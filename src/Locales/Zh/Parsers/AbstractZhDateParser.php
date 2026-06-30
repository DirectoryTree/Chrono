<?php

namespace DirectoryTree\Chrono\Locales\Zh\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Locales\Zh\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\Zh\ZhConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

abstract readonly class AbstractZhDateParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @return array<string, int>
     */
    abstract protected function numbers(): array;

    abstract protected function daySuffix(): string;

    abstract protected function tag(): string;

    /**
     * Parse Chinese month/day date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $numberChars = preg_quote(implode('', array_keys($this->numbers())), '/');
        $daySuffix = $this->daySuffix();

        preg_match_all("/(?<![A-Za-z0-9_])(?<year>\\d{2,4}|[{$numberChars}]{2,4})?\\s*(?:年)?[\\s,，]*(?<month>\\d{1,2}|[{$numberChars}]{1,3})\\s*月\\s*(?<day>\\d{1,2}|[{$numberChars}]{1,3})?\\s*(?:{$daySuffix})?/u", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = ZhConstants::number($match['month'][0], $this->numbers());
            $dayText = $match['day'][0] ?? '';
            $day = $dayText !== '' ? ZhConstants::number($dayText, $this->numbers()) : $reference->date->day;
            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? ZhConstants::year($yearText, $this->numbers()) : $reference->date->year;

            if ($month < 1 || $month > 12 || $day < 1 || $day > 31 || ! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], trim($match[0][0]), $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                ...($dayText !== '' ? ['day' => $day] : []),
            ])->addTag($this->tag()));
        }, $matches)));
    }
}
