<?php

namespace Chrono\Locales\Zh\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\Zh\ZhConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

abstract readonly class AbstractZhWeekdayParser implements Parser
{
    abstract protected function pattern(): string;

    abstract protected function tag(): string;

    /**
     * Parse Chinese weekday references.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all($this->pattern(), $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference, $options): ?ParsedResult {
            $weekday = ZhConstants::WEEKDAYS[$match['weekday'][0]] ?? null;

            if ($weekday === null) {
                return null;
            }

            $modifier = match ($match['prefix'][0] ?? '') {
                '上' => 'last',
                '下' => 'next',
                '今', '这', '這', '呢' => 'this',
                default => '',
            };

            $date = $reference->date->startOfDay()->addDays($this->daysToWeekday($reference, $weekday, $modifier, $options));
            $components = new ParsedComponents($date);
            $components->assign('weekday', $weekday);

            if (in_array($modifier, ['last', 'next'], true)) {
                $components
                    ->assign('day', $date->day)
                    ->assign('month', $date->month)
                    ->assign('year', $date->year);
            }

            $components->addTag($this->tag());

            return new ParsedResult($match[0][1], trim($match[0][0]), $components);
        }, $matches)));
    }

    /**
     * Resolve the weekday value.
     */
    protected function daysToWeekday(Reference $reference, int $weekday, string $modifier, Options $options): int
    {
        $referenceWeekday = $reference->date->dayOfWeek;

        if ($modifier === 'this') {
            return $weekday - $referenceWeekday;
        }

        if ($modifier === 'last') {
            return $weekday - 7 - $referenceWeekday;
        }

        if ($modifier === 'next') {
            return $weekday + 7 - $referenceWeekday;
        }

        if ($options->forwardDate()) {
            return Weekdays::getDaysForwardToWeekday($reference->date, $weekday);
        }

        return Weekdays::getDaysToWeekdayClosest($reference->date, $weekday);
    }
}
