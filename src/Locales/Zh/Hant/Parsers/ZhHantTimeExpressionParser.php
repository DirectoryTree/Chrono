<?php

namespace Chrono\Locales\Zh\Hant\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhTimeExpressionParser;
use Chrono\Locales\Zh\ZhConstants;

readonly class ZhHantTimeExpressionParser extends AbstractZhTimeExpressionParser
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
    protected function timeWordPattern(): string
    {
        return '上午|上晝|朝早|早上|下午|下晝|晏晝|晚上|夜晚?|中午|凌晨';
    }

    /**
     * Get the localized time separator character.
     */
    protected function pointCharacter(): string
    {
        return '點';
    }

    /**
     * Get the parser pattern.
     */
    protected function dayWordPattern(): string
    {
        return '今|明|前|大前|後|大後|聽|昨|尋|琴';
    }

    /**
     * Normalize the value.
     */
    protected function normalizeDay(string $day): string
    {
        return match ($day) {
            '聽' => '明',
            '尋', '琴' => '昨',
            default => $day,
        };
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string
    {
        return 'parser/ZHHantTimeExpressionParser';
    }
}
