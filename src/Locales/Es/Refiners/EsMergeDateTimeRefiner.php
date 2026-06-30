<?php

namespace DirectoryTree\Chrono\Locales\Es\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class EsMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Spanish connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:,|de|aslas|a)?\s*$/iu';
    }
}
