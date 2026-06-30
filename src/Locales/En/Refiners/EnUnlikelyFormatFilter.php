<?php

namespace DirectoryTree\Chrono\Locales\En\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiners\Filter;

readonly class EnUnlikelyFormatFilter extends Filter
{
    /**
     * Determine whether the parsed result is valid.
     */
    protected function isValid(string $text, ParsedResult $result, Reference $reference, Options $options): bool
    {
        $resultText = trim($result->text);

        if ($resultText === trim($text)) {
            return true;
        }

        if (strtolower($resultText) === 'may') {
            $textBefore = trim(substr($text, 0, $result->index));

            return preg_match('/\b(?:in)$/i', $textBefore) === 1;
        }

        if (str_ends_with(strtolower($resultText), 'the second')) {
            return trim(substr($text, $result->index + strlen($result->text))) === '';
        }

        if ($this->isPercentEncodedByte($text, $result)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether a result was carved out of a percent-encoded byte.
     */
    protected function isPercentEncodedByte(string $text, ParsedResult $result): bool
    {
        if ($result->index === 0 || substr($text, $result->index - 1, 1) !== '%') {
            return false;
        }

        return preg_match('/^[[:xdigit:]]{2}$/', $result->text) === 1;
    }
}
