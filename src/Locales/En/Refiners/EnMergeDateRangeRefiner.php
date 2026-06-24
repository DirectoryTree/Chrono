<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class EnMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    use InteractsWithEnglishRefiners;

    protected function patternBetween(): string
    {
        return '/^\s*(?:-|to|until|through|thru|till)\s*$/i';
    }
}
