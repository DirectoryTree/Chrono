<?php

namespace Chrono\Parsers;

use Carbon\CarbonImmutable;
use Chrono\Locales\En\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class NativeDateFormatParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse native JavaScript/RFC-style date strings.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $patterns = [
            '/(?<![\pL\pN])(?:[A-Z][a-z]{2},\s*)?\d{1,2}\s+[A-Z][a-z]{2}\s+\d{4}\s+\d{2}:\d{2}:\d{2}\s+(?:[+-]\d{4}|UTC|GMT)(?![\pL\pN])/u',
            '/(?<![\pL\pN])[A-Z][a-z]{2}\s+[A-Z][a-z]{2}\s+\d{1,2}\s+\d{4}\s+\d{2}:\d{2}:\d{2}\s+GMT[+-]\d{4}(?:\s*\([A-Z]{2,5}\))?(?![\pL\pN])/u',
            '/(?<![\pL\pN])\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2}:\d{2}(?:\.\d{1,6})?\s*(?:AM|PM)(?![\pL\pN])/iu',
        ];

        $results = [];

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

            foreach ($matches as $match) {
                $result = $this->result($match[0][0], $match[0][1]);

                if ($result !== null) {
                    $results[] = $result;
                }
            }
        }

        return $results;
    }

    protected function result(string $text, int $index): ?ParsedResult
    {
        $date = $this->date($text);

        if ($date === null) {
            return null;
        }

        $components = $this->components($date, [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            'hour' => $date->hour,
            'minute' => $date->minute,
            'second' => $date->second,
            'millisecond' => $date->millisecond,
        ]);

        if (($offset = $this->timezoneOffset($text)) !== null) {
            $components->assign('timezoneOffset', $offset);
        }

        $components->addTag('parser/NativeDateFormatParser');

        return new ParsedResult($index, $text, $components);
    }

    protected function date(string $text): ?CarbonImmutable
    {
        $normalized = preg_replace('/\s*\([^)]*\)\s*$/', '', trim($text)) ?? trim($text);

        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}\s+/', $normalized) === 1) {
            $date = \DateTimeImmutable::createFromFormat('!m/d/Y h:i:s.u A', $this->normalizeFractionalSeconds($normalized));

            return $date === false ? null : CarbonImmutable::instance($date);
        }

        if (preg_match('/^[A-Z][a-z]{2}\s+[A-Z][a-z]{2}\s+\d{1,2}\s+\d{4}\s+/', $normalized) === 1) {
            $normalized = preg_replace('/^[A-Z][a-z]{2}\s+/', '', $normalized) ?? $normalized;
        }

        $date = new CarbonImmutable($normalized);

        return $date;
    }

    protected function normalizeFractionalSeconds(string $text): string
    {
        return preg_replace_callback('/\.(\d{1,6})/', function (array $match): string {
            return '.'.str_pad($match[1], 6, '0');
        }, $text) ?? $text;
    }

    protected function timezoneOffset(string $text): ?int
    {
        if (preg_match('/(?:GMT|UTC)?([+-])(\d{2}):?(\d{2})?\b/i', $text, $match) === 1) {
            $offset = ((int) $match[2] * 60) + (int) ($match[3] ?? 0);

            return $match[1] === '-' ? -$offset : $offset;
        }

        if (preg_match('/\b(?:UTC|GMT)\b/i', $text) === 1) {
            return 0;
        }

        return null;
    }
}
