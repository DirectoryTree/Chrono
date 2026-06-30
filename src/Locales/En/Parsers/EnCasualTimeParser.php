<?php

namespace DirectoryTree\Chrono\Locales\En\Parsers;

use DirectoryTree\Chrono\CasualReferences;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Reference;

class EnCasualTimeParser extends AbstractParserWithWordBoundary
{
    /**
     * Get the English casual time pattern without the left boundary wrapper.
     */
    protected function innerPattern(Reference $reference, Options $options): string
    {
        return '(?:(?<this>this)\s*)?(?<time>morning|afternoon|evening|night|midnight|midday|noon)(?:\s+at\s+(?<hour>\d{1,2})(?::(?<minute>\d{2}))?\s*(?<meridiem>am|pm)?)?(?=\W|$)';
    }

    /**
     * Extract English casual time components.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null
    {
        $word = strtolower($match['time'][0]);

        $components = match ($word) {
            'afternoon' => CasualReferences::afternoon($reference),
            'evening', 'night' => CasualReferences::evening($reference),
            'midnight' => CasualReferences::midnight($reference),
            'morning' => CasualReferences::morning($reference),
            'midday', 'noon' => CasualReferences::noon($reference),
        };

        if (($match['hour'][0] ?? '') !== '') {
            $components
                ->assign('hour', $this->hourWithPeriod((int) $match['hour'][0], ($match['meridiem'][0] ?? '') ?: null, $word))
                ->assign('minute', ($match['minute'][0] ?? '') !== '' ? (int) $match['minute'][0] : 0)
                ->imply('second', 0)
                ->imply('millisecond', 0);
        }

        return new ParsedResult(
            $match[0][1],
            trim($match[0][0]),
            $components->addTag('parser/ENCasualTimeParser')
        );
    }

    /**
     * Resolve an hour against an optional meridiem or casual time period.
     */
    protected function hourWithPeriod(int $hour, ?string $meridiem, string $period): int
    {
        if ($meridiem !== null) {
            return $this->meridiemHour($hour, $meridiem);
        }

        if (in_array($period, ['afternoon', 'evening', 'night'], true) && $hour < 12) {
            return $hour + 12;
        }

        return $hour;
    }

    /**
     * Resolve an hour with an explicit meridiem suffix.
     */
    protected function meridiemHour(int $hour, string $meridiem): int
    {
        $meridiem = strtolower($meridiem);

        if ($meridiem === 'am') {
            return $hour === 12 ? 0 : $hour;
        }

        return $hour === 12 ? 12 : $hour + 12;
    }
}
