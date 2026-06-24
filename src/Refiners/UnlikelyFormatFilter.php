<?php

namespace Chrono\Refiners;

use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Reference;

class UnlikelyFormatFilter extends Filter
{
    /**
     * Create an unlikely-format result filter.
     */
    public function __construct(
        /**
         * Whether strict filtering rules should be applied.
         */
        protected readonly bool $strictMode = false,
    ) {}

    /**
     * Determine whether the parsed result should be kept.
     */
    protected function isValid(string $text, ParsedResult $result, Reference $reference, Options $options): bool
    {
        $normalizedText = preg_replace('/ /', '', $result->text, 1) ?? $result->text;

        if (preg_match('/^\d*(?:\.\d*)?$/', $normalizedText) === 1) {
            return false;
        }

        if (! $result->start->isValidDate()) {
            return false;
        }

        if ($result->end !== null && ! $result->end->isValidDate()) {
            return false;
        }

        if ($this->strictMode && $result->start->isOnlyWeekdayComponent()) {
            return false;
        }

        return true;
    }
}
