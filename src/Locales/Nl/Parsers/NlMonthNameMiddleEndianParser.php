<?php

namespace DirectoryTree\Chrono\Locales\Nl\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;
use DirectoryTree\Chrono\Locales\Nl\CreatesParsedComponents;
use DirectoryTree\Chrono\Locales\Nl\NlConstants;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class NlMonthNameMiddleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Dutch day-month dates and same-month ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $ordinalPattern = NlConstants::ordinalPattern();
        $monthPattern = NlConstants::monthPattern();
        $yearPattern = NlConstants::yearPattern();

        preg_match_all(
            "/(?<![\\pL\\pN])(?:on\\s*?)?(?<day>{$ordinalPattern})(?:\\s*(?:tot|-|–|until|through|till|\\s)\\s*(?<endday>{$ordinalPattern}))?(?:-|\\/|\\s*(?:of)?\\s*)(?<month>{$monthPattern})(?:(?:-|\\/|,?\\s*)(?<year>{$yearPattern}(?![^\\s]\\d)))?(?=\\W|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = NlConstants::monthNumber($match['month'][0]);
            $day = NlConstants::ordinalNumber($match['day'][0]);

            if ($month === null || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? NlConstants::year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if (! checkdate($month, $day, max(1, abs($year)))) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ]);
            $components->addTag('parser/NLMonthNameMiddleEndianParser');

            return new ParsedResult($match[0][1], $match[0][0], $components, $end);
        }, $matches)));
    }

    /**
     * Build end components for same-month ranges.
     */
    protected function endComponents(array $match, CarbonImmutable $start, int $month): ?ParsedComponents
    {
        $endDayText = $match['endday'][0] ?? '';

        if ($endDayText === '') {
            return null;
        }

        $endDay = NlConstants::ordinalNumber($endDayText);

        if (! checkdate($month, $endDay, $start->year)) {
            return null;
        }

        return $this->components($start->day($endDay), [
            'month' => $month,
            'day' => $endDay,
        ]);
    }
}
