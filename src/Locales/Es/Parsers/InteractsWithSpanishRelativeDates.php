<?php

namespace Chrono\Locales\Es\Parsers;

use Carbon\CarbonImmutable;
use Chrono\ParsedComponents;

trait InteractsWithSpanishRelativeDates
{
    /**
     * @var array<string, int>
     */
    protected array $integers = [
        'un' => 1, 'una' => 1, 'uno' => 1,
        'dos' => 2, 'tres' => 3, 'cuatro' => 4, 'cinco' => 5,
        'seis' => 6, 'siete' => 7, 'ocho' => 8, 'nueve' => 9,
        'diez' => 10, 'once' => 11, 'doce' => 12, 'trece' => 13,
        'algunos' => 3, 'algunas' => 3, 'unos' => 3, 'unas' => 3,
    ];

    /**
     * @var array<string, string>
     */
    protected array $timeUnits = [
        'sec' => 'second', 'segundo' => 'second', 'segundos' => 'second',
        'min' => 'minute', 'mins' => 'minute', 'minuto' => 'minute', 'minutos' => 'minute',
        'h' => 'hour', 'hr' => 'hour', 'hrs' => 'hour', 'hora' => 'hour', 'horas' => 'hour',
        'dia' => 'day', 'dias' => 'day', 'día' => 'day', 'días' => 'day',
        'semana' => 'week', 'semanas' => 'week',
        'mes' => 'month', 'meses' => 'month',
        'cuarto' => 'quarter', 'cuartos' => 'quarter',
        'ano' => 'year', 'anos' => 'year', 'año' => 'year', 'años' => 'year',
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
     * Parse a Spanish time-unit phrase into duration fragments.
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

        return "{$single}(?:\\s*(?:,?\\s*(?:y)|,)?\\s*{$single})*";
    }

    /**
     * Get the parser pattern.
     */
    protected function numberPattern(): string
    {
        return '(?:'.$this->alternation(array_keys($this->integers)).'|[0-9]+|[0-9]+\.[0-9]+|demi-?)';
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

        if (preg_match('/demi/iu', $number) === 1) {
            return 0.5;
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

    /**
     * Normalize the value.
     */
    protected function normalize(string $value): string
    {
        return strtr(strtolower($value), [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            'Ñ' => 'n',
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
