<?php

namespace DirectoryTree\Chrono\Locales\Ru\Parsers;

use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractTimeExpressionParser;
use DirectoryTree\Chrono\Reference;

class RuTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the Russian prefix accepted before numeric time.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:в|с)\s*)?';
    }

    /**
     * Return the Russian connector accepted before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|до|по|и|\?)\s*';
    }

    /**
     * Return the Russian suffix accepted after numeric time.
     */
    protected function primarySuffix(): string
    {
        return '(?:\s*(?:утра|вечера|после\s+полудня))?(?!\/)(?=\W|$)';
    }

    /**
     * Return the Russian suffix accepted after following numeric time.
     */
    protected function followingSuffix(): string
    {
        return $this->primarySuffix();
    }

    /**
     * Build a parsed time result and apply Russian meridiem words.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $result = parent::result($text, $match, $reference);

        if ($result === null) {
            return null;
        }

        if ($this->isInvalidSingleDigitMinuteRange($result->text)
            || $this->isStrictLooseNumericTime($result)
            || ($result->end === null && $this->isFollowedByInvalidRangeTail($text, $match))) {
            return null;
        }

        $this->applyMeridiemSuffix($result);
        $result->start->addTag('parser/RUTimeExpressionParser');

        if ($result->end !== null) {
            $result->end->addTag('parser/RUTimeExpressionParser');
        }

        return $result;
    }

    /**
     * Apply Russian AM/PM suffixes that are not handled by the base parser.
     */
    protected function applyMeridiemSuffix(ParsedResult $result): void
    {
        $text = mb_strtolower($result->text);
        $components = array_filter([$result->start, $result->end]);

        if (str_contains($text, 'вечера')) {
            foreach ($components as $component) {
                $this->applyEveningMeridiem($component);
            }
        }

        if (preg_match('/после\s+полудня/u', $text) === 1) {
            foreach ($components as $component) {
                $this->applyAfternoonMeridiem($component);
            }
        }

        if (str_contains($text, 'утра')) {
            foreach ($components as $component) {
                $hour = $component->get('hour');

                if ($hour === 12) {
                    $component->assign('hour', 0);
                }

                $component->assign('meridiem', Meridiem::AM->value)->addTag('meridiem');
            }
        }
    }

    /**
     * Mark a time component as PM when the suffix asks for it.
     */
    protected function applyEveningMeridiem(ParsedComponents $component): void
    {
        $hour = $component->get('hour');

        if ($hour >= 6 && $hour < 12) {
            $component->assign('hour', $hour + 12)->assign('meridiem', Meridiem::PM->value)->addTag('meridiem');
        } elseif ($hour < 6) {
            $component->assign('meridiem', Meridiem::AM->value)->addTag('meridiem');
        }
    }

    /**
     * Mark a time component as PM after a Russian afternoon suffix.
     */
    protected function applyAfternoonMeridiem(ParsedComponents $component): void
    {
        $hour = $component->get('hour');

        if ($hour >= 0 && $hour <= 6) {
            $component->assign('hour', $hour + 12);
        }

        $component->assign('meridiem', Meridiem::PM->value)->addTag('meridiem');
    }

    /**
     * Reject partial matches before an invalid range tail.
     */
    protected function isFollowedByInvalidRangeTail(string $text, array $match): bool
    {
        $remainingText = substr($text, $match[0][1] + strlen($match[0][0]));

        return preg_match('/^\s*(?:-|–|~|〜|до|по|и|\?)\s*\d{1,4}(?:\.|:|：)\d(?!\d)/u', $remainingText) === 1;
    }

    /**
     * Reject Russian ranges whose following time has a single digit minute.
     */
    protected function isInvalidSingleDigitMinuteRange(string $text): bool
    {
        return preg_match('/(?:-|–|~|〜|до|по|и|\?)\s*\d{1,4}(?:\.|:|：)\d(?!\d)/u', $text) === 1;
    }

    /**
     * Reject loose prefixed numeric Russian times in strict mode.
     */
    protected function isStrictLooseNumericTime(ParsedResult $result): bool
    {
        if (! $this->options?->strict()) {
            return false;
        }

        if ($result->end === null) {
            return preg_match('/[^\d:.](\d[\d.]+)$/u', $result->text) === 1;
        }

        return preg_match('/^\d+-\d+$/u', $result->text) === 1
            || preg_match('/[^\d:.](\d[\d.]+)\s*-\s*(\d[\d.]+)$/u', $result->text) === 1;
    }
}
