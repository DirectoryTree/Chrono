<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

readonly class EnMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    use InteractsWithEnglishRefiners;

    /**
     * Get the connector pattern allowed between parsed results.
     */
    protected function patternBetween(): string
    {
        return '/^\s*(?:-|to|until|through|thru|till)\s*$/i';
    }
}
