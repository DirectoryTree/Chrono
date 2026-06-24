<?php

namespace Chrono\Locales\Fr\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class FrMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the French connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:à|a|au|-)\s*$/iu';
    }
}
