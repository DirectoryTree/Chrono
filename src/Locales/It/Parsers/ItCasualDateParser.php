<?php

namespace DirectoryTree\Chrono\Locales\It\Parsers;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Locales\It\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class ItCasualDateParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Italian casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>ora|oggi|stasera|questa sera|domani|dmn|ieri\s*sera)\b';
    }

    /**
     * Extract Italian casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = strtolower($match['word'][0]);
        $date = match (true) {
            in_array($word, ['domani', 'dmn'], true) => $reference->date->addDay(),
            preg_match('/ieri\s*sera/iu', $word) === 1 && $reference->date->hour > 6 => $reference->date->subDay(),
            default => $reference->date,
        };

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ];

        if ($word === 'ora') {
            $known = [
                ...$known,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
                'millisecond' => $date->millisecond,
                'timezoneOffset' => $date->offsetMinutes,
            ];
        }

        if (in_array($word, ['stasera', 'questa sera'], true)) {
            $date = $date->hour(22)->minute(0)->second(0)->millisecond(0);
            $known['hour'] = 22;
            $known['minute'] = 0;
        }

        if (preg_match('/ieri\s*sera/iu', $word) === 1) {
            $date = $date->hour(0)->minute(0)->second(0)->millisecond(0);
            $known['hour'] = 0;
            $known['minute'] = 0;
        }

        $components = $this->components($date, $known);

        if (! in_array($word, ['ora', 'stasera', 'questa sera'], true) && preg_match('/ieri\s*sera/iu', $word) !== 1) {
            Dates::implySimilarTime($components, $date);
            $components->delete('meridiem');
        }

        $components->addTag('parser/ITCasualDateParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }
}
