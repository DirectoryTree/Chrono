<?php

namespace Chrono\Locales\De\Parsers;

use Carbon\CarbonImmutable;
use Chrono\ParsedComponents;

trait InteractsWithGermanRelativeDates
{
    /**
     * @var array<string, int>
     */
    protected array $integers = [
        'eins' => 1, 'ein' => 1, 'eine' => 1, 'einem' => 1, 'einen' => 1, 'einer' => 1,
        'zwei' => 2, 'drei' => 3, 'vier' => 4, 'fünf' => 5, 'fuenf' => 5, 'funf' => 5,
        'sechs' => 6, 'sieben' => 7, 'acht' => 8, 'neun' => 9, 'zehn' => 10,
        'elf' => 11, 'zwölf' => 12, 'zwoelf' => 12, 'zwolf' => 12,
    ];

    /**
     * @var array<string, string>
     */
    protected array $timeUnits = [
        'sek' => 'second', 'sekunde' => 'second', 'sekunden' => 'second',
        'min' => 'minute', 'minute' => 'minute', 'minuten' => 'minute',
        'h' => 'hour', 'std' => 'hour', 'stunde' => 'hour', 'stunden' => 'hour',
        'tag' => 'day', 'tage' => 'day', 'tagen' => 'day',
        'woche' => 'week', 'wochen' => 'week',
        'monat' => 'month', 'monate' => 'month', 'monaten' => 'month', 'monats' => 'month',
        'quartal' => 'quarter', 'quartals' => 'quarter', 'quartale' => 'quarter', 'quartalen' => 'quarter',
        'a' => 'year', 'j' => 'year', 'jr' => 'year', 'jahr' => 'year', 'jahre' => 'year', 'jahren' => 'year', 'jahres' => 'year',
    ];

    /**
     * @param  array<string, int>  $known
     */
    protected function relativeComponents(CarbonImmutable $date, array $known): ParsedComponents
    {
        $components = new ParsedComponents($date, []);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        return $components;
    }

    /**
     * @return array<string, int>
     */
    protected function certainComponents(CarbonImmutable $date, string $unit): array
    {
        return match ($unit) {
            'second' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, 'hour' => $date->hour, 'minute' => $date->minute, 'second' => $date->second],
            'minute' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, 'hour' => $date->hour, 'minute' => $date->minute],
            'hour' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day, 'hour' => $date->hour],
            'day', 'week' => ['year' => $date->year, 'month' => $date->month, 'day' => $date->day],
            'month', 'quarter' => ['year' => $date->year, 'month' => $date->month],
            'year' => ['year' => $date->year],
            default => [],
        };
    }

    /**
     * @return array<string, int>
     */
    protected function casualCertainComponents(CarbonImmutable $date, string $unit): array
    {
        if (in_array($unit, ['quarter', 'year'], true)) {
            return ['year' => $date->year];
        }

        return $this->certainComponents($date, $unit);
    }

    /**
     * @param  array<string, int|float>  $duration
     */
    protected function applyDuration(CarbonImmutable $date, array $duration, int $direction): CarbonImmutable
    {
        foreach ($duration as $unit => $amount) {
            if ($unit === 'quarter') {
                $unit = 'month';
                $amount *= 3;
            }

            $date = $date->add($unit, (int) round($amount * $direction));
        }

        return $date;
    }

    /**
     * Parse a German time-unit phrase into duration fragments.
     *
     * @return array<string, int|float>
     */
    protected function duration(string $duration): array
    {
        $numberPattern = $this->numberPattern();
        $unitPattern = $this->timeUnitPattern();

        preg_match_all("/(?<number>{$numberPattern})\\s{0,5}(?<unit>{$unitPattern})\\s{0,5}/iu", $duration, $matches, PREG_SET_ORDER);

        $units = [];

        foreach ($matches as $match) {
            $unit = $this->timeUnit($match['unit']);

            if ($unit === null) {
                continue;
            }

            $units[$unit] = ($units[$unit] ?? 0) + $this->number($match['number']);
        }

        return $units;
    }

    /**
     * Get the parser pattern.
     */
    protected function durationPattern(): string
    {
        $numberPattern = $this->numberPattern();
        $unitPattern = $this->timeUnitPattern();
        $single = "(?:{$numberPattern})\\s{0,5}(?:{$unitPattern})\\s{0,5}";

        return "{$single}(?:\\s*(?:,?\\s*(?:und)|,)?\\s*{$single})*";
    }

    /**
     * Get the parser pattern.
     */
    protected function numberPattern(): string
    {
        return '(?:'.$this->alternation(array_keys($this->integers)).'|[0-9]+|[0-9]+\.[0-9]+|halb?|halbe?|einigen?|wenigen?|mehreren?)';
    }

    /**
     * Resolve the number value.
     */
    protected function number(string $number): float
    {
        $number = $this->normalize($number);

        if (isset($this->integers[$number])) {
            return $this->integers[$number];
        }

        if (preg_match('/wenigen/iu', $number) === 1) {
            return 2;
        }

        if (preg_match('/halb/iu', $number) === 1) {
            return 0.5;
        }

        if (preg_match('/einigen/iu', $number) === 1) {
            return 3;
        }

        if (preg_match('/mehreren/iu', $number) === 1) {
            return 7;
        }

        return (float) $number;
    }

    /**
     * Get the parser pattern.
     */
    protected function timeUnitPattern(): string
    {
        return $this->alternation(array_keys($this->timeUnits));
    }

    /**
     * Resolve the time unit value.
     */
    protected function timeUnit(string $unit): ?string
    {
        return $this->timeUnits[$this->normalize($unit)] ?? null;
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return strtr(strtolower($value), [
            'ä' => 'a',
            'ö' => 'o',
            'ü' => 'u',
            'ß' => 'ss',
        ]);
    }

    /**
     * @param  array<int, string>  $words
     */
    protected function alternation(array $words): string
    {
        usort($words, fn (string $left, string $right): int => strlen($right) <=> strlen($left));

        return implode('|', array_map(fn (string $word): string => preg_quote($word, '/'), $words));
    }
}
