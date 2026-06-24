<?php

namespace Chrono\Parsers;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

abstract class AbstractParserWithWordBoundary implements Parser
{
    /**
     * Get the inner parser pattern without the left boundary wrapper.
     */
    abstract protected function innerPattern(Reference $reference, Options $options): string;

    /**
     * Extract a parser result from a normalized inner match.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     */
    abstract protected function innerExtract(array $match, Reference $reference, Options $options): ParsedComponents|ParsedResult|null;

    /**
     * Parse text by applying the upstream-style word-boundary wrapper.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $results = [];
        $offset = 0;
        $pattern = $this->pattern($reference, $options);

        while (preg_match($pattern, $text, $match, PREG_OFFSET_CAPTURE, $offset) === 1) {
            $match = $this->normalizeMatch($match);
            $extracted = $this->innerExtract($match, $reference, $options);

            if ($extracted instanceof ParsedResult) {
                $results[] = $extracted;
                $offset = $extracted->index + strlen($extracted->text);

                continue;
            }

            if ($extracted instanceof ParsedComponents) {
                $result = new ParsedResult($match[0][1], $match[0][0], $extracted);
                $results[] = $result;
                $offset = $result->index + strlen($result->text);

                continue;
            }

            $offset = $match[0][1] + 1;
        }

        return $results;
    }

    /**
     * Get the regex fragment used as the left boundary.
     */
    protected function patternLeftBoundary(): string
    {
        return '(\W|^)';
    }

    /**
     * Get the fully wrapped parser pattern.
     */
    protected function pattern(Reference $reference, Options $options): string
    {
        return '/'.$this->patternLeftBoundary().$this->innerPattern($reference, $options).'/iu';
    }

    /**
     * Remove the boundary capture from the whole match.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     * @return array<string|int, array{0: string, 1: int}>
     */
    protected function normalizeMatch(array $match): array
    {
        $header = $match[1][0] ?? '';

        if ($header === '') {
            return $this->shiftNumericCaptures($match);
        }

        $match[0] = [
            substr($match[0][0], strlen($header)),
            $match[0][1] + strlen($header),
        ];

        return $this->shiftNumericCaptures($match);
    }

    /**
     * Remove the wrapper capture from numeric groups while preserving named groups.
     *
     * @param  array<string|int, array{0: string, 1: int}>  $match
     * @return array<string|int, array{0: string, 1: int}>
     */
    protected function shiftNumericCaptures(array $match): array
    {
        for ($i = 2; array_key_exists($i, $match); $i++) {
            $match[$i - 1] = $match[$i];
        }

        unset($match[$i - 1]);

        return $match;
    }
}
