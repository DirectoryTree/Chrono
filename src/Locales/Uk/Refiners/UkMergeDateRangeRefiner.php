<?php

namespace Chrono\Locales\Uk\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class UkMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Ukrainian connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:і\s+до|і\s+по|до|по|-)\s*$/iu';
    }
}
