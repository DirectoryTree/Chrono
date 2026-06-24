<?php

namespace Chrono\Locales\Zh\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class ZhMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    protected function patternBetween(): string
    {
        return '/^\s*(?:至|到|-|~|～|－|ー)\s*$/u';
    }
}
