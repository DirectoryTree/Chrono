<?php

namespace Chrono\Locales\Fi\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

class FiMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Finnish connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|klo|kello|,|-)?\s*$/iu';
    }
}
