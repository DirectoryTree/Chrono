<?php

namespace DirectoryTree\Chrono\Locales\Nl\Parsers;

use DirectoryTree\Chrono\Locales\Nl\CreatesParsedComponents;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class NlCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Dutch casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<today>deze)?\s*(?<time>namiddag|avond|middernacht|ochtend|middag|\'s middags|\'s avonds|\'s ochtends)(?=\W|$)';
    }

    /**
     * Extract Dutch casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $components = $this->timeComponents(new ParsedComponents($reference->date), mb_strtolower($match['time'][0]), ($match['today'][0] ?? '') !== '');
        $components->addTag('parser/NLCasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Dutch casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }

    /**
     * Apply a Dutch casual time keyword to existing parsed components.
     */
    public function timeComponents(ParsedComponents $components, string $time, bool $assignDate = false): ParsedComponents
    {
        $date = $components->date();
        $hour = match ($time) {
            'namiddag', "'s namiddags" => 15,
            'avond', "'s avonds" => 20,
            'middernacht' => 0,
            'ochtend', "'s ochtends" => 6,
            'middag', "'s middags" => 12,
        };

        if ($time === 'middernacht') {
            $date = $date->addDay();
        }

        $date = $date->hour($hour)->minute(0)->second(0)->millisecond(0);

        $known = [];

        foreach ($components->getCertainComponents() as $component) {
            $known[$component] = match ($component) {
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
                'weekday' => $date->dayOfWeek,
                'timezoneOffset' => $date->offsetMinutes,
                default => $components->get($component),
            };
        }

        return $this->components($date, [
            ...$known,
            ...($assignDate || $time === 'middernacht' ? ['year' => $date->year, 'month' => $date->month, 'day' => $date->day] : []),
        ])
            ->imply('hour', $hour)
            ->imply('minute', 0)
            ->imply('second', 0)
            ->imply('millisecond', 0)
            ->imply('meridiem', $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);
    }
}
