<?php

namespace Chrono\Locales\Ja\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Ja\JaConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class JaWeekdayParser implements Parser
{
    /**
     * Parse Japanese weekday references.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<prefix>前の|次の|今週)?(?<weekday>日|月|火|水|木|金|土)(?:曜日|曜)/u', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(fn (array $match): ?ParsedResult => $this->result($match, $reference, $options, 'parser/JPWeekdayParser'), $matches)));
    }

    /**
     * Get result.
     */
    protected function result(array $match, Reference $reference, Options $options, string $tag): ?ParsedResult
    {
        $weekday = JaConstants::WEEKDAYS[$match['weekday'][0]] ?? null;

        if ($weekday === null) {
            return null;
        }

        $modifier = match ($match['prefix'][0] ?? '') {
            '前の' => 'last',
            '次の' => 'next',
            '今週' => 'this',
            default => null,
        };

        $components = Weekdays::createParsingComponentsAtWeekday($reference, $weekday, $modifier)
            ->imply('hour', 0)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->addTag($tag);

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }
}
