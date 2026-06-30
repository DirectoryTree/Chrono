<?php

namespace DirectoryTree\Chrono\Locales\Pt\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class PtMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Portuguese connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*-\s*$/iu';
    }
}
