<?php

namespace DirectoryTree\Chrono\Locales\Fr\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Locales\Fr\CreatesParsedComponents;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Reference;

readonly class FrSlashDateParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse French numeric slash date expressions.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $results = (new SlashDateFormatParser(
            littleEndian: true,
            forwardDateByDefault: true,
            twoDigitYearPastThreshold: 50,
        ))->parse($text, $reference, $options);

        $weekday = 'lundi|mardi|mercredi|jeudi|vendredi|samedi|dimanche';

        preg_match_all('/\b(?:(?<weekday>'.$weekday.')\s*,?\s*)?(?<day>\d{1,2})\/(?<month>\d{1,2})(?:\/(?<year>\d{2,4}))?(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $results = [
            ...$results,
            ...array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
                if (($match['weekday'][0] ?? '') === '') {
                    return null;
                }

                $day = (int) $match['day'][0];
                $month = (int) $match['month'][0];
                $year = ($match['year'][0] ?? '') !== ''
                    ? $this->year((int) $match['year'][0])
                    : $reference->date->year;

                if (! checkdate($month, $day, $year)) {
                    return null;
                }

                $date = CarbonImmutable::create($year, $month, $day, 12, 0, 0, $reference->date->timezone);

                if (($match['year'][0] ?? '') === '' && $date->lessThan($reference->date)) {
                    $date = $date->addYear();
                    $year = $date->year;
                }

                $known = [
                    'month' => $month,
                    'day' => $day,
                    ...((($match['year'][0] ?? '') !== '') ? ['year' => $year] : []),
                ];

                if (($match['weekday'][0] ?? '') !== '') {
                    $known['weekday'] = $this->weekday($match['weekday'][0]);
                }

                $components = $this->components($date, $known);
                $components->addTag('parser/FRSlashDateParser');

                return new ParsedResult($match[0][1], $match[0][0], $components);
            }, $matches))),
        ];

        usort($results, fn (ParsedResult $a, ParsedResult $b) => $a->index <=> $b->index ?: strlen($b->text) <=> strlen($a->text));

        return $results;
    }

    /**
     * Resolve the year value.
     */
    protected function year(int $year): int
    {
        if ($year < 100) {
            return $year > 50 ? 1900 + $year : 2000 + $year;
        }

        return $year;
    }

    /**
     * Resolve the weekday value.
     */
    protected function weekday(string $weekday): int
    {
        return [
            'dimanche' => 0,
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
        ][mb_strtolower($weekday)];
    }
}
