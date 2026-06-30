<?php

namespace DirectoryTree\Chrono\Locales\Fr\Parsers;

use DirectoryTree\Chrono\Locales\Fr\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class FrCasualDateParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the French casual date pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<word>maintenant|aujourd[’\']hui|demain(?:\s+matin)?|hier|la\s+veille|ce\s+matin|cet\s+apr[eè]s-midi|cet\s+aprem|ce\s+soir|midi|minuit)(?:\s+(?<hour>\d{1,2})(?:(?::|h)(?<minute>\d{2})?|h)?)?\b';
    }

    /**
     * Extract French casual date components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = $this->normalize($match['word'][0]);
        $date = $reference->date;

        if (str_starts_with($word, 'demain')) {
            $date = $date->addDay();
        } elseif ($word === 'hier' || $word === 'laveille') {
            $date = $date->subDay();
        }

        if ($word === 'laveille') {
            $date = $date->hour(0)->minute(0)->second(0)->millisecond(0);
        } elseif (str_ends_with($word, 'matin') && $word !== 'demainmatin') {
            $date = $date->hour(8)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'demainmatin') {
            $date = $date->hour(8)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'cetapres-midi' || $word === 'cetaprem') {
            $date = $date->hour(14)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'cesoir') {
            $date = $date->hour(18)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'midi') {
            $date = $date->hour(12)->minute(0)->second(0)->millisecond(0);
        } elseif ($word === 'minuit') {
            $date = $date->hour(0)->minute(0)->second(0)->millisecond(0);
        }

        if (($match['hour'][0] ?? '') !== '') {
            $date = $date
                ->hour((int) $match['hour'][0])
                ->minute(($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0)
                ->second(0)
                ->millisecond(0);
        }

        $known = [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            ...((($match['hour'][0] ?? '') !== '' || ! in_array($word, ['aujourdhui', 'demain', 'hier'], true)) ? ['hour' => $date->hour, 'minute' => $date->minute] : []),
        ];

        if ($word === 'maintenant') {
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
        $components->addTag('parser/FRCasualDateParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }

    /**
     * Normalize French casual date variants.
     */
    protected function normalize(string $value): string
    {
        return strtr(strtolower(str_replace([' ', '’', "'"], '', $value)), [
            'à' => 'a',
            'â' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'î' => 'i',
            'ï' => 'i',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c',
            'À' => 'a',
            'Â' => 'a',
            'É' => 'e',
            'È' => 'e',
            'Ê' => 'e',
            'Î' => 'i',
            'Ï' => 'i',
            'Ô' => 'o',
            'Ù' => 'u',
            'Û' => 'u',
            'Ç' => 'c',
        ]);
    }
}
