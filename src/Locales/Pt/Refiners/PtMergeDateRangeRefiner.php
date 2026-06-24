<?php

namespace Chrono\Locales\Pt\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class PtMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Portuguese connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*-\s*$/iu';
    }
}
