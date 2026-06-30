<?php

namespace DirectoryTree\Chrono\Locales\Uk\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class UkMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Ukrainian connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:і\s+до|і\s+по|до|по|-)\s*$/iu';
    }
}
