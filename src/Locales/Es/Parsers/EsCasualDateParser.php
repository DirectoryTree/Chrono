<?php

namespace Chrono\Locales\Es\Parsers;

use Chrono\Locales\Es\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class EsCasualDateParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Spanish casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>ahora|hoy|ma(?:ñ|n)ana|ayer(?:\s+de\s+noche)?|esta\s+ma(?:ñ|n)ana|esta\s+tarde|esta\s+noche)(?:\s+(?:a\s+las\s+)?(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?<meridiem>am|pm)?)?\b';
    }

    /**
     * Extract Spanish casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = strtr(mb_strtolower($match['word'][0]), ['Ñ' => 'ñ']);
        $date = $reference->date;

        if ($word === 'mañana' || $word === 'manana') {
            $date = $date->addDay();
        } elseif (str_starts_with($word, 'ayer')) {
            $date = $date->subDay();
        }

        if (str_contains($word, 'de noche') || $word === 'esta noche') {
            $date = $date->hour(22)->minute(0)->second(0)->millisecond(0);
        } elseif (str_contains($word, 'mañana') || str_contains($word, 'manana')) {
            if (str_starts_with($word, 'esta')) {
                $date = $date->hour(6)->minute(0)->second(0)->millisecond(0);
            }
        } elseif ($word === 'esta tarde') {
            $date = $date->hour(15)->minute(0)->second(0)->millisecond(0);
        }

        if (($match['hour'][0] ?? '') !== '') {
            $meridiem = ($match['meridiem'][0] ?? '') ?: ($word === 'esta noche' ? 'pm' : null);

            $date = $date
                ->hour($this->meridiemHour((int) $match['hour'][0], $meridiem))
                ->minute(($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0)
                ->second(0)
                ->millisecond(0);
        }

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            ...((($match['hour'][0] ?? '') !== '' || $word === 'esta noche' || str_contains($word, 'de noche') || str_starts_with($word, 'esta ')) ? ['hour' => $date->hour, 'minute' => $date->minute] : []),
        ];

        if ($word === 'ahora') {
            $known = [
                ...$known,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
                'millisecond' => $date->millisecond,
                'timezoneOffset' => $date->offsetMinutes,
            ];
        }

        $components = $this->components($date, $known);
        $components->addTag('parser/ESCasualDateParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }
}
