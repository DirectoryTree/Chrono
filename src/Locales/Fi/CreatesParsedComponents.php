<?php

namespace DirectoryTree\Chrono\Locales\Fi;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\ParsedComponents;

trait CreatesParsedComponents
{
    /**
     * Create parsed components with the given known values.
     *
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
}
