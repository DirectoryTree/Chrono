<?php

namespace DirectoryTree\Chrono\Locales\Ja\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class JaMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the connector pattern allowed between Japanese date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:から|－|ー|-|～|~)\s*$/u';
    }
}
