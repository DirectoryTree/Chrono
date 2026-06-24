<?php

namespace Chrono\Locales\Zh\Hant\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhCasualDateParser;

class ZhHantCasualDateParser extends AbstractZhCasualDateParser
{
    /**
     * Get the parser pattern.
     */
    protected function pattern(): string
    {
        return '/(?<now>而家|立(?:刻|即)|即刻)|(?<day1>今|明|前|大前|後|大後|聽|昨|尋|琴)(?<time1>早|朝|晚)|(?<time2>上午|上晝|朝早|早上|下午|下晝|晏晝|晚上|夜晚?|中午|凌晨)|(?<day3>今|明|前|大前|後|大後|聽|昨|尋|琴)(?:日|天)[\s,，]*(?<time3>上午|上晝|朝早|早上|下午|下晝|晏晝|晚上|夜晚?|中午|凌晨)?/u';
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
     * Resolve the hour value.
     */
    protected function timeHour(string $time): ?int
    {
        return match (mb_substr($time, 0, 1)) {
            '早', '朝', '上' => 6,
            '下', '晏' => 15,
            '中' => 12,
            '夜', '晚' => 22,
            '凌' => 0,
            default => null,
        };
    }

    /**
     * Get the parser tag.
     */
    protected function tag(): string { return 'parser/ZHHantCasualDateParser'; }
}
