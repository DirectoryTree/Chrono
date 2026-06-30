<?php

namespace DirectoryTree\Chrono\Locales\Vi\Parsers;

use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class ViTimeExpressionParser implements Parser
{
    /**
     * Parse Vietnamese numeric time expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\pL\pN])(?<prefix>lúc\s*|vào\s*)?(?<hour>[0-9]{1,2})(?:(?:\s*giờ\s*(?<minute_word>[0-9]{1,2})?\s*(?:phút\s*)?(?<period>sáng|trưa|chiều|tối|đêm)?)|:(?<minute_colon>[0-9]{2}))(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $hour = (int) $match['hour'][0];

            if ($hour > 23) {
                return null;
            }

            $minute = (int) (($match['minute_colon'][0] ?? '') ?: (($match['minute_word'][0] ?? '') ?: 0));

            if ($minute >= 60) {
                return null;
            }

            $period = mb_strtolower($match['period'][0] ?? '');
            $meridiem = null;

            if ($period === 'sáng') {
                $meridiem = Meridiem::AM;
                $hour = $hour === 12 ? 0 : $hour;
            } elseif ($period === 'trưa') {
                if ($hour < 10) {
                    $meridiem = Meridiem::PM;
                    $hour += 12;
                } else {
                    $meridiem = $hour >= 12 ? Meridiem::PM : Meridiem::AM;
                }
            } elseif (in_array($period, ['chiều', 'tối', 'đêm'], true)) {
                $meridiem = Meridiem::PM;

                if ($hour < 12) {
                    $hour += 12;
                }
            }

            $date = $reference->date
                ->hour($hour)
                ->minute($minute)
                ->second(0)
                ->millisecond(0);

            $components = new ParsedComponents($date);
            $components->assign('hour', $hour);
            $components->assign('minute', $minute);
            $components->imply('second', 0);
            $components->imply('millisecond', 0);

            if ($meridiem !== null) {
                $components->assign('meridiem', $meridiem->value);
            }

            $components->addTag('parser/VITimeExpressionParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components);
        }, $matches)));
    }
}
