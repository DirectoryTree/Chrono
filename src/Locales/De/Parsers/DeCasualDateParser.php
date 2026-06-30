<?php

namespace DirectoryTree\Chrono\Locales\De\Parsers;

use DirectoryTree\Chrono\Dates;
use DirectoryTree\Chrono\Locales\De\CreatesParsedComponents;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class DeCasualDateParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the German casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>jetzt|heute(?:\s+(?:morgen|nachmittag|abend|nacht))?|morgen(?:\s+morgen|\s+nachmittag|\s+abend|\s+nacht)?|uebermorgen(?:\s+morgen|\s+vormittag|\s+nachmittag|\s+abend|\s+nacht)?|übermorgen(?:\s+morgen|\s+vormittag|\s+nachmittag|\s+abend|\s+nacht)?|gestern(?:\s+morgen|\s+vormittag|\s+nachmittag|\s+abend|\s+nacht)?|vorgestern(?:\s+morgen|\s+vormittag|\s+nachmittag|\s+abend|\s+nacht)?|letzte\s+nacht|mittags|mitternacht)(?:\s+(?:um\s+)?(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?:uhr)?)?\b';
    }

    /**
     * Extract German casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = $this->normalize($match['word'][0]);
        $date = $reference->date;

        if ($word === 'morgen') {
            $date = $date->addDay();
        } elseif (str_starts_with($word, 'morgen')) {
            $date = $date->addDay();
        } elseif (str_starts_with($word, 'uebermorgen') || str_starts_with($word, 'ubermorgen')) {
            $date = $date->addDays(2);
        } elseif (str_starts_with($word, 'vorgestern')) {
            $date = $date->subDays(2);
        } elseif (str_starts_with($word, 'gestern') || $word === 'letztenacht') {
            $date = $date->subDay();
        }

        if ($word === 'mitternacht') {
            if ($date->hour >= 6) {
                $date = $date->addDay();
            }

            $date = $date->hour(0)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'letztenacht') {
            $date = $date->hour(0)->minute(0)->second(0)->millisecond(0);
        } elseif (str_ends_with($word, 'nacht')) {
            $date = $date->hour(22)->minute(0)->second(0)->millisecond(0);
        } elseif ($word !== 'morgen' && str_ends_with($word, 'morgen')) {
            $date = $date->hour(6)->minute(0)->second(0)->millisecond(0);
        } elseif (str_ends_with($word, 'vormittag')) {
            $date = $date->hour(9)->minute(0)->second(0)->millisecond(0);
        } elseif (str_ends_with($word, 'nachmittag')) {
            $date = $date->hour(15)->minute(0)->second(0)->millisecond(0);
        } elseif (str_ends_with($word, 'abend')) {
            $date = $date->hour(18)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'mittags') {
            $date = $date->hour(12)->minute(0)->second(0)->millisecond(0);
        }

        if (($match['hour'][0] ?? '') !== '') {
            $hour = (int) $match['hour'][0];

            if ((str_ends_with($word, 'abend') || str_ends_with($word, 'nacht')) && $hour >= 6 && $hour < 12) {
                $hour += 12;
            }

            $date = $date
                ->hour($hour)
                ->minute(($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0)
                ->second(0)
                ->millisecond(0);
        }

        $meridiem = $this->meridiem($word);

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            ...((($match['hour'][0] ?? '') !== '' || ! in_array($word, ['heute', 'morgen', 'gestern'], true)) ? ['hour' => $date->hour, 'minute' => $date->minute] : []),
            ...($meridiem !== null ? ['meridiem' => $meridiem->value] : []),
        ];

        if ($word === 'jetzt') {
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

        if (($match['hour'][0] ?? '') === '' && in_array($word, ['heute', 'morgen', 'gestern', 'uebermorgen', 'ubermorgen', 'vorgestern'], true)) {
            Dates::implySimilarTime($components, $date);
        }

        $components->addTag('parser/DECasualDateParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }

    /**
     * Normalize German umlaut spelling variants.
     */
    protected function normalize(string $value): string
    {
        return strtr(strtolower(str_replace(' ', '', $value)), [
            'ä' => 'a',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss',
            'Ä' => 'a',
            'Ö' => 'o',
            'Ü' => 'u',
        ]);
    }

    /**
     * Resolve a German day period into its meridiem.
     */
    protected function meridiem(string $word): ?Meridiem
    {
        if (str_ends_with($word, 'morgen') || str_ends_with($word, 'vormittag')) {
            return Meridiem::AM;
        }

        if ($word === 'mittags' || $word === 'mitternacht') {
            return Meridiem::AM;
        }

        if (str_ends_with($word, 'nachmittag') || str_ends_with($word, 'abend') || ($word !== 'letztenacht' && str_ends_with($word, 'nacht'))) {
            return Meridiem::PM;
        }

        return null;
    }
}
