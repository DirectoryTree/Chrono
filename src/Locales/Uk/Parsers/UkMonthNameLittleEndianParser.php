<?php

namespace Chrono\Locales\Uk\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\Uk\CreatesParsedComponents;
use Chrono\Locales\Uk\UkConstants;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class UkMonthNameLittleEndianParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Ukrainian day-month and day-range-month expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $dayPattern = UkConstants::ordinalPattern();
        $monthPattern = UkConstants::monthPattern();
        $yearPattern = '[1-9][0-9]{0,3}(?:\s+(?:року|рік|р\.?))?\s*(?:н\.?\s*е\.?|до\s+н\.?\s*е\.?)|[1-2][0-9]{3}(?:\s+(?:року|рік|р\.?))?|[5-9][0-9](?:\s+(?:року|рік|р\.?))?';

        preg_match_all("/(?<![\\pL\\pN])(?:з|із)?\\s*(?<day>{$dayPattern})(?:\\s{0,3}(?:по|-|–|до)?\\s{0,3}(?<endday>{$dayPattern}))?(?:-|\\/|\\s{0,3})\\s*(?<month>{$monthPattern})(?:(?:-|\\/|,?\\s{0,3})(?<year>{$yearPattern}(?![^\\s]\\d)))?(?=\\W|$)/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $month = UkConstants::MONTHS[mb_strtolower($match['month'][0])] ?? null;
            $day = UkConstants::ordinal($match['day'][0]);

            if ($month === null || $day > 31) {
                return null;
            }

            $yearText = $match['year'][0] ?? '';
            $year = $yearText !== '' ? UkConstants::year($yearText) : Years::findYearClosestToReference($reference->date, $day, $month);

            if ($year < 1 || ! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);
            $end = $this->endComponents($match, $date, $month);

            $components = $this->components($date, [
                ...($yearText !== '' ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
            ])->addTag('parser/UKMonthNameLittleEndianParser');

            [$index, $resultText] = $this->trimmedMatch($match[0][1], $match[0][0]);

            return new ParsedResult($index, $resultText, $components, $end);
        }, $matches)));
    }

    /**
     * Trim matched text while keeping the byte index aligned with the result.
     *
     * @return array{0: int, 1: string}
     */
    protected function trimmedMatch(int $index, string $text): array
    {
        if (preg_match('/^\s+/u', $text, $match) === 1) {
            $index += strlen($match[0]);
        }

        return [$index, trim($text)];
    }

    /**
     * Build end components for same-month day ranges.
     */
    protected function endComponents(array $match, CarbonImmutable $start, int $month): ?ParsedComponents
    {
        $endDayText = $match['endday'][0] ?? '';

        if ($endDayText === '') {
            return null;
        }

        $endDay = UkConstants::ordinal($endDayText);

        if (! checkdate($month, $endDay, $start->year)) {
            return null;
        }

        return $this->components($start->day($endDay), [
            'month' => $month,
            'day' => $endDay,
        ])->addTag('parser/UKMonthNameLittleEndianParser');
    }
}
