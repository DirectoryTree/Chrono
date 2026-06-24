<?php

namespace Chrono\Locales\Fi\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class FiMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Finnish connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:-|–)\s*$/iu';
    }
}
