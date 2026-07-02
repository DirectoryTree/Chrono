<?php

namespace DirectoryTree\Chrono\Locales\De;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\ParsedComponents;

trait CreatesParsedComponents
{
    /**
     * Create parsed date components.
     *
     * @param  array<string, int>  $known
     */
    protected function components(CarbonImmutable $date, array $known): ParsedComponents
    {
        $components = new ParsedComponents($date->second($date->second)->millisecond($date->millisecond), []);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        return $components;
    }
}
