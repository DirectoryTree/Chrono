<?php

namespace Chrono\Locales\En;

use Carbon\CarbonImmutable;
use Chrono\ParsedComponents;

trait CreatesParsedComponents
{
    /**
     * @param  array<string, int>  $known
     */
    protected function components(CarbonImmutable $date, array $known): ParsedComponents
    {
        $components = new ParsedComponents($date->second(0)->millisecond(0), []);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        return $components;
    }

    protected function meridiemHour(int $hour, ?string $meridiem): int
    {
        if ($meridiem === null) {
            return $hour;
        }

        $meridiem = strtolower($meridiem);

        if ($meridiem === 'am') {
            return $hour === 12 ? 0 : $hour;
        }

        return $hour === 12 ? 12 : $hour + 12;
    }
}
