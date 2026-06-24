<?php

namespace Chrono\Locales\En\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Locales\En\EnConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class EnMonthNameParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * @var array<string, bool>
     */
    public const FULL_MONTHS = [
        'january' => true,
        'february' => true,
        'march' => true,
        'april' => true,
        'may' => true,
        'june' => true,
        'july' => true,
        'august' => true,
        'september' => true,
        'october' => true,
        'november' => true,
        'december' => true,
    ];

    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        return [
            ...$this->parseMonthYear($text, $reference),
            ...$this->parseMonthOnly($text, $reference, $options),
        ];
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMonthYear(string $text, Reference $reference): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<month>'.$monthPattern.')\.?(?:(?:\s+of\s+)|(?:[-]\s*)|(?:,?\s+))(?<year>\d{4}|[5-9]\d|2[0-5])\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference): ?ParsedResult {
            $yearText = $match['year'][0];

            if (strlen($yearText) === 2 && $this->isFollowedByRange($text, $match)) {
                return null;
            }

            if (strlen($yearText) <= 2 && $this->isFollowedByYear($text, $match)) {
                return null;
            }

            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $year = $this->year((int) $yearText);
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
                'year' => $year,
                'month' => $month,
            ])->addTag('parser/ENMonthNameParser'));
        }, $matches)));
    }

    /**
     * Determine whether the match is followed by a range.
     */
    protected function isFollowedByRange(string $text, array $match): bool
    {
        $after = substr($text, $match[0][1] + strlen($match[0][0]));

        return preg_match('/^\s*(?:-|to|through)\s*\d{1,2}/i', $after) === 1;
    }

    /**
     * Determine whether a short numeric token belongs to a rejected month-day-year date.
     */
    protected function isFollowedByYear(string $text, array $match): bool
    {
        $after = substr($text, $match[0][1] + strlen($match[0][0]));

        return preg_match('/^\s*,\s*\d{4}\b/', $after) === 1;
    }

    /**
     * @return array<int, ParsedResult>
     */
    protected function parseMonthOnly(string $text, Reference $reference, Options $options): array
    {
        $monthPattern = EnConstants::monthPattern();

        preg_match_all('/\b(?<month>'.$monthPattern.')\.?(?:,?\s+(?<year>\d{4}))?(?=\W|$)/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference, $options): ?ParsedResult {
            if (strtolower($match['month'][0]) === 'may' && $this->isModalMay($text, $match)) {
                return null;
            }

            if (($match['year'][0] ?? '') === '' && $this->isFollowedByNumericToken($text, $match)) {
                return null;
            }

            if (($match['year'][0] ?? '') === '' && $this->isUnlikelyBareAbbreviation($text, $match)) {
                return null;
            }

            $month = EnConstants::MONTHS[strtolower($match['month'][0])];
            $year = ($match['year'][0] ?? '') !== ''
                ? (int) $match['year'][0]
                : $this->impliedYear($reference, $options, $month);
            $date = CarbonImmutable::create($year, $month, 1, 12, 0, 0, $reference->date->timezone);

            return new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
                ...(($match['year'][0] ?? '') !== '' ? ['year' => $year] : []),
                'month' => $month,
            ])->addTag('parser/ENMonthNameParser'));
        }, $matches)));
    }

    /**
     * Determine whether the match is an unlikely bare abbreviation.
     */
    protected function isUnlikelyBareAbbreviation(string $text, array $match): bool
    {
        $month = strtolower($match['month'][0]);

        if (isset(self::FULL_MONTHS[$month]) || strlen($month) > 3) {
            return false;
        }

        $before = substr($text, 0, $match[0][1]);

        return preg_match('/\bin\s+$/i', $before) !== 1;
    }

    /**
     * Resolve the year value.
     */
    protected function impliedYear(Reference $reference, Options $options, int $month): int
    {
        if (! $options->forwardDate()) {
            return Years::findYearClosestToReference($reference->date, 1, $month);
        }

        return $month < $reference->date->month
            ? $reference->date->year + 1
            : $reference->date->year;
    }

    /**
     * Determine whether the match is the modal word may.
     */
    protected function isModalMay(string $text, array $match): bool
    {
        $after = substr($text, $match[0][1] + strlen($match[0][0]));

        return preg_match('/^\s+not\b/i', $after) === 1;
    }

    /**
     * Determine whether the match is followed by a numeric token.
     */
    protected function isFollowedByNumericToken(string $text, array $match): bool
    {
        $after = substr($text, $match[0][1] + strlen($match[0][0]));

        return preg_match('/^\s+\d/', $after) === 1;
    }

    /**
     * Resolve the year value.
     */
    protected function year(int $year): int
    {
        if ($year < 100) {
            return $year > 50 ? $year + 1900 : $year + 2000;
        }

        return $year;
    }
}
