<?php

namespace DirectoryTree\Chrono\Locales\Nl\Refiners;

use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class NlMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    /**
     * Get the Dutch connector pattern between date range endpoints.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:tot|-)\s*$/iu';
    }

    /**
     * Imply missing component values.
     */
    protected function implyMissingComponents(ParsedComponents $start, ParsedComponents $end): void
    {
        if (! $start->isCertain('year') && $end->isCertain('year')) {
            $start->assign('year', (int) $end->get('year'));
        }
    }
}
