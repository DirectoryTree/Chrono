<?php

namespace Chrono\Locales\Nl\Parsers;

use Chrono\Meridiem;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractTimeExpressionParser;
use Chrono\Reference;

class NlTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the Dutch primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:om)\s*)?';
    }

    /**
     * Return the Dutch range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|om|\?)\s*';
    }

    /**
     * Return the Dutch suffix allowed after a primary time.
     */
    protected function primarySuffix(): string
    {
        return '(?:\s*(?:uur|vanavond|in\s+de\s+namiddag|\'s\s+avonds|\'s\s+ochtends))?(?!\/)(?=\W|$)';
    }

    /**
     * Return the Dutch suffix allowed after a following time.
     */
    protected function followingSuffix(): string
    {
        return $this->primarySuffix();
    }

    /**
     * Reject standalone year-like matches.
     */
    protected function shouldRejectResult(array $match, string $text, ?ParsedComponents $end): bool
    {
        return $end === null
            && preg_match('/^\d{1,4}$/', $text) === 1;
    }

    /**
     * Build a Dutch time expression result.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $result = parent::result($text, $match, $reference);

        if ($result !== null) {
            $this->applyMeridiemSuffix($result);
            $result->start->addTag('parser/NLTimeExpressionParser');

            if ($result->end !== null) {
                $result->end->addTag('parser/NLTimeExpressionParser');
            }
        }

        return $result;
    }

    /**
     * Apply Dutch AM/PM suffixes consumed by the numeric time parser.
     */
    protected function applyMeridiemSuffix(ParsedResult $result): void
    {
        $text = mb_strtolower($result->text);
        $components = array_filter([$result->start, $result->end]);

        if (preg_match('/vanavond|\'s\s+avonds/u', $text) === 1) {
            foreach ($components as $component) {
                $this->applyEveningMeridiem($component);
            }
        }

        if (str_contains($text, 'namiddag')) {
            foreach ($components as $component) {
                $this->applyAfternoonMeridiem($component);
            }
        }

        if (preg_match('/\'s\s+ochtends/u', $text) === 1) {
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
     * Apply a Dutch evening suffix to one parsed time component.
     */
    protected function applyEveningMeridiem(ParsedComponents $component): void
    {
        $hour = $component->get('hour');

        if ($hour >= 6 && $hour < 12) {
            $component->assign('hour', $hour + 12);
        }

        $component->assign('meridiem', Meridiem::PM->value)->addTag('meridiem');
    }

    /**
     * Apply a Dutch afternoon suffix to one parsed time component.
     */
    protected function applyAfternoonMeridiem(ParsedComponents $component): void
    {
        $hour = $component->get('hour');

        if ($hour >= 0 && $hour <= 6) {
            $component->assign('hour', $hour + 12);
        }

        $component->assign('meridiem', Meridiem::PM->value)->addTag('meridiem');
    }
}
