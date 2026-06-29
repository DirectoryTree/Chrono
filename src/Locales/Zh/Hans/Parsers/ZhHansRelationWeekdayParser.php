<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

readonly class ZhHansRelationWeekdayParser extends ZhHansWeekdayParser
{
    /**
     * Get the parser pattern.
     */
    protected function pattern(): string
    {
        return '/(?<![A-Za-z0-9_])(?<prefix>上|下|这)(?:个)?(?:星期|礼拜|周)(?<weekday>天|日|一|二|三|四|五|六)/u';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHansRelationWeekdayParser';
    }
}
