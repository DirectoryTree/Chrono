<?php

namespace Chrono\Locales\Fi\Parsers;

use Chrono\Locales\Fi\CreatesParsedComponents;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Reference;

class FiCasualTimeParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the Finnish casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:tänä\s*)?(?<time>aamulla|aamuna|aamupäivällä|päivällä|iltapäivällä|illalla|yöllä|keskiyöllä)(?=\W|$)';
    }

    /**
     * Extract Finnish casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $components = $this->timeComponents(new ParsedComponents($reference->date), mb_strtolower($match['time'][0]), str_contains(mb_strtolower($match[0][0]), 'tänä'));
        $components->addTag('parser/FICasualTimeParser');

        return new ParsedResult($match[0][1], trim($match[0][0]), $components);
    }

    /**
     * Get the Unicode-aware left boundary used by Finnish casual time parsing.
     */
    protected function patternLeftBoundary(): string
    {
        return '((?<![\pL\pN]))';
    }

    /**
     * Apply a Finnish casual time keyword to existing parsed components.
     */
    public function timeComponents(ParsedComponents $components, string $time, bool $sameDay = false): ParsedComponents
    {
        $date = $components->date();
        $hour = match ($time) {
            'aamulla', 'aamuna' => 6,
            'aamupäivällä' => 9,
            'päivällä' => 12,
            'iltapäivällä' => 15,
            'illalla' => 18,
            'yöllä' => 22,
            'keskiyöllä' => 0,
        };

        if ($time === 'keskiyöllä' && ! $sameDay && ! $components->isCertain('day') && $date->hour > 1) {
            $date = $date->addDay();
        }

        $date = $date->hour($hour)->minute(0)->second(0)->millisecond(0);

        return $this->components($date, [
            ...($time === 'keskiyöllä' ? ['year' => $date->year, 'month' => $date->month, 'day' => $date->day] : []),
            'hour' => $hour,
            'minute' => 0,
            'second' => 0,
            'meridiem' => $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value,
        ]);
    }
}
