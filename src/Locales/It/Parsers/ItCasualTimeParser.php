<?php

namespace Chrono\Locales\It\Parsers;

use Chrono\Locales\It\CreatesParsedComponents;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class ItCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Italian casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:questo|questa)?\s{0,3}(?<time>mattina|pomeriggio|sera|notte|mezzanotte|mezzogiorno)(?=\W|$)';
    }

    /**
     * Extract Italian casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $time = mb_strtolower($match['time'][0]);
        $date = $reference->date;
        $hour = match ($time) {
            'pomeriggio' => 15,
            'sera', 'notte' => 20,
            'mattina' => 6,
            'mezzogiorno' => 12,
            'mezzanotte' => 0,
        };

        if ($time === 'mezzanotte') {
            $date = $date->addDay();
        }

        $date = $date->hour($hour)->minute(0)->second(0)->millisecond(0);

        $known = [
            ...($time === 'mezzanotte' ? ['year' => $date->year, 'month' => $date->month, 'day' => $date->day] : []),
            'hour' => $hour,
            ...($time !== 'mezzanotte' ? ['meridiem' => $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value] : []),
            ...($time === 'mezzanotte' ? ['minute' => 0, 'second' => 0] : []),
        ];

        $components = $this->components($date, $known);
        $components->addTag('parser/ITCasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Italian casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
