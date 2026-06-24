<?php

namespace Chrono\Locales\It;

use Carbon\CarbonImmutable;
use Chrono\ParsedComponents;

trait CreatesParsedComponents
{
    /**
     * @param  array<string, int>  $known
     */
    protected function components(CarbonImmutable $date, array $known): ParsedComponents
    {
        $components = new ParsedComponents($date, []);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        return $components;
    }

    /**
     * Convert a 12-hour clock hour into a 24-hour clock hour.
     */
    protected function meridiemHour(int $hour, ?string $meridiem): int
    {
        if ($meridiem === null) {
            return $hour;
        }

        return strtolower($meridiem) === 'am'
            ? ($hour === 12 ? 0 : $hour)
            : ($hour === 12 ? 12 : $hour + 12);
    }
}
