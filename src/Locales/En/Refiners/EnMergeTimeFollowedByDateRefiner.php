<?php

namespace Chrono\Locales\En\Refiners;

use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\MergingRefiner;

class EnMergeTimeFollowedByDateRefiner extends MergingRefiner
{
    use InteractsWithEnglishRefiners;

    /**
     * Determine whether the parsed results should be merged.
     */
    protected function shouldMergeResults(string $textBetween, ParsedResult $time, ParsedResult $date, string $text, Reference $reference, Options $options): bool
    {
        if (! $time->start->isCertain('hour') || $time->start->isCertain('day')) {
            return false;
        }

        if (! $date->start->isCertain('day')) {
            return false;
        }

        return $this->isDateTimeConnector($textBetween);
    }

    /**
     * Merge the parsed results.
     */
    protected function mergeResults(string $textBetween, ParsedResult $time, ParsedResult $date, string $text, Reference $reference, Options $options): ParsedResult
    {
        $this->mergeDateComponents($time->start, $date->start);

        if ($time->end !== null) {
            $this->mergeDateComponents($time->end, $date->end ?? $date->start);
        }

        if (($timeOffset = $time->start->timezoneOffset()) !== null) {
            $time->start->assign('timezoneOffset', $timeOffset);
        }

        $time->text = substr($text, $time->index, $date->index + strlen($date->text) - $time->index);

        foreach ($date->tags() as $tag) {
            $time->addTag($tag);
        }

        $time->addTag('refiner/mergeTimeFollowedByDate');

        return $time;
    }

    /**
     * Merge date fields into the time component while preserving inferred certainty.
     */
    protected function mergeDateComponents(ParsedComponents $time, ParsedComponents $date): void
    {
        foreach (['year', 'month', 'day'] as $component) {
            $date->isCertain($component)
                ? $time->assign($component, (int) $date->get($component))
                : $time->imply($component, (int) $date->get($component));
        }

        $time->addTags($date->tags());
    }
}
