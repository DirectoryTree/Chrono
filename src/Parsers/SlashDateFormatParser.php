<?php

namespace Chrono\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Calculation\Years;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class SlashDateFormatParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Create a slash date parser.
     */
    public function __construct(
        protected readonly bool $littleEndian = false,

        protected readonly bool $forwardDateByDefault = false,

        protected readonly bool $includeYearWhenForwardDate = false,

        protected readonly int $twoDigitYearPastThreshold = 50,
    ) {}

    /**
     * Parse numeric slash, dash, and dotted date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\d.\/-])(?<leadingslash>\/)?(?<first>\d{1,2})[\/.-](?<second>\d{1,2})(?:[\/.-](?<year>\d{2,4}))?(?:(?:\s+(?:at\s+)?|[.:-])(?<hour>\d{1,2})(?:(?::(?<minute>\d{2}))|\s*(?<meridiem>am|pm)))?\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference, $options): ?ParsedResult {
            $previous = $match[0][1] > 0 ? $text[$match[0][1] - 1] : '';
            $after = substr($text, $match[0][1] + strlen($match[0][0]));
            $next = $after[0] ?? '';

            if (($match['leadingslash'][0] ?? '') === '' && $previous !== '' && preg_match('/[\d.\/-]/', $previous) === 1) {
                return null;
            }

            if (in_array($next, ['/', '.', '-'], true)
                && preg_match('/^[\/.-]\d/', $after) === 1
                && ! $this->isFollowedBySeparatedTime($text, $match)) {
                return null;
            }

            $first = (int) $match['first'][0];
            $second = (int) $match['second'][0];
            $month = $this->littleEndian ? $second : $first;
            $day = $this->littleEndian ? $first : $second;
            $yearText = $match['year'][0] ?? '';
            $dateText = $match[0][0];

            if ($month > 12 && $month <= 31 && $day >= 1 && $day <= 12) {
                [$month, $day] = [$day, $month];
            }

            if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return null;
            }

            if ($this->looksLikeVersionNumber($dateText, $yearText)) {
                return null;
            }

            $year = $this->year($yearText, $reference, $day, $month);
            $hour = isset($match['hour']) && $match['hour'][0] !== ''
                ? $this->meridiemHour((int) $match['hour'][0], ($match['meridiem'][0] ?? '') ?: null)
                : 12;
            $minute = isset($match['minute']) && $match['minute'][0] !== '' ? (int) $match['minute'][0] : 0;

            if (! checkdate($month, $day, $year)) {
                return null;
            }

            $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, 0, $reference->date->timezone);
            $shouldForwardDate = $this->forwardDateByDefault || $options->forwardDate();

            if ($yearText === '' && $shouldForwardDate && $date->lt($reference->date)) {
                $date = $date->addYear();
                $year = $date->year;
            }

            return (new ParsedResult($match[0][1], $match[0][0], $this->components($date, [
                ...($yearText !== '' || ($this->includeYearWhenForwardDate && $shouldForwardDate) ? ['year' => $year] : []),
                'month' => $month,
                'day' => $day,
                ...(($match['hour'][0] ?? '') !== '' ? ['hour' => $hour, 'minute' => $minute] : []),
            ])))->addTag('parser/SlashDateFormatParser');
        }, $matches)));
    }

    /**
     * Resolve the year value.
     */
    protected function year(string $year, Reference $reference, int $day, int $month): int
    {
        if ($year === '') {
            return $this->closestYear($reference, $day, $month);
        }

        $year = (int) $year;

        if ($this->twoDigitYearPastThreshold === 50) {
            return Years::findMostLikelyADYear($year);
        }

        if ($year < 100 && $year > $this->twoDigitYearPastThreshold) {
            return 1900 + $year;
        }

        return $year < 100 ? 2000 + $year : $year;
    }

    /**
     * Find the year that places the month and day closest to the reference date.
     */
    protected function closestYear(Reference $reference, int $day, int $month): int
    {
        return Years::findYearClosestToReference($reference->date, $day, $month);
    }

    /**
     * Determine whether the date is followed by a separated time.
     */
    protected function isFollowedBySeparatedTime(string $text, array $match): bool
    {
        $after = substr($text, $match[0][1] + strlen($match[0][0]));

        return preg_match('/^[.:-]\d{1,2}:\d{2}(?::\d{2})?\b/', $after) === 1;
    }

    /**
     * Determine whether the text looks like a version number.
     */
    protected function looksLikeVersionNumber(string $dateText, string $yearText): bool
    {
        if ($yearText === '' && (str_contains($dateText, '.') || str_contains($dateText, '-'))) {
            return true;
        }

        if (strlen($yearText) === 1) {
            return true;
        }

        return str_contains($dateText, '.') && strlen($yearText) < 4;
    }
}
