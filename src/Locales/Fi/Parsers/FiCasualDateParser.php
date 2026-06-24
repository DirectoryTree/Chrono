<?php

namespace Chrono\Locales\Fi\Parsers;

use Chrono\Dates;
use Chrono\Locales\Fi\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FiCasualDateParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Finnish casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<date>nyt|tänään|huomenna|ylihuomenna|eilen|toissapäivänä|viime\s*yönä)(?:\s*(?<time>aamulla|aamuna|aamupäivällä|päivällä|iltapäivällä|illalla|yöllä|keskiyöllä))?(?=\W|$)';
    }

    /**
     * Extract Finnish casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['date'][0]);
        $date = match ($word) {
            'huomenna' => $reference->date->addDay(),
            'ylihuomenna' => $reference->date->addDays(2),
            'eilen' => $reference->date->subDay(),
            'toissapäivänä' => $reference->date->subDays(2),
            default => $reference->date,
        };

        if (preg_match('/viime\s*yönä/iu', $word) === 1 && $date->hour > 6) {
            $date = $date->subDay();
        }

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ];

        if ($word === 'nyt') {
            $known = [
                ...$known,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
                'millisecond' => $date->millisecond,
                'timezoneOffset' => $date->offsetMinutes,
            ];
        }

        if (preg_match('/viime\s*yönä/iu', $word) === 1) {
            $date = $date->hour(0)->minute(0)->second(0)->millisecond(0);
            $known['hour'] = 0;
        }

        $components = $this->components($date, $known);

        if ($word !== 'nyt' && ($match['time'][0] ?? '') === '' && preg_match('/viime\s*yönä/iu', $word) !== 1) {
            Dates::implySimilarTime($components, $date);
            $components->delete('meridiem');
        }

        if (($match['time'][0] ?? '') !== '') {
            $components = (new FiCasualTimeParser)->timeComponents($components, mb_strtolower($match['time'][0]));
        }

        $components->addTag('parser/FICasualDateParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Finnish casual date parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
