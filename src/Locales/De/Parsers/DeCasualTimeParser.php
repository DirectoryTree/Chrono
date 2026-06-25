<?php

namespace Chrono\Locales\De\Parsers;

use Chrono\Locales\De\CreatesParsedComponents;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class DeCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the German casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<this>diesen)?\s*(?<time>morgen|vormittag|mittags?|nachmittag|abend|nacht|mitternacht)(?=\W|$)';
    }

    /**
     * Extract German casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $time = $this->normalize($match['time'][0]);

        if ($time === 'morgen' && ($match['this'][0] ?? '') === '') {
            return null;
        }

        $components = $this->timeComponents(new ParsedComponents($reference->date), $time);
        $components->addTag('parser/DECasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by German casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }

    /**
     * Apply a German casual time keyword to parsed components.
     */
    public function timeComponents(ParsedComponents $components, string $time): ParsedComponents
    {
        $date = $components->date();
        $hour = match ($time) {
            'morgen' => 6,
            'vormittag' => 9,
            'mittag', 'mittags' => 12,
            'nachmittag' => 15,
            'abend' => 18,
            'nacht' => 22,
            'mitternacht' => 0,
        };

        if ($time === 'mitternacht' && $date->hour > 1) {
            $date = $date->addDay();
        }

        $date = $date->hour($hour)->minute(0)->second(0)->millisecond(0);

        $meridiem = in_array($time, ['morgen', 'vormittag', 'mittag', 'mittags', 'mitternacht'], true)
            ? Meridiem::AM
            : Meridiem::PM;

        return $this->components($date, [
            ...($time === 'mitternacht' ? ['year' => $date->year, 'month' => $date->month, 'day' => $date->day] : []),
            'hour' => $hour,
            'minute' => 0,
            'second' => 0,
            'meridiem' => $meridiem->value,
        ]);
    }

    /**
     * Normalize German umlaut spelling variants.
     */
    protected function normalize(string $value): string
    {
        return strtr(mb_strtolower($value), [
            'ä' => 'a',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss',
        ]);
    }
}
