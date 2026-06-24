<?php

namespace Chrono\Locales\Vi\Refiners;

use Chrono\ParsedResult;
use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class ViMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the connector pattern allowed between Vietnamese date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:–|-|đến|tới|và)\s*$/iu';
    }

    /**
     * Determine if a parsed result can be used as a Vietnamese date range endpoint.
     */
    protected function isRangeDate(ParsedResult $result): bool
    {
        return parent::isRangeDate($result)
            || $result->start->isCertain('month')
            || $result->start->isCertain('year');
    }
}
