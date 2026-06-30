<?php

namespace DirectoryTree\Chrono\Locales\De\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateTimeRefiner;

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
