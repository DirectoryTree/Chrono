<?php

namespace Chrono\Locales\Zh\Parsers;

use Chrono\Dates;
use Chrono\Locales\Zh\ZhConstants;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

abstract class AbstractZhTimeExpressionParser implements Parser
{
    /**
     * @return array<string, int>
     */
    abstract protected function numbers(): array;

    abstract protected function timeWordPattern(): string;

    abstract protected function pointCharacter(): string;

    abstract protected function tag(): string;

    protected function dayWordPattern(): string
    {
        return '今|明|前|大前|后|後|大后|大後|昨';
    }

    protected function normalizeDay(string $day): string
    {
        return $day;
    }

    /**
     * Parse Chinese time expressions and ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $numberChars = preg_quote(implode('', array_keys($this->numbers())), '/');
        $timeWords = $this->timeWordPattern();
        $point = $this->pointCharacter();

        $dayWords = $this->dayWordPattern();

        preg_match_all("/(?<![A-Za-z0-9_])(?:从|自|由)?(?:(?<day1>{$dayWords})(?<period1>早|朝|晚)|(?<period2>{$timeWords})|(?<day3>{$dayWords})(?:日|天)[\\s,，]*(?<period3>{$timeWords})?)?[\\s,，]*(?<hour>\\d+|[{$numberChars}]+)\\s*(?:{$point}|时|時|:|：)\\s*(?<minute>\\d+|半|正|整|[{$numberChars}]+)?\\s*(?:分|:|：)?\\s*(?<second>\\d+|[{$numberChars}]+)?\\s*(?:秒)?(?:\\s*(?<ampm>A\\.M\\.|P\\.M\\.|AM?|PM?))?/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference, $numberChars, $timeWords, $point, $dayWords): ?ParsedResult {
            $start = $this->componentsFromMatch($match, $reference);

            if ($start === null) {
                return null;
            }

            $resultText = $match[0][0];
            $remaining = substr($text, $match[0][1] + strlen($match[0][0]));
            $end = null;

            if (preg_match("/^\\s*(?:到|至|-|–|~|〜)\\s*(?:(?<day1>{$dayWords})(?<period1>早|朝|晚)|(?<period2>{$timeWords})|(?<day3>{$dayWords})(?:日|天)[\\s,，]*(?<period3>{$timeWords})?)?[\\s,，]*(?<hour>\\d+|[{$numberChars}]+)\\s*(?:{$point}|时|時|:|：)\\s*(?<minute>\\d+|半|正|整|[{$numberChars}]+)?\\s*(?:分|:|：)?\\s*(?<second>\\d+|[{$numberChars}]+)?\\s*(?:秒)?(?:\\s*(?<ampm>A\\.M\\.|P\\.M\\.|AM?|PM?))?/iu", $remaining, $endMatch) === 1) {
                $end = $this->componentsFromMatch($this->offsetlessMatch($endMatch), $reference);

                if ($end !== null) {
                    $endPeriod = ($endMatch['period1'] ?? '') ?: (($endMatch['period2'] ?? '') ?: ($endMatch['period3'] ?? ''));

                    if ($endPeriod === '' && ($endMatch['ampm'] ?? '') === '' && $start->date()->hour >= 12 && $end->date()->hour < 12) {
                        if (($start->date()->hour - 12) <= $end->date()->hour) {
                            $end->assign('hour', $end->date()->hour + 12);
                        }
                    }

                    if ($end->date()->lt($start->date())) {
                        $end->imply('day', $end->date()->day + 1);
                    }

                    $resultText .= $endMatch[0];
                }
            }

            $start->addTag($this->tag());
            $end?->addTag($this->tag());

            return new ParsedResult($match[0][1], trim($resultText), $start, $end);
        }, $matches)));
    }

    protected function componentsFromMatch(array $match, Reference $reference): ?ParsedComponents
    {
        $hour = ZhConstants::number($match['hour'][0], $this->numbers());
        $minuteText = $match['minute'][0] ?? '';
        $secondText = $match['second'][0] ?? '';
        $minute = 0;
        $second = 0;

        if ($minuteText !== '') {
            $minute = match ($minuteText) {
                '半' => 30,
                '正', '整' => 0,
                default => ZhConstants::number($minuteText, $this->numbers()),
            };
        } elseif ($hour > 100) {
            $minute = $hour % 100;
            $hour = intdiv($hour, 100);
        }

        if ($secondText !== '') {
            $second = ZhConstants::number($secondText, $this->numbers());
        }

        if ($hour > 24 || $minute >= 60 || $second >= 60) {
            return null;
        }

        $day1 = $match['day1'][0] ?? '';
        $day3 = $match['day3'][0] ?? '';
        $day = $this->normalizeDay($day1 ?: $day3);
        $period = ($match['period1'][0] ?? '') ?: (($match['period2'][0] ?? '') ?: ($match['period3'][0] ?? ''));
        $ampm = $match['ampm'][0] ?? '';

        if ($ampm !== '') {
            if ($hour > 12) {
                return null;
            }

            if (str_starts_with(strtolower($ampm), 'a')) {
                $hour = $hour === 12 ? 0 : $hour;
            } else {
                $hour = $hour !== 12 ? $hour + 12 : $hour;
            }
        } elseif ($period !== '') {
            $prefix = mb_substr($period, 0, 1);

            if (in_array($prefix, ['下', '晚', '夜', '晏'], true) && $hour !== 12) {
                $hour += 12;
            }

            if (in_array($prefix, ['早', '上', '朝', '凌'], true) && $hour === 12) {
                $hour = 0;
            }
        }

        $date = match ($day) {
            '明' => $day3 !== '' || $reference->date->hour > 1 ? $reference->date->addDay() : $reference->date,
            '昨' => $reference->date->subDay(),
            '前' => $reference->date->subDays(2),
            '大前' => $reference->date->subDays(3),
            '后', '後' => $reference->date->addDays(2),
            '大后', '大後' => $reference->date->addDays(3),
            default => $reference->date,
        };

        $date = $date->hour($hour)->minute($minute)->second($second)->millisecond(0);
        $components = new ParsedComponents($date);

        if ($day !== '') {
            Dates::assignSimilarDate($components, $date);
        }

        $components->assign('hour', $hour)->assign('minute', $minute);

        if ($secondText !== '') {
            $components->assign('second', $second);
        }

        $components->imply('meridiem', $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);

        return $components;
    }

    protected function offsetlessMatch(array $match): array
    {
        $offsetless = [];

        foreach ($match as $key => $value) {
            if (is_string($key)) {
                $offsetless[$key] = [$value, 0];
            }
        }

        return $offsetless;
    }
}
