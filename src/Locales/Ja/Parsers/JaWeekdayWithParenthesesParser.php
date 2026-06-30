<?php

namespace DirectoryTree\Chrono\Locales\Ja\Parsers;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;

readonly class JaWeekdayWithParenthesesParser extends JaWeekdayParser
{
    /**
     * Parse Japanese weekday references inside parentheses.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?:\(|（)(?<weekday>日|月|火|水|木|金|土)(?:\)|）)/u', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(fn (array $match): ?ParsedResult => $this->result($match, $reference, $options, 'parser/JPWeekdayWithParenthesesParser'), $matches)));
    }
}
