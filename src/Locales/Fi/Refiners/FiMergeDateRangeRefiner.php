<?php

namespace DirectoryTree\Chrono\Locales\Fi\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class FiMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Finnish connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:-|–)\s*$/iu';
    }
}
