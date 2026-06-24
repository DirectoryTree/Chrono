<?php

namespace Chrono\Locales\Es\Parsers;

use Chrono\Locales\Es\CreatesParsedComponents;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class EsCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Spanish casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:(?<article>el|la)\s+)?(?<today>esta\s*)?(?<word>mañana|manana|tarde|medianoche|mediodia|mediodía|noche)(?=\W|$)';
    }

    /**
     * Extract Spanish casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = $this->normalize($match['word'][0]);

        if (in_array($word, ['manana', 'mañana'], true) && ($match['today'][0] ?? '') === '') {
            return null;
        }

        $date = $reference->date;
        $hour = match ($word) {
            'tarde' => 15,
            'noche' => 22,
            'manana', 'mañana' => 6,
            'mediodia', 'mediodía' => 12,
            'medianoche' => 0,
        };

        if ($word === 'medianoche') {
            $date = $date->addDay();
        }

        $date = $date->hour($hour)->minute(0)->second(0)->millisecond(0);
        $components = $this->components($date, [
            ...($word === 'medianoche' ? ['year' => $date->year, 'month' => $date->month, 'day' => $date->day] : []),
            'hour' => $hour,
            ...($word === 'medianoche' ? ['minute' => 0, 'second' => 0] : []),
            ...($word !== 'medianoche' ? ['meridiem' => $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value] : []),
        ]);
        $components->addTag('parser/ESCasualTimeParser');

        return new ParsedResult($match[0][1], $match[0][0], $components);
    }

    /**
     * Normalize Spanish accent variants.
     */
    protected function normalize(string $value): string
    {
        return strtr(mb_strtolower($value), [
            'í' => 'i',
            'Í' => 'i',
        ]);
    }
}
