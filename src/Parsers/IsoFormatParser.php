<?php

namespace DirectoryTree\Chrono\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Locales\En\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Reference;

class IsoFormatParser extends AbstractParserWithWordBoundary
{
    use CreatesParsedComponents;

    /**
     * Get the upstream ISO 8601 parser pattern.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?<year>\d{4})-(?<month>\d{1,2})-(?<day>\d{1,2})'.
            '(?:T(?<hour>\d{1,2}):(?<minute>\d{1,2})'.
            '(?::(?<second>\d{1,2})(?:\.(?<millisecond>\d{1,4}))?)?'.
            '(?<timezone>Z|(?<timezoneHourOffset>[+-]\d{2}):?(?<timezoneMinuteOffset>\d{2})?)?)?'.
            '(?=\W|$)';
    }

    /**
     * Extract ISO date and optional time components from the matched text.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
    {
        $year = (int) $match['year'][0];
        $month = (int) $match['month'][0];
        $day = (int) $match['day'][0];

        if (! checkdate($month, $day, $year)) {
            return null;
        }

        $hour = $this->matched($match, 'hour') ? (int) $match['hour'][0] : 12;
        $minute = $this->matched($match, 'minute') ? (int) $match['minute'][0] : 0;
        $second = $this->matched($match, 'second') ? (int) $match['second'][0] : 0;
        $millisecond = $this->matched($match, 'millisecond') ? (int) $match['millisecond'][0] : 0;

        $date = CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $reference->date->timezone)
            ->millisecond($millisecond);

        $components = $this->components($date, [
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ]);

        if ($this->matched($match, 'hour')) {
            $components->assign('hour', $hour);
            $components->assign('minute', $minute);

            if ($this->matched($match, 'second')) {
                $components->assign('second', $second);
            }

            if ($this->matched($match, 'millisecond')) {
                $components->assign('millisecond', $millisecond);
            }

            if (($timezone = $this->timezoneOffset($match)) !== null) {
                $components->assign('timezoneOffset', $timezone);
            }
        }

        return $components->addTag('parser/ISOFormatParser');
    }

    /**
     * Resolve an ISO timezone designator to a minute offset.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function timezoneOffset(array $match): ?int
    {
        if (! $this->matched($match, 'timezone')) {
            return null;
        }

        if (strtoupper($match['timezone'][0]) === 'Z') {
            return 0;
        }

        $hourOffset = (int) $match['timezoneHourOffset'][0];
        $minuteOffset = $this->matched($match, 'timezoneMinuteOffset')
            ? (int) $match['timezoneMinuteOffset'][0]
            : 0;

        $offset = $hourOffset * 60;

        return $offset < 0
            ? $offset - $minuteOffset
            : $offset + $minuteOffset;
    }

    /**
     * Determine whether a named capture was matched.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function matched(array $match, string $group): bool
    {
        return isset($match[$group]) && $match[$group][0] !== '';
    }
}
