<?php

namespace Chrono\Locales\Nl\Parsers;

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
        return '(?:\s*(?:uur))?(?!\/)(?=\W|$)';
    }

    /**
     * Return the Dutch suffix allowed after a following time.
     */
    protected function followingSuffix(): string
    {
        return '(?:\s*(?:uur))?(?!\/)(?=\W|$)';
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
            $result->start->addTag('parser/NLTimeExpressionParser');
        }

        return $result;
    }
}
