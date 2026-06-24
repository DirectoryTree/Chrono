<?php

namespace Chrono\Locales\Pt\Parsers;

use Chrono\Locales\Pt\CreatesParsedComponents;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class PtCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Portuguese casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:esta\s*)?(?<time>manha|manhã|tarde|meia-noite|meio-dia|noite)(?=\W|$)';
    }

    /**
     * Extract Portuguese casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = mb_strtolower($match['time'][0]);
        $date = $reference->date;
        $tag = match ($word) {
            'tarde' => 'casualReference/afternoon',
            'noite' => 'casualReference/evening',
            'manha', 'manhã' => 'casualReference/morning',
            'meia-noite' => 'casualReference/midnight',
            'meio-dia' => 'casualReference/noon',
        };

        $hour = match ($word) {
            'tarde' => 15,
            'noite' => 22,
            'manha', 'manhã' => 6,
            'meia-noite' => 0,
            'meio-dia' => 12,
        };

        if ($word === 'meia-noite') {
            $date = $date->addDay();
        }

        $components = $this->components($date->hour($hour)->minute(0)->second(0)->millisecond(0), [
            'hour' => $hour,
            'minute' => 0,
            'second' => 0,
        ])
            ->imply('meridiem', in_array($word, ['manha', 'manhã', 'meia-noite', 'meio-dia'], true) ? Meridiem::AM->value : Meridiem::PM->value)
            ->addTag($tag)
            ->addTag('parser/PTCasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Portuguese parsers.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }
}
