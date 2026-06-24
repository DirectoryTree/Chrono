<?php

namespace Chrono\Locales\Fr\Parsers;

use Carbon\CarbonImmutable;
use Chrono\ParsedComponents;

trait InteractsWithFrenchRelativeDates
{
    /**
     * @var array<string, int>
     */
    protected array $integers = [
        'un' => 1, 'une' => 1,
        'deux' => 2, 'trois' => 3, 'quatre' => 4, 'cinq' => 5,
        'six' => 6, 'sept' => 7, 'huit' => 8, 'neuf' => 9,
        'dix' => 10, 'onze' => 11, 'douze' => 12, 'treize' => 13,
        'quelque' => 3, 'quelques' => 3,
    ];

    /**
     * @var array<string, string>
     */
    protected array $timeUnits = [
        'sec' => 'second', 'seconde' => 'second', 'secondes' => 'second',
        'min' => 'minute', 'mins' => 'minute', 'minute' => 'minute', 'minutes' => 'minute',
        'h' => 'hour', 'hr' => 'hour', 'hrs' => 'hour', 'heure' => 'hour', 'heures' => 'hour',
        'jour' => 'day', 'jours' => 'day',
        'semaine' => 'week', 'semaines' => 'week',
        'mois' => 'month',
        'trimestre' => 'quarter', 'trimestres' => 'quarter',
        'an' => 'year', 'ans' => 'year', 'annee' => 'year', 'annees' => 'year', 'année' => 'year', 'années' => 'year',
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
     * Parse a French time-unit phrase into duration fragments.
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

    protected function durationPattern(): string
    {
        $numberPattern = $this->numberPattern();
        $unitPattern = $this->timeUnitPattern();
        $single = "(?:{$numberPattern})\\s{0,5}(?:{$unitPattern})\\s{0,5}";

        return "{$single}(?:\\s*(?:,?\\s*(?:et)|,)?\\s*{$single})*";
    }

    protected function numberPattern(): string
    {
        return '(?:'.$this->alternation(array_keys($this->integers)).'|[0-9]+|[0-9]+\.[0-9]+|demi-?)';
    }

    protected function number(string $number): float
    {
        $number = $this->normalize($number);

        if (isset($this->integers[$number])) {
            return $this->integers[$number];
        }

        if (preg_match('/demi/iu', $number) === 1) {
            return 0.5;
        }

        return (float) $number;
    }

    protected function timeUnitPattern(): string
    {
        return $this->alternation(array_keys($this->timeUnits));
    }

    protected function timeUnit(string $unit): ?string
    {
        return $this->timeUnits[$this->normalize($unit)] ?? null;
    }

    /**
     * @param  array<string, int|float>  $duration
     */
    protected function mostSpecificUnit(array $duration): string
    {
        foreach (['second', 'minute', 'hour', 'day', 'week', 'month', 'quarter', 'year'] as $unit) {
            if (array_key_exists($unit, $duration)) {
                return $unit;
            }
        }

        return 'year';
    }

    protected function normalize(string $value): string
    {
        return strtr(strtolower($value), [
            'à' => 'a',
            'â' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'î' => 'i',
            'ï' => 'i',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c',
            'À' => 'a',
            'Â' => 'a',
            'É' => 'e',
            'È' => 'e',
            'Ê' => 'e',
            'Î' => 'i',
            'Ï' => 'i',
            'Ô' => 'o',
            'Ù' => 'u',
            'Û' => 'u',
            'Ç' => 'c',
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
