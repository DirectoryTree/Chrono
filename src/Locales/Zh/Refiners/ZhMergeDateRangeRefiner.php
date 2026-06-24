<?php

namespace Chrono\Locales\Zh\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class ZhMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the connector pattern allowed between parsed results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:至|到|-|~|～|－|ー)\s*$/u';
    }
}
