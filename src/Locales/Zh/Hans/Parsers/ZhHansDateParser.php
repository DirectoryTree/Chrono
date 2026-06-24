<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhDateParser;
use Chrono\Locales\Zh\ZhConstants;

class ZhHansDateParser extends AbstractZhDateParser
{
    /**
     * Get the localized number map.
     */
    protected function numbers(): array
    {
        return ZhConstants::HANS_NUMBERS;
    }

    /**
     * Get the localized day suffix pattern.
     */
    protected function daySuffix(): string
    {
        return '日|号';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHansDateParser';
    }
}
