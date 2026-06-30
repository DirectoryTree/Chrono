<?php

namespace DirectoryTree\Chrono\Locales\En\Parsers;

use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Parsers\AbstractTimeExpressionParser;
use DirectoryTree\Chrono\Reference;

class EnTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the English primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:at|from)\s*)?';
    }

    /**
     * Return the English range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|to|until|through|till|\?)\s*';
    }

    /**
     * Return the English suffix allowed after a primary time.
     */
    protected function primarySuffix(): string
    {
        return '(?:\s*(?:o\W*clock|at\s*night|tonight|in\s*the\s*(?:morning|afternoon)))?(?!\/)(?=\W|$)';
    }

    /**
     * Return the English suffix allowed after a following time.
     */
    protected function followingSuffix(): string
    {
        return '(?:\s*(?<suffix>at\s*night|tonight|in\s*the\s*(?:morning|afternoon)))?(?!\/)(?=\W|$)';
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function primaryTimeComponents(array $match, Reference $reference): ?ParsedComponents
    {
        $components = parent::primaryTimeComponents($match, $reference);

        if ($components === null) {
            return null;
        }

        $text = strtolower($match[0][0]);
        $hour = (int) $components->get('hour');

        if (str_ends_with($text, 'night') || str_ends_with($text, 'tonight')) {
            if ($hour >= 6 && $hour < 12) {
                $components->assign('hour', $hour + 12);
                $components->assign('meridiem', Meridiem::PM->value);
                $components->addTag('meridiem');
            } elseif ($hour < 6) {
                $components->assign('meridiem', Meridiem::AM->value);
                $components->addTag('meridiem');
            }
        }

        if (str_ends_with($text, 'afternoon')) {
            $components->assign('meridiem', Meridiem::PM->value);
            $components->addTag('meridiem');

            if ($hour >= 0 && $hour <= 6) {
                $components->assign('hour', $hour + 12);
            }
        }

        if (str_ends_with($text, 'morning')) {
            $components->assign('meridiem', Meridiem::AM->value);
            $components->addTag('meridiem');
        }

        $components->addTag('parser/ENTimeExpressionParser');

        return $components;
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function followingTimeComponents(array $match, Reference $reference, ParsedComponents $start): ?ParsedComponents
    {
        $components = parent::followingTimeComponents($match, $reference, $start);

        if ($components !== null) {
            $this->applyFollowingSuffix($match, $start, $components);

            $components->addTag('parser/ENTimeExpressionParser');
        }

        return $components;
    }

    /**
     * Apply the following time suffix to parsed components.
     */
    protected function applyFollowingSuffix(array $match, ParsedComponents $start, ParsedComponents $end): void
    {
        $suffix = strtolower($match['suffix'][0] ?? '');

        if ($suffix === '') {
            return;
        }

        $startHour = (int) $start->get('hour');
        $endHour = (int) $end->get('hour');

        if (str_contains($suffix, 'afternoon')) {
            $this->assignPm($end);

            if ($endHour <= 6) {
                $end->assign('hour', $endHour + 12);
            }

            if (! $this->hasAssignedMeridiem($start)) {
                $this->assignPm($start);

                if ($startHour <= 6) {
                    $start->assign('hour', $startHour + 12);
                }
            }
        }

        if (str_contains($suffix, 'night') || str_contains($suffix, 'tonight')) {
            if ($endHour >= 6 && $endHour < 12) {
                $end->assign('hour', $endHour + 12);
                $this->assignPm($end);
            } elseif ($endHour < 6) {
                $end->assign('meridiem', Meridiem::AM->value);
                $end->addTag('meridiem');
            }

            if (! $this->hasAssignedMeridiem($start) && $startHour >= 6 && $startHour < 12) {
                $start->assign('hour', $startHour + 12);
                $this->assignPm($start);
            }
        }

        if (str_contains($suffix, 'morning')) {
            $end->assign('meridiem', Meridiem::AM->value);
            $end->addTag('meridiem');

            if (! $this->hasAssignedMeridiem($start)) {
                $start->assign('meridiem', Meridiem::AM->value);
                $start->addTag('meridiem');
            }
        }
    }

    /**
     * Assign the parsed component value.
     */
    protected function assignPm(ParsedComponents $components): void
    {
        $components->assign('meridiem', Meridiem::PM->value);
        $components->addTag('meridiem');
    }

    /**
     * Determine whether the parsed result should be rejected.
     */
    protected function shouldRejectResult(array $match, string $text, ?ParsedComponents $end): bool
    {
        $plain = trim($text);

        if ($this->options?->strict() && $this->isLooseStrictGuess($match, $end)) {
            return true;
        }

        if ($end === null && $this->shouldRejectWithoutFollowingTime($plain)) {
            return true;
        }

        if ($end !== null && $this->shouldRejectWithFollowingTime($plain)) {
            return true;
        }

        return preg_match('/^\d{1,2}-\d{1,2}(?:-\d{1,2})?$/', $plain) === 1;
    }

    /**
     * Determine whether the match should be rejected without a following time.
     */
    protected function shouldRejectWithoutFollowingTime(string $text): bool
    {
        if (preg_match('/^\d{1,4}$/', $text) === 1) {
            return true;
        }

        if (preg_match('/\d[ap]$/i', $text) === 1) {
            return true;
        }

        if (preg_match('/[^\d:.](?<ending>\d[\d.]+)$/', $text, $match) !== 1) {
            return false;
        }

        if ($this->options?->strict()) {
            return true;
        }

        if (str_contains($match['ending'], '.') && preg_match('/\d(?:\.\d{2})+$/', $match['ending']) !== 1) {
            return true;
        }

        return (int) $match['ending'] > 24;
    }

    /**
     * Determine whether the match should be rejected with a following time.
     */
    protected function shouldRejectWithFollowingTime(string $text): bool
    {
        if (preg_match('/[^\d:.](?<start>\d[\d.]+)\s*-\s*(?<end>\d[\d.]+)$/', $text, $match) !== 1) {
            return false;
        }

        if ($this->options?->strict()) {
            return true;
        }

        if (str_contains($match['end'], '.') && preg_match('/\d(?:\.\d{2})+$/', $match['end']) !== 1) {
            return true;
        }

        return (int) $match['start'] > 24 || (int) $match['end'] > 24;
    }

    /**
     * Determine whether the match is a loose strict-mode guess.
     */
    protected function isLooseStrictGuess(array $match, ?ParsedComponents $end): bool
    {
        return ($match['minute'][0] ?? '') === ''
            && ($match['second'][0] ?? '') === ''
            && ($match['meridiem'][0] ?? '') === ''
            && preg_match('/o\W*clock/i', $match[0][0]) !== 1;
    }
}
