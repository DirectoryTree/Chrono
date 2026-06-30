<?php

namespace DirectoryTree\Chrono\Locales\Fi\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class FiMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Finnish connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|klo|kello|,|-)?\s*$/iu';
    }
}
