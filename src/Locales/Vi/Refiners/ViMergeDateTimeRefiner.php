<?php

namespace Chrono\Locales\Vi\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

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
