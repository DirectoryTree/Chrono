<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhTimeExpressionParser;
use Chrono\Locales\Zh\ZhConstants;

readonly class ZhHansTimeExpressionParser extends AbstractZhTimeExpressionParser
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
    protected function timeWordPattern(): string
    {
        return '上午|早上|下午|晚上|夜晚?|中午|凌晨';
    }

    /**
     * Get the localized time separator character.
     */
    protected function pointCharacter(): string
    {
        return '点';
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHansTimeExpressionParser';
    }
}
