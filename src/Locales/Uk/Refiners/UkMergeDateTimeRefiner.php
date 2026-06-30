<?php

namespace DirectoryTree\Chrono\Locales\Uk\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class UkMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the Ukrainian connector pattern between date and time results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|в|у|о|,|-)?\s*$/iu';
    }
}
