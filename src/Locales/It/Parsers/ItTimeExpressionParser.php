<?php

namespace Chrono\Locales\It\Parsers;

use Chrono\Meridiem;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractTimeExpressionParser;
use Chrono\Reference;

class ItTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the Italian primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:alle|dalle)\s*)?';
    }

    /**
     * Return the Italian range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|to|\?)\s*';
    }

    /**
     * Return the Italian suffix allowed after a primary time.
     */
    protected function primarySuffix(): string
    {
        return '(?:\s*(?:in punto|o\W*in punto|sera|(?:di|del|della|in|al|alla|alle)\s*(?:mattina|pomeriggio|sera)))?(?!\/)(?=\W|$)';
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

        $text = mb_strtolower($match[0][0]);
        $hour = (int) $components->get('hour');

        if (str_ends_with($text, 'sera')) {
            if ($hour >= 6 && $hour < 12) {
                $components->assign('hour', $hour + 12);
                $components->assign('meridiem', Meridiem::PM->value);
            } elseif ($hour < 6) {
                $components->assign('meridiem', Meridiem::AM->value);
            }
        }

        if (str_ends_with($text, 'pomeriggio')) {
            $components->assign('meridiem', Meridiem::PM->value);

            if ($hour >= 0 && $hour <= 6) {
                $components->assign('hour', $hour + 12);
            }
        }

        if (str_ends_with($text, 'mattina')) {
            $components->assign('meridiem', Meridiem::AM->value);
        }

        return $components;
    }

    /**
     * Determine whether the parsed result should be rejected.
     */
    protected function shouldRejectResult(array $match, string $text, ?ParsedComponents $end): bool
    {
        return $end === null
            && preg_match('/^\d{1,4}$/', $text) === 1;
    }

    /**
     * Get result.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $result = parent::result($text, $match, $reference);

        if ($result !== null) {
            $result->start->addTag('parser/ITTimeExpressionParser');
        }

        return $result;
    }
}
