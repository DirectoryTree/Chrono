<?php

namespace Chrono\Locales\Es\Parsers;

use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parsers\AbstractTimeExpressionParser;
use Chrono\Reference;

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

    protected function shouldRejectResult(array $match, string $text, ?ParsedComponents $end): bool
    {
        return $end === null
            && preg_match('/^\d{1,4}$/', $text) === 1;
    }

    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $result = parent::result($text, $match, $reference);

        if ($result !== null) {
            $result->start->addTag('parser/ESTimeExpressionParser');
        }

        return $result;
    }
}
