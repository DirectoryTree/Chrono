<?php

namespace DirectoryTree\Chrono\Locales\Es\Parsers;

use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractTimeExpressionParser;
use DirectoryTree\Chrono\Reference;

class EsTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the Spanish primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:aslas|deslas|las?|al?|de|del)\s*)?';
    }

    /**
     * Return the Spanish range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|a(?:l)?|\?)\s*';
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
            $result->start->addTag('parser/ESTimeExpressionParser');
        }

        return $result;
    }
}
