<?php

namespace Chrono\Locales\Es\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class EsMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Spanish connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*-\s*$/iu';
    }
}
