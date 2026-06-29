<?php

namespace Chrono\Locales\Fr\Parsers;

use Chrono\Locales\Fr\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class FrSpecificTimeExpressionParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse French specific time expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all(
            '/(^|\s|T)(?:(?:[àa])\s*)?(?<hour>\d{1,2})(?:h|:)?(?:(?<minute>\d{1,2})(?:m|:)?)?(?:(?<second>\d{1,2})(?:s|:)?)?(?:\s*(?<meridiem>A\.M\.|P\.M\.|AM?|PM?))?(?=\W|$)/iu',
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(fn (array $match): ?ParsedResult => $this->result($text, $match, $reference), $matches)));
    }

    /**
     * Get result.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $leading = $match[1][0] ?? '';
        $index = $match[0][1] + strlen($leading);
        $matchedText = substr($match[0][0], strlen($leading));

        if (preg_match('/^\d{4}$/', $matchedText) === 1) {
            return null;
        }

        $remainingText = substr($text, $match[0][1] + strlen($match[0][0]));
        $endMatch = [];

        preg_match('/^\s*(?:-|–|~|〜|[àa]|\?)\s*(?<hour>\d{1,2})(?:h|:)?(?:(?<minute>\d{1,2})(?:m|:)?)?(?:(?<second>\d{1,2})(?:s|:)?)?(?:\s*(?<meridiem>A\.M\.|P\.M\.|AM?|PM?))?(?=\W|$)/iu', $remainingText, $endMatch);

        $start = $this->timeComponents($match, $reference, null, $endMatch['meridiem'] ?? '');

        if ($start === null) {
            return null;
        }

        $end = null;

        if ($endMatch !== []) {
            $end = $this->timeComponents($this->offsetlessMatch($endMatch), $reference, $start);

            if ($end !== null) {
                $matchedText .= $endMatch[0];
            }
        }

        $start->addTag('parser/FRSpecificTimeExpressionParser');

        return new ParsedResult($index, trim($matchedText), $start, $end);
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function timeComponents(array $match, Reference $reference, ?ParsedComponents $base = null, string $pairedMeridiem = ''): ?ParsedComponents
    {
        $hour = (int) $match['hour'][0];
        $minute = ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0;
        $second = ($match['second'][0] ?? '') !== '' ? (int) $match['second'][0] : 0;

        if ($minute >= 60 || $second >= 60 || $hour > 24) {
            return null;
        }

        $meridiem = null;

        if ($hour >= 12) {
            $meridiem = 1;
        }

        $meridiemText = ($match['meridiem'][0] ?? '') ?: $pairedMeridiem;

        if ($meridiemText !== '') {
            if ($hour > 12) {
                return null;
            }

            $ampm = strtolower(substr(str_replace('.', '', $meridiemText), 0, 1));

            if ($ampm === 'a') {
                $meridiem = 0;
                $hour = $hour === 12 ? 0 : $hour;
            } elseif ($ampm === 'p') {
                $meridiem = 1;
                $hour = $hour === 12 ? 12 : $hour + 12;
            }
        }

        $date = ($base?->date() ?? $reference->date)
            ->hour($hour)
            ->minute($minute)
            ->second($second)
            ->millisecond(0);

        $components = $this->components($date, [
            'hour' => $hour,
            'minute' => $minute,
            ...((($match['second'][0] ?? '') !== '') ? ['second' => $second] : []),
            ...($meridiem !== null ? ['meridiem' => $meridiem] : []),
        ]);

        if ($base !== null && $components->date()->lessThanOrEqualTo($base->date())) {
            $components = $this->components($components->date()->addDay(), [
                'hour' => $hour,
                'minute' => $minute,
                ...((($match['second'][0] ?? '') !== '') ? ['second' => $second] : []),
                ...($meridiem !== null ? ['meridiem' => $meridiem] : []),
            ]);
        }

        return $components;
    }

    /**
     * Convert preg_match output into the offset-carrying shape used by preg_match_all.
     *
     * @return array<string, array{0: string}>
     */
    protected function offsetlessMatch(array $match): array
    {
        return array_map(fn (string $value): array => [$value], $match);
    }
}
