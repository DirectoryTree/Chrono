<?php

namespace Chrono\Locales\Zh\Parsers;

use Chrono\Locales\Zh\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

abstract class AbstractZhCasualDateParser implements Parser
{
    use CreatesParsedComponents;

    abstract protected function pattern(): string;

    abstract protected function normalizeDay(string $day): string;

    abstract protected function timeHour(string $time): ?int;

    abstract protected function tag(): string;

    /**
     * Parse Chinese casual date references.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all($this->pattern(), $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $date = $reference->date;
            $known = [];

            if (($match['now'][0] ?? '') !== '') {
                $known = ['hour' => $date->hour, 'minute' => $date->minute, 'second' => $date->second, 'millisecond' => $date->millisecond];
            }

            $day = $this->normalizeDay(($match['day1'][0] ?? '') ?: ($match['day3'][0] ?? ''));
            $time = ($match['time1'][0] ?? '') ?: (($match['time2'][0] ?? '') ?: ($match['time3'][0] ?? ''));

            $date = match ($day) {
                '明' => $date->hour > 1 ? $date->addDay() : $date,
                '昨' => $date->subDay(),
                '前' => $date->subDays(2),
                '大前' => $date->subDays(3),
                '后', '後' => $date->addDays(2),
                '大后', '大後' => $date->addDays(3),
                default => $date,
            };

            $known = ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, ...$known];
            $hour = $this->timeHour($time);

            if ($hour !== null) {
                $date = $date->hour($hour)->minute(0)->second(0)->millisecond(0);
                $known['hour'] = $hour;
                $known['minute'] = 0;
            }

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, $known)->addTag($this->tag()));
        }, $matches)));
    }
}
