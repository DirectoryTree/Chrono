<?php

namespace DirectoryTree\Chrono\Locales\Vi\Parsers;

use DirectoryTree\Chrono\Locales\Vi\CreatesParsedComponents;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ViCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Vietnamese casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:buổi\s*)?(?<time>sáng sớm|sáng|trưa|chiều|tối|đêm|nửa đêm|bình minh)(?=\W|$)';
    }

    /**
     * Extract Vietnamese casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['time'][0]);
        $hour = match ($word) {
            'bình minh', 'sáng sớm' => 6,
            'sáng' => 9,
            'trưa' => 12,
            'chiều' => 15,
            'tối' => 19,
            'đêm' => 22,
            'nửa đêm' => 0,
        };

        $date = $reference->date->hour($hour)->minute(0)->second(0)->millisecond(0);

        return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, [
            'hour' => $hour,
            'minute' => 0,
        ])
            ->imply('meridiem', $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value)
            ->addTag('parser/VICasualTimeParser'));
    }

    /**
     * Get the Unicode-aware left boundary used by Vietnamese casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
