<?php

namespace DirectoryTree\Chrono\Locales\It\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class ItMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Italian connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:to|-)\s*$/iu';
    }
}
