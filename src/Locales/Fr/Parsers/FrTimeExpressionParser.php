<?php

namespace DirectoryTree\Chrono\Locales\Fr\Parsers;

use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractTimeExpressionParser;
use DirectoryTree\Chrono\Reference;

class FrTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the French primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:[àa]|de)\s*)?';
    }

    /**
     * Return the French range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|[àa]|\?)\s*';
    }

    /**
     * Get result.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $result = parent::result($text, $match, $reference);

        if ($result !== null) {
            $result->start->addTag('parser/FRTimeExpressionParser');
        }

        return $result;
    }
}
