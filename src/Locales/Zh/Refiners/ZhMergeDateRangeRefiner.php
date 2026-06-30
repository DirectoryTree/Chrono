<?php

namespace DirectoryTree\Chrono\Locales\Zh\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class ZhMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the connector pattern allowed between parsed results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:至|到|-|~|～|－|ー)\s*$/u';
    }
}
