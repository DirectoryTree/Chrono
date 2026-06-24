<?php

namespace Chrono\Locales\De\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

class DeMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    protected function patternBetween(): string
    {
        return '/^\s*(?:T|um|am|,|-)?\s*$/iu';
    }

}
