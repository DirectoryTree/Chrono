<?php

namespace DirectoryTree\Chrono\Locales\Vi\Refiners;

use DirectoryTree\Chrono\Refiners\AbstractMergeDateTimeRefiner;

readonly class ViMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    /**
     * Get the connector pattern allowed between parsed results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:lúc|vào|,|T|-)?\s*$/iu';
    }
}
