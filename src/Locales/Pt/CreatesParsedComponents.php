<?php

namespace DirectoryTree\Chrono\Locales\Pt;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\ParsedComponents;

trait CreatesParsedComponents
{
    /**
     * @param  array<string, int>  $known
     */
    protected function components(CarbonImmutable $date, array $known = []): ParsedComponents
    {
        $components = new ParsedComponents($date);

        foreach ($known as $name => $value) {
            $components->assign($name, $value);
        }

        foreach (['hour' => 12, 'minute' => 0, 'second' => 0, 'millisecond' => 0] as $name => $value) {
            if (! array_key_exists($name, $known)) {
                $components->imply($name, $value);
            }
        }

        return $components;
    }
}
