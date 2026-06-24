<?php

namespace Chrono\Locales\Fr\Parsers;

use Chrono\Locales\Fr\CreatesParsedComponents;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FrCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the French casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:(?:cet?|cette)\s*)?(?<time>matin|soir|apr[eè]s-midi|aprem|a\s+midi|à\s+minuit)(?=\W|$)';
    }

    /**
     * Extract French casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $time = $this->normalize($match['time'][0]);
        $hour = match ($time) {
            'apres-midi', 'aprem' => 14,
            'soir' => 18,
            'matin' => 8,
            'amidi' => 12,
            'aminuit' => 0,
        };

        $date = $reference->date
            ->hour($hour)
            ->minute(0)
            ->second(0)
            ->millisecond(0);

        $components = $this->components($date, [
            'hour' => $hour,
            'minute' => 0,
            'meridiem' => $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value,
        ]);
        $components->addTag('parser/FRCasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by French casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }

    /**
     * Normalize French casual time variants.
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
