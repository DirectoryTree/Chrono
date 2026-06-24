<?php

namespace Chrono\Locales\De\Refiners;

use Chrono\Refiners\AbstractMergeDateRangeRefiner;

class DeMergeDateRangeRefiner extends AbstractMergeDateRangeRefiner
{
    protected function patternBetween(): string
    {
        return '/^\s*(?:bis(?:\s*(?:am|zum))?|-)\s*$/iu';
    }
}
