<?php

namespace Chrono\Locales\Zh\Parsers;

use Chrono\Locales\Zh\CreatesParsedComponents;
use Chrono\Locales\Zh\ZhConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

abstract readonly class AbstractZhDeadlineFormatParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<string, int>
     */
    abstract protected function numbers(): array;

    abstract protected function pattern(): string;

    abstract protected function tag(): string;

    /**
     * Parse Chinese deadline expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all($this->pattern(), $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $amount = is_numeric($match['amount'][0]) ? (float) $match['amount'][0] : ZhConstants::number($match['amount'][0], $this->numbers());

            if (($match['amount'][0] ?? '') === '几' || ($match['amount'][0] ?? '') === '幾') {
                $amount = 3;
            }

            if (($match['amount'][0] ?? '') === '半') {
                $amount = 0.5;
            }

            $unit = mb_substr($match['unit'][0], 0, 1);
            $chronoUnit = match (true) {
                in_array($unit, ['日', '天'], true) => 'day',
                in_array($unit, ['星', '礼', '禮'], true) => 'week',
                $unit === '月' => 'month',
                $unit === '年' => 'year',
                $unit === '秒' => 'second',
                $unit === '分' => 'minute',
                in_array($unit, ['小', '钟', '鐘'], true) => 'hour',
                default => null,
            };

            if ($chronoUnit === null) {
                return null;
            }

            if ($amount === 0.5 && $chronoUnit === 'hour') {
                $chronoUnit = 'minute';
                $amount = 30;
            }

            $date = $reference->date->add($chronoUnit, (int) round($amount));
            $known = in_array($chronoUnit, ['day', 'week', 'month', 'year'], true)
                ? ['year' => $date->year, 'month' => $date->month, 'day' => $date->day]
                : ['hour' => $date->hour, 'minute' => $date->minute, 'second' => $date->second];

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, $known)->addTag($this->tag()));
        }, $matches)));
    }
}
