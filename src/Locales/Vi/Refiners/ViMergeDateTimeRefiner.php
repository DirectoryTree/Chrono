<?php

namespace Chrono\Locales\Vi\Refiners;

use Chrono\Refiners\AbstractMergeDateTimeRefiner;

class ViMergeDateTimeRefiner extends AbstractMergeDateTimeRefiner
{
    protected function patternBetween(): string
    {
        return '/^\s*(?:lúc|vào|,|T|-)?\s*$/iu';
    }

}
