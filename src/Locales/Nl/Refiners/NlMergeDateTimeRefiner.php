<?php

namespace Chrono\Locales\Nl\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

class NlMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Dutch connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:om|na|voor|in de|,|-)?\s*$/iu';
    }
}
