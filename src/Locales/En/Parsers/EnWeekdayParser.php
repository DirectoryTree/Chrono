<?php

namespace Chrono\Locales\En\Parsers;

use Chrono\Calculation\Weekdays;
use Chrono\Locales\En\EnConstants;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

class EnWeekdayParser implements Parser
{
    /**
     * Parse English weekday references.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all(
            "/(?<![\\pL\\pN])(?:[,\\(（]\\s*)?(?:on\\s*?)?(?:(?<modifier>this|last|past|next)\\s*)?(?<weekday>{$this->weekdayPattern()}|weekend|weekday)(?:\\s*(?:,|\\)|）))?(?:\\s*(?:of\\s*)?(?<postmodifier>this|last|past|next)\\s*week)?(?=\\W|$)/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(function (array $match) use ($reference): ?ParsedResult {
            $modifier = $this->modifier(($match['modifier'][0] ?? '') ?: ($match['postmodifier'][0] ?? ''));
            $weekday = $this->weekday(mb_strtolower($match['weekday'][0]), $modifier, $reference);

            if ($weekday === null) {
                return null;
            }

            $components = Weekdays::createParsingComponentsAtWeekday($reference, $weekday, $modifier)
                ->addTag('parser/ENWeekdayParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components);
        }, $matches)));
    }

    /**
     * Resolve the weekday number from the matched weekday text.
     */
    protected function weekday(string $weekday, ?string $modifier, Reference $reference): ?int
    {
        if (isset(EnConstants::WEEKDAYS[$weekday])) {
            return EnConstants::WEEKDAYS[$weekday];
        }

        if ($weekday === 'weekend') {
            return $modifier === 'last' ? 0 : 6;
        }

        if ($weekday !== 'weekday') {
            return null;
        }

        $referenceWeekday = $reference->date->dayOfWeek;

        if ($referenceWeekday === 0 || $referenceWeekday === 6) {
            return $modifier === 'last' ? 5 : 1;
        }

        $weekday = $referenceWeekday - 1;
        $weekday = $modifier === 'last' ? $weekday - 1 : $weekday + 1;

        return ($weekday % 5) + 1;
    }

    /**
     * Normalize English weekday modifier words.
     */
    protected function modifier(string $modifier): ?string
    {
        return match (mb_strtolower($modifier)) {
            'last', 'past' => 'last',
            'next' => 'next',
            'this' => 'this',
            default => null,
        };
    }

    /**
     * Build a longest-first weekday regex alternation.
     */
    protected function weekdayPattern(): string
    {
        return EnConstants::weekdayPattern();
    }
}
