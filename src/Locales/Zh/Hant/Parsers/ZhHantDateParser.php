<?php

namespace DirectoryTree\Chrono\Locales\Zh\Hant\Parsers;

use DirectoryTree\Chrono\Locales\Zh\Parsers\AbstractZhDateParser;
use DirectoryTree\Chrono\Locales\Zh\ZhConstants;

readonly class ZhHantDateParser extends AbstractZhDateParser
{
    /**
     * Get the localized number map.
     */
    protected function numbers(): array
    {
        return ZhConstants::HANT_NUMBERS;
    }

    /**
     * Get the localized day suffix pattern.
     */
    protected function daySuffix(): string
    {
        return '日|號';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHantDateParser';
    }
}
