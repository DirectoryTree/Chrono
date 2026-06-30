<?php

namespace DirectoryTree\Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Locales\De\CreatesParsedComponents;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class DeSpecificTimeExpressionParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse German-specific time expressions such as "8h10m00s Uhr".
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all(
            '/(^|\s|T)(?:(?:um|von)\s*)?(?<hour>\d{1,2})(?:h|:)?(?:(?<minute>\d{1,2})(?:m|:)?)?(?:(?<second>\d{1,2})(?:s)?)?(?:\s*Uhr)?(?:\s*(?<suffix>morgens|vormittags|nachmittags|abends|nachts|am\s+(?:Morgen|Vormittag|Nachmittag|Abend)|in\s+der\s+Nacht))?(?=\W|$)/iu',
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference): ?ParsedResult {
            $leading = $match[1][0];
            $index = $match[0][1] + strlen($leading);
            $matchedText = substr($match[0][0], strlen($leading));

            if (preg_match('/^\d{4}$/', $matchedText) === 1) {
                return null;
            }

            $start = $this->timeComponents($reference->date, $match);

            if ($start === null) {
                return null;
            }

            $end = $this->endComponents($text, $match, $start->date());
            $resultText = $matchedText.($end['text'] ?? '');
            $start->addTag('parser/DESpecificTimeExpressionParser');

            return new ParsedResult($index, $resultText, $start, $end['components'] ?? null);
        }, $matches)));
    }

    /**
     * Build end components when a second range expression follows the first time.
     *
     * @return array{text: string, components: ParsedComponents}|null
     */
    protected function endComponents(string $text, array $match, CarbonImmutable $start): ?array
    {
        $remaining = substr($text, $match[0][1] + strlen($match[0][0]));

        if (preg_match('/^\s*(?:-|–|~|〜|bis(?:\s+um)?|\?)\s*(?<hour>\d{1,2})(?:h|:)?(?:(?<minute>\d{1,2})(?:m|:)?)?(?:(?<second>\d{1,2})(?:s)?)?(?:\s*Uhr)?(?:\s*(?<suffix>morgens|vormittags|nachmittags|abends|nachts|am\s+(?:Morgen|Vormittag|Nachmittag|Abend)|in\s+der\s+Nacht))?(?=\W|$)/iu', $remaining, $endMatch) !== 1) {
            return null;
        }

        $components = $this->timeComponents($start, $endMatch);

        if ($components === null) {
            return null;
        }

        if ($components->date()->lessThanOrEqualTo($start)) {
            $date = $components->date()->addDay();
            $components = $this->components($date, [
                'hour' => $date->hour,
                'minute' => $date->minute,
                ...($date->second !== 0 ? ['second' => $date->second] : []),
                'meridiem' => $date->hour < 12 ? Meridiem::AM->value : Meridiem::PM->value,
            ]);
        }

        return [
            'text' => $endMatch[0],
            'components' => $components,
        ];
    }

    /**
     * Extract time components from a German-specific time regex match.
     */
    protected function timeComponents(CarbonImmutable $date, array $match): ?ParsedComponents
    {
        $hour = (int) $this->matchValue($match, 'hour');
        $minuteText = $this->matchValue($match, 'minute');
        $secondText = $this->matchValue($match, 'second');
        $minute = $minuteText !== '' ? (int) $minuteText : 0;
        $second = $secondText !== '' ? (int) $secondText : 0;
        $hasSecond = $secondText !== '';

        if ($minute >= 60 || $second >= 60 || $hour > 24) {
            return null;
        }

        $suffix = $this->normalize($this->matchValue($match, 'suffix'));
        $meridiem = $hour >= 12 ? Meridiem::PM : null;

        if ($suffix !== '') {
            if (str_contains($suffix, 'morgen') || str_contains($suffix, 'vormittag')) {
                $meridiem = Meridiem::AM;
                $hour = $hour === 12 ? 0 : $hour;
            }

            if (str_contains($suffix, 'nachmittag') || str_contains($suffix, 'abend')) {
                $meridiem = Meridiem::PM;
                $hour = $hour === 12 ? 12 : $hour + 12;
            }

            if (str_contains($suffix, 'nacht')) {
                if ($hour === 12) {
                    $meridiem = Meridiem::AM;
                    $hour = 0;
                } elseif ($hour < 6) {
                    $meridiem = Meridiem::AM;
                } else {
                    $meridiem = Meridiem::PM;
                    $hour += 12;
                }
            }
        }

        if ($hour > 23) {
            return null;
        }

        $date = $date->hour($hour)->minute($minute)->second($second)->millisecond(0);

        $components = $this->components($date, [
            'hour' => $hour,
            'minute' => $minute,
            ...($hasSecond ? ['second' => $second] : []),
            ...($meridiem !== null ? ['meridiem' => $meridiem->value] : []),
        ]);

        if ($meridiem === null) {
            $components->imply('meridiem', $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);
        }

        return $components;
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return strtr(mb_strtolower(str_replace(' ', '', $value)), [
            'ä' => 'a',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss',
        ]);
    }

    /**
     * Read a named regex match from offset-capture and plain match arrays.
     */
    protected function matchValue(array $match, string $key): string
    {
        $value = $match[$key] ?? '';

        if (is_array($value)) {
            return $value[0] ?? '';
        }

        return $value;
    }
}
