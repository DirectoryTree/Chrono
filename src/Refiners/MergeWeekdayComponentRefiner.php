<?php

namespace DirectoryTree\Chrono\Refiners;

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;

readonly class MergeWeekdayComponentRefiner extends MergingRefiner
{
    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): bool
    {
        return $current->start->isOnlyWeekdayComponent()
            && ! $current->start->isCertain('hour')
            && $next->start->isCertain('day')
            && ! in_array('result/relativeDate', $next->tags(), true)
            && preg_match('/^,?\s*$/', $textBetween) === 1;
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $current, ParsedResult $next, string $text, Reference $reference, Options $options): ParsedResult
    {
        $next->start->assign('weekday', (int) $current->start->get('weekday'));
        $next->end?->assign('weekday', (int) $current->start->get('weekday'));

        $result = new ParsedResult(
            $current->index,
            $current->text.$textBetween.$next->text,
            $next->start,
            $next->end,
            $next->tags(),
        );

        foreach ($current->tags() as $tag) {
            $result->addTag($tag);
        }

        return $result;
    }
}
