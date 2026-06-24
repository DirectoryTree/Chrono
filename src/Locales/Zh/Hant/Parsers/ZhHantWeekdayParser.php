<?php

namespace Chrono\Locales\Zh\Hant\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhWeekdayParser;

class ZhHantWeekdayParser extends AbstractZhWeekdayParser
{
    /**
     * Get the parser pattern.
     */
    protected function pattern(): string
    {
        return '/(?<![A-Za-z0-9_])(?:星期|禮拜|週)(?<weekday>天|日|一|二|三|四|五|六)/u';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHantWeekdayParser';
    }
}
