<?php

namespace DirectoryTree\Chrono\Locales\Ru\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class RuMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Russian connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|в|,|-)?\s*$/iu';
    }
}
