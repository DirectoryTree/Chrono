<?php

namespace DirectoryTree\Chrono\Locales\Ja\Parsers;

use DirectoryTree\Chrono\Locales\Ja\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

readonly class JaCasualDateParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse Japanese casual date references.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/今日|きょう|本日|ほんじつ|昨日|きのう|明日|あした|今夜|こんや|今夕|こんゆう|今晩|こんばん|今朝|けさ/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_map(function (array $match) use ($reference): ParsedResult {
            $word = $this->normalize($match[0][0]);

            $date = match ($word) {
                '昨日' => $reference->date->subDay(),
                '明日' => $reference->date->addDay(),
                default => $reference->date,
            };

            $known = [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ];

            if (in_array($word, ['今夜', '今夕', '今晩'], true)) {
                $date = $date->hour(22);
                $known['hour'] = 22;
            }

            if ($word === '今朝') {
                $date = $date->hour(6);
                $known['hour'] = 6;
            }

            $components = $this->components($date, $known)
                ->addTag('parser/JPCasualDateParser');

            return new ParsedResult($match[0][1], $match[0][0], $components);
        }, $matches);
    }

    /**
     * Normalize the value.
     */
    protected function normalize(string $text): string
    {
        return match ($text) {
            'きょう' => '今日',
            'ほんじつ' => '本日',
            'きのう' => '昨日',
            'あした' => '明日',
            'こんや' => '今夜',
            'こんゆう' => '今夕',
            'こんばん' => '今晩',
            'けさ' => '今朝',
            default => $text,
        };
    }
}
