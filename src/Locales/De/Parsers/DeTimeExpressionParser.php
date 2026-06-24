<?php

namespace Chrono\Locales\De\Parsers;

use Chrono\ParsedComponents;
use Chrono\Parsers\AbstractTimeExpressionParser;

class DeTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the German primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:um|von)\s*)?';
    }

    /**
     * Return the German range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|bis)\s*';
    }

    /**
     * Determine whether the parsed result should be rejected.
     */
    protected function shouldRejectResult(array $match, string $text, ?ParsedComponents $end): bool
    {
        return $end === null
            && preg_match('/^\d{1,4}$/', $text) === 1;
    }
}
