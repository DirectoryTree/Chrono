<?php

namespace Chrono\Locales\Zh\Hant\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhDeadlineFormatParser;
use Chrono\Locales\Zh\ZhConstants;

readonly class ZhHantDeadlineFormatParser extends AbstractZhDeadlineFormatParser
{
    /**
     * Get the localized number map.
     */
    protected function numbers(): array
    {
        return ZhConstants::HANT_NUMBERS;
    }

    /**
     * Get the parser pattern.
     */
    protected function pattern(): string
    {
        return '/(?<amount>\d+|[零一二兩三四五六七八九十廿卅]+|半|幾)\s*(?:個)?(?<unit>秒(?:鐘)?|分鐘|小時|鐘|日|天|星期|禮拜|月|年)(?:(?:之|過)?後|(?:之)?內)/u';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHantDeadlineFormatParser';
    }
}
