<?php

namespace DirectoryTree\Chrono\Locales\Ja\Parsers;

use DirectoryTree\Chrono\Locales\Ja\JaConstants;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class JaTimeExpressionParser implements Parser
{
    /**
     * Parse Japanese time expressions and ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $number = '[0-9０-９]+|[零〇一二三四五六七八九十]+';

        preg_match_all("/(?<![A-Za-z0-9_])(?<meridiem1>午前|午後|A\\.M\\.|P\\.M\\.|AM|PM)?[\\s,，、]*(?<hour>{$number})(?:\\s*)(?:時(?!間)|:|：)(?:\\s*)(?<minute>{$number}|半)?(?:\\s*)(?:分|:|：)?(?:\\s*)(?<second>{$number})?(?:\\s*)(?:秒)?(?:\\s*(?<meridiem2>A\\.M\\.|P\\.M\\.|AM?|PM?))?/iu", $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_values(array_filter(array_map(function (array $match) use ($text, $reference, $number): ?ParsedResult {
            $start = $this->components($match, $reference);

            if ($start === null) {
                return null;
            }

            $resultText = $match[0][0];
            $remaining = substr($text, $match[0][1] + strlen($match[0][0]));
            $end = null;

            if (preg_match("/^\\s*(?:から|-|–|－|~|〜)\\s*(?<meridiem1>午前|午後|A\\.M\\.|P\\.M\\.|AM|PM)?[\\s,，、]*(?<hour>{$number})(?:\\s*)(?:時|:|：)(?:\\s*)(?<minute>{$number}|半)?(?:\\s*)(?:分|:|：)?(?:\\s*)(?<second>{$number})?(?:\\s*)(?:秒)?(?:\\s*(?<meridiem2>A\\.M\\.|P\\.M\\.|AM?|PM?))?/iu", $remaining, $endMatch) === 1) {
                $end = $this->components($this->offsetlessMatch($endMatch), $reference);

                if ($end === null) {
                    return null;
                }

                if (! $end->isCertain('meridiem') && $start->isCertain('meridiem')) {
                    $this->implyMeridiemFromStart($start, $end);
                }

                if ($end->date()->lt($start->date())) {
                    $end->imply('day', $end->date()->day + 1);
                }

                $resultText .= $endMatch[0];
            }

            $start->addTag('parser/JPTimeExpressionParser');
            $end?->addTag('parser/JPTimeExpressionParser');

            [$index, $resultText] = $this->trimmedMatch($match[0][1], $resultText);

            return new ParsedResult($index, $resultText, $start, $end);
        }, $matches)));
    }

    /**
     * Trim matched time text while keeping the byte offset aligned with the
     * trimmed text. PCRE offsets are byte offsets, so use strlen here.
     *
     * @return array{0: int, 1: string}
     */
    protected function trimmedMatch(int $index, string $text): array
    {
        if (preg_match('/^\s+/u', $text, $match) === 1) {
            $index += strlen($match[0]);
        }

        return [$index, trim($text)];
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function components(array $match, Reference $reference): ?ParsedComponents
    {
        $hour = JaConstants::number($match['hour'][0]);

        if ($hour > 24) {
            return null;
        }

        $minute = 0;
        $second = 0;

        if (($match['minute'][0] ?? '') !== '') {
            $minute = $match['minute'][0] === '半' ? 30 : JaConstants::number($match['minute'][0]);
        }

        if (($match['second'][0] ?? '') !== '') {
            $second = JaConstants::number($match['second'][0]);
        }

        if ($minute >= 60 || $second >= 60) {
            return null;
        }

        $meridiemText = ($match['meridiem1'][0] ?? '') ?: ($match['meridiem2'][0] ?? '');
        $known = ['hour' => $hour];

        if (($match['minute'][0] ?? '') !== '') {
            $known['minute'] = $minute;
        }

        if (($match['second'][0] ?? '') !== '') {
            $known['second'] = $second;
        }

        if ($meridiemText !== '') {
            if ($hour > 12) {
                return null;
            }

            if ($meridiemText === '午前' || str_starts_with(strtolower($meridiemText), 'a')) {
                $hour = $hour === 12 ? 0 : $hour;
            } else {
                $hour = $hour !== 12 ? $hour + 12 : $hour;
            }

            $known['hour'] = $hour;
            $known['meridiem'] = $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value;
        }

        $date = $reference->date->hour($hour)->minute($minute)->second($second)->millisecond(0);

        $components = new ParsedComponents($date);

        foreach ($known as $component => $value) {
            $components->assign($component, $value);
        }

        if ($meridiemText === '') {
            $components->imply('meridiem', $hour < 12 ? Meridiem::AM->value : Meridiem::PM->value);
        }

        return $components;
    }

    /**
     * Imply missing component values.
     */
    protected function implyMeridiemFromStart(ParsedComponents $start, ParsedComponents $end): void
    {
        if ($start->get('meridiem') === Meridiem::PM) {
            if ($start->date()->hour - 12 > $end->date()->hour) {
                $end->imply('meridiem', Meridiem::AM->value);

                return;
            }

            if ($end->date()->hour < 12) {
                $end->assign('hour', $end->date()->hour + 12);
            }
        }

        $end->imply('meridiem', $start->get('meridiem')?->value ?? Meridiem::AM->value);
    }

    /**
     * @return array<string, array{0: string, 1: int}>
     */
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
