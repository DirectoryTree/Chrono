<?php

namespace DirectoryTree\Chrono\Locales\De\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class DeMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the connector pattern allowed between parsed results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:bis(?:\s*(?:am|zum))?|-)\s*$/iu';
    }
}
