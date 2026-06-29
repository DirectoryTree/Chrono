<?php

namespace Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\De\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;
use Chrono\Refiners\ExtractTimezoneRefiner;

readonly class DeTimeExpressionExtensionParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse German time-expression extensions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $suffix = $this->suffixPattern();
        $results = $this->parseRanges($text, $reference, $suffix);
        $patterns = [
            '/\b(?:(?<prefix>um)\s*)?(?<hour>\d{1,2})(?<separator>[:.])(?<minute>\d{2})\s*(?:uhr)?(?:\s+(?<suffix>'.$suffix.'))?(?=\W|$)/iu',
            '/\b(?:(?<prefix>um)\s*)?(?<hour>\d{1,2})h(?<minute>\d{2})?\s*(?:uhr)?(?:\s+(?<suffix>'.$suffix.'))?(?=\W|$)/iu',
            '/\b(?:(?<prefix>um)\s*)?(?<hour>\d{1,2})\s*uhr(?:\s+(?<suffix>'.$suffix.'))?(?=\W|$)/iu',
            '/\b(?<prefix>um)\s+(?<hour>\d{1,2})(?:\s+(?<suffix>'.$suffix.'))?(?=\W|$)/iu',
            '/\b(?:am\s+)?(?<midday>mittag)\b/iu',
        ];

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

            foreach ($matches as $match) {
                $result = isset($match['midday'])
                    ? $this->middayResult($match, $reference)
                    : $this->result($match, $reference);

                if ($result !== null) {
                    $results[] = $result;
                }
            }
        }

        usort($results, fn (ParsedResult $a, ParsedResult $b) => $a->index <=> $b->index ?: strlen($b->text) <=> strlen($a->text));

        return (new ExtractTimezoneRefiner)->refine($text, $results, $reference, $options);
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseRanges(string $text, Reference $reference, string $suffix): array
    {
        $time = '(?<hour>\d{1,2})(?:(?<separator>[:.]|h)(?<minute>\d{2})?)?\s*(?:uhr)?(?:\s+(?<suffix>'.$suffix.'))?';
        $endTime = '(?<endHour>\d{1,2})(?:(?<endSeparator>[:.]|h)(?<endMinute>\d{2})?)?\s*(?:uhr)?(?:\s+(?<endSuffix>'.$suffix.'))?';

        preg_match_all('/\b(?:von\s+)?'.$time.'\s*(?:-|bis)\s*'.$endTime.'(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(fn (array $match): ?ParsedResult => $this->rangeResult($match, $reference), $matches)));
    }

    /**
     * Create a parsed result for a time range.
     */
    protected function rangeResult(array $match, Reference $reference): ?ParsedResult
    {
        $start = $this->time($match, '', $match['endSuffix'][0] ?? '');
        $end = $this->time($match, 'end', '');

        if ($start === null || $end === null) {
            return null;
        }

        $startDate = $reference->date
            ->hour($start['hour'])
            ->minute($start['minute'])
            ->second(0)
            ->millisecond(0);
        $endDate = $reference->date
            ->hour($end['hour'])
            ->minute($end['minute'])
            ->second(0)
            ->millisecond(0);

        if ($endDate->lessThanOrEqualTo($startDate)) {
            $endDate = $endDate->addDay();
        }

        $start = $this->timeComponents($startDate, $start['meridiemCertain']);
        $start->addTag('parser/DETimeExpressionExtensionParser');

        return new ParsedResult(
            $match[0][1],
            trim($match[0][0]),
            $start,
            $this->timeComponents($endDate, $end['meridiemCertain']),
        );
    }

    /**
     * @return array{hour: int, minute: int, meridiemCertain: bool}|null
     */
    protected function time(array $match, string $prefix, string $pairedSuffix): ?array
    {
        $hourKey = $prefix === '' ? 'hour' : 'endHour';
        $minuteKey = $prefix === '' ? 'minute' : 'endMinute';
        $suffixKey = $prefix === '' ? 'suffix' : 'endSuffix';

        $hour = (int) $match[$hourKey][0];
        $minute = ($match[$minuteKey][0] ?? '') !== '' ? (int) $match[$minuteKey][0] : 0;
        $suffix = ($match[$suffixKey][0] ?? '') ?: $pairedSuffix;
        $meridiemCertain = $suffix !== '' || ($hour < 12 || $hour >= 18);
        $hour = $this->hour($hour, $suffix);

        if ($hour > 23 || $minute > 59) {
            return null;
        }

        return compact('hour', 'minute', 'meridiemCertain');
    }

    /**
     * Get result.
     */
    protected function result(array $match, Reference $reference): ?ParsedResult
    {
        $time = $this->time($match, '', '');

        if ($time === null) {
            return null;
        }

        $date = $reference->date
            ->hour($time['hour'])
            ->minute($time['minute'])
            ->second(0)
            ->millisecond(0);

        $components = $this->timeComponents($date, $time['meridiemCertain']);
        $components->addTag('parser/DETimeExpressionExtensionParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Create a parsed result for a midday expression.
     */
    protected function middayResult(array $match, Reference $reference): ParsedResult
    {
        $date = $reference->date->hour(12)->minute(0)->second(0)->millisecond(0);

        return new ParsedResult(
            $match['midday'][1],
            $match['midday'][0],
            $this->timeComponents($date, true)->addTag('parser/DETimeExpressionExtensionParser'),
        );
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function timeComponents(CarbonImmutable $date, bool $meridiemCertain): ParsedComponents
    {
        return $this->components($date, [
            'hour' => $date->hour,
            'minute' => $date->minute,
            ...($meridiemCertain ? ['meridiem' => $date->hour < 12 ? 0 : 1] : []),
        ]);
    }

    /**
     * Resolve the hour value.
     */
    protected function hour(int $hour, string $suffix): int
    {
        $suffix = $this->normalize($suffix);

        if (in_array($suffix, ['nachmittags', 'abends', 'amabend'], true) && $hour < 12) {
            return $hour + 12;
        }

        if ($suffix === 'indernacht' && $hour >= 6 && $hour < 12) {
            return $hour + 12;
        }

        return $hour;
    }

    /**
     * Get the parser pattern.
     */
    protected function suffixPattern(): string
    {
        return 'morgens|vormittags|nachmittags|abends|am\s+abend|in\s+der\s+nacht';
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return strtr(strtolower(str_replace(' ', '', $value)), [
            'ä' => 'a',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss',
            'Ä' => 'a',
            'Ö' => 'o',
            'Ü' => 'u',
        ]);
    }
}
