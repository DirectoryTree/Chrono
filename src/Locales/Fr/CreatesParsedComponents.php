<?php

namespace Chrono\Locales\Fr;

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
}
