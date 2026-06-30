<?php

namespace DirectoryTree\Chrono\Locales\Pt\Parsers;

use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractTimeExpressionParser;
use DirectoryTree\Chrono\Reference;

class PtTimeExpressionParser extends AbstractTimeExpressionParser
{
    /**
     * Return the Portuguese primary time prefix.
     */
    protected function primaryPrefix(): string
    {
        return '(?:(?:ao?|às?|das|da|de|do)\s*)?';
    }

    /**
     * Return the Portuguese range connector before a following time.
     */
    protected function followingPhase(): string
    {
        return '\s*(?:-|–|~|〜|a(?:o)?|\?)\s*';
    }

    /**
     * Build a Portuguese time expression result.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $result = parent::result($text, $match, $reference);

        if ($result !== null) {
            $result->start->addTag('parser/PTTimeExpressionParser');
        }

        return $result;
    }
}
