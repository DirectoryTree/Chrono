<?php

namespace DirectoryTree\Chrono\Locales\Vi;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViCasualDateParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViCasualTimeParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViMonthYearParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViStandardParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViTimeUnitLaterFormatParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViWeekdayParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViYearParser;
use DirectoryTree\Chrono\Locales\Vi\Refiners\ViMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Vi\Refiners\ViMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\Vi\Refiners\ViMergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class ViChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Vietnamese Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Vietnamese parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Vietnamese parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new ViTimeUnitCasualRelativeFormatParser,
                new ViCasualDateParser,
                new ViCasualTimeParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new ViStandardParser,
                new ViMonthYearParser,
                new ViYearParser,
                new ViWeekdayParser,
                new ViTimeExpressionParser,
                new ViTimeUnitAgoFormatParser,
                new ViTimeUnitLaterFormatParser,
                new ViTimeUnitWithinFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new ViMergeWeekdayComponentRefiner,
                new ViMergeDateRangeRefiner,
                new ViMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Vietnamese parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new ViStandardParser,
                new ViMonthYearParser,
                new ViYearParser,
                new ViWeekdayParser,
                new ViTimeExpressionParser,
                new ViTimeUnitAgoFormatParser(strictMode: true),
                new ViTimeUnitLaterFormatParser(strictMode: true),
                new ViTimeUnitWithinFormatParser(strictMode: true),
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new ViMergeWeekdayComponentRefiner,
                new ViMergeDateRangeRefiner,
                new ViMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
