<?php

namespace Chrono\Locales\Ru\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class RuMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Russian connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:и\s+до|и\s+по|до|по|-|–)\s*$/iu';
    }
}
