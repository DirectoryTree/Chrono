<?php

namespace Chrono\Locales\It\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

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
