<?php

namespace Chrono\Locales\It\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class ItMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Italian connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|alle|dopo|prima|il|di|del|delle|,|-)?\s*$/iu';
    }
}
