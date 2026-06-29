<?php

namespace Chrono\Locales\Zh\Hant\Parsers;

readonly class ZhHantRelationWeekdayParser extends ZhHantWeekdayParser
{
    /**
     * Get the parser pattern.
     */
    protected function pattern(): string
    {
        return '/(?<![A-Za-z0-9_])(?<prefix>上|今|下|這|呢)(?:個)?(?:星期|禮拜|週)(?<weekday>天|日|一|二|三|四|五|六)/u';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHantRelationWeekdayParser';
    }
}
