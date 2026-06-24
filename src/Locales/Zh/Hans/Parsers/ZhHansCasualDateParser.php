<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhCasualDateParser;

class ZhHansCasualDateParser extends AbstractZhCasualDateParser
{
    protected function pattern(): string
    {
        return '/(?<now>现在|立(?:刻|即)|即刻)|(?<day1>今|明|前|大前|后|大后|昨)(?<time1>早|晚)|(?<time2>上午|早上|下午|晚上|夜晚?|中午|凌晨)|(?<day3>今|明|前|大前|后|大后|昨)(?:日|天)[\s,，]*(?<time3>上午|早上|下午|晚上|夜晚?|中午|凌晨)?/u';
    }

    protected function normalizeDay(string $day): string { return $day; }

    protected function timeHour(string $time): ?int
    {
        return match (mb_substr($time, 0, 1)) {
            '早', '上' => 6,
            '下' => 15,
            '中' => 12,
            '夜', '晚' => 22,
            '凌' => 0,
            default => null,
        };
    }

    protected function tag(): string { return 'parser/ZHHansCasualDateParser'; }
}
