<?php

namespace DirectoryTree\Chrono\Locales\It;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\It\Parsers\ItCasualDateParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItCasualTimeParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItCasualYearMonthDayParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItMonthNameMiddleEndianParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItMonthNameParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItSlashMonthFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItTimeExpressionParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItTimeUnitLaterFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItWeekdayParser;
use DirectoryTree\Chrono\Locales\It\Refiners\ItMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\It\Refiners\ItMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\It\Refiners\ItMergeRelativeDateRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class ItChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Italian Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Italian parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Italian parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new ItTimeUnitCasualRelativeFormatParser,
                new ItRelativeDateFormatParser,
                new ItMonthNameParser,
                new ItCasualTimeParser,
                new ItCasualDateParser,
                new IsoFormatParser,
                new SlashDateFormatParser,
                new ItTimeUnitWithinFormatParser,
                new ItMonthNameLittleEndianParser,
                new ItMonthNameMiddleEndianParser,
                new ItWeekdayParser,
                new ItCasualYearMonthDayParser,
                new ItSlashMonthFormatParser,
                new ItTimeExpressionParser,
                new ItTimeUnitAgoFormatParser,
                new ItTimeUnitLaterFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new ItMergeRelativeDateRefiner,
                new ItMergeDateTimeRefiner,
                new ItMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Italian parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser,
                new ItTimeUnitWithinFormatParser,
                new ItMonthNameLittleEndianParser,
                new ItMonthNameMiddleEndianParser,
                new ItWeekdayParser,
                new ItCasualYearMonthDayParser,
                new ItSlashMonthFormatParser,
                new ItTimeExpressionParser,
                new ItTimeUnitAgoFormatParser(strictMode: true),
                new ItTimeUnitLaterFormatParser(strictMode: true),
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new ItMergeRelativeDateRefiner,
                new ItMergeDateTimeRefiner,
                new ItMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
