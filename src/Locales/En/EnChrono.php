<?php

namespace DirectoryTree\Chrono\Locales\En;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\En\Parsers\EnCasualDateParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnCasualTimeParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameLittleEndianDateTimeParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameMiddleEndianParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameOrdinalParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameRangeParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameTrailingYearParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnMonthNameWeekdayParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnSlashDateParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnSlashMonthFormatParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeExpressionParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeUnitLaterFormatParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnWeekdayParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnYearMonthDayParser;
use DirectoryTree\Chrono\Locales\En\Refiners\EnExtractYearSuffixRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeRelativeAfterDateRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeRelativeFollowByDateRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeSpecificDateIntoTimeRangeRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeTimeFollowedByDateRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeTrailingTimeRangeRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnUnlikelyFormatFilter;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\NativeDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class EnChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured English Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the casual English parser.
     */
    public static function casual(): self
    {
        return new self(self::createCasualConfiguration());
    }

    /**
     * Create the strict English parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the British English parser.
     */
    public static function british(): self
    {
        return new self(self::createBritishConfiguration());
    }

    /**
     * Create the British English parser.
     */
    public static function gb(): self
    {
        return self::british();
    }

    /**
     * Create the casual English parser and refiner configuration.
     */
    public static function createCasualConfiguration(bool $littleEndian = false): Configuration
    {
        return self::createConfiguration(casual: true, littleEndian: $littleEndian);
    }

    /**
     * Create the strict English parser and refiner configuration.
     */
    public static function createStrictConfiguration(bool $littleEndian = false): Configuration
    {
        return self::createConfiguration(casual: false, littleEndian: $littleEndian);
    }

    /**
     * Create the British English parser and refiner configuration.
     */
    public static function createBritishConfiguration(): Configuration
    {
        return self::createCasualConfiguration(littleEndian: true);
    }

    /**
     * Create the English parser configuration.
     */
    protected static function createConfiguration(bool $casual, bool $littleEndian = false): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new NativeDateFormatParser,
                new EnYearMonthDayParser(strictMonthDateOrder: ! $casual),
                new EnSlashDateParser(littleEndian: $littleEndian),
                new EnSlashMonthFormatParser,
                new EnMonthNameLittleEndianDateTimeParser,
                new EnMonthNameLittleEndianParser,
                new EnMonthNameMiddleEndianParser(shouldSkipYearLikeDate: $littleEndian),
                new EnMonthNameTrailingYearParser,
                new EnMonthNameRangeParser,
                new EnMonthNameOrdinalParser,
                new EnMonthNameWeekdayParser,
                new EnMonthNameParser,
                ...($casual ? [new EnCasualTimeParser] : []),
                new EnTimeExpressionParser,
                ...($casual ? [new EnCasualDateParser] : []),
                new EnWeekdayParser,
                new EnTimeUnitWithinFormatParser(strictMode: ! $casual),
                new EnRelativeDateFormatParser,
                ...($casual ? [new EnTimeUnitCasualRelativeFormatParser] : []),
                new EnTimeUnitAgoFormatParser(strictMode: ! $casual),
                new EnTimeUnitLaterFormatParser(strictMode: ! $casual),
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new EnMergeRelativeFollowByDateRefiner,
                new EnMergeRelativeAfterDateRefiner,
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new ExtractTimezoneAbbrRefiner,
                new EnMergeTimeFollowedByDateRefiner,
                new EnMergeDateTimeRefiner,
                new EnMergeTrailingTimeRangeRefiner,
                new EnMergeSpecificDateIntoTimeRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: ! $casual),
                ...($casual ? [new EnUnlikelyFormatFilter] : []),
                new EnMergeDateTimeRefiner,
                new EnExtractYearSuffixRefiner,
                new EnMergeDateRangeRefiner,
            ],
        );
    }
}
