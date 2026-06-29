<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhDeadlineFormatParser;
use Chrono\Locales\Zh\ZhConstants;

readonly class ZhHansDeadlineFormatParser extends AbstractZhDeadlineFormatParser
{
    /**
     * Get the localized number map.
     */
    protected function numbers(): array
    {
        return ZhConstants::HANS_NUMBERS;
    }

    /**
     * Get the parser pattern.
     */
    protected function pattern(): string
    {
        return '/(?<amount>\d+|[零〇一二两三四五六七八九十]+|半|几)\s*(?:个)?(?<unit>秒(?:钟)?|分钟|小时|钟|日|天|星期|礼拜|月|年)(?:(?:之|过)?后|(?:之)?内)/u';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHansDeadlineFormatParser';
    }
}
