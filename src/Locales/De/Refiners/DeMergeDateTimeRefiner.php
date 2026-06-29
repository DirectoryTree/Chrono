<?php

namespace Chrono\Locales\De\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class DeMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the connector pattern allowed between parsed results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|um|am|,|-)?\s*$/iu';
    }
}
