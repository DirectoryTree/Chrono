<?php

namespace Chrono\Locales\En;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\En\Parsers\EnCasualDateParser;
use Chrono\Locales\En\Parsers\EnCasualTimeParser;
use Chrono\Locales\En\Parsers\EnMonthNameLittleEndianDateTimeParser;
use Chrono\Locales\En\Parsers\EnMonthNameLittleEndianParser;
use Chrono\Locales\En\Parsers\EnMonthNameMiddleEndianParser;
use Chrono\Locales\En\Parsers\EnMonthNameOrdinalParser;
use Chrono\Locales\En\Parsers\EnMonthNameParser;
use Chrono\Locales\En\Parsers\EnMonthNameRangeParser;
use Chrono\Locales\En\Parsers\EnMonthNameTrailingYearParser;
use Chrono\Locales\En\Parsers\EnMonthNameWeekdayParser;
use Chrono\Locales\En\Parsers\EnRelativeDateFormatParser;
use Chrono\Locales\En\Parsers\EnSlashDateParser;
use Chrono\Locales\En\Parsers\EnSlashMonthFormatParser;
use Chrono\Locales\En\Parsers\EnTimeExpressionParser;
use Chrono\Locales\En\Parsers\EnTimeUnitAgoFormatParser;
use Chrono\Locales\En\Parsers\EnTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\En\Parsers\EnTimeUnitLaterFormatParser;
use Chrono\Locales\En\Parsers\EnTimeUnitWithinFormatParser;
use Chrono\Locales\En\Parsers\EnWeekdayParser;
use Chrono\Locales\En\Parsers\EnYearMonthDayParser;
use Chrono\Locales\En\Refiners\EnExtractYearSuffixRefiner;
use Chrono\Locales\En\Refiners\EnMergeDateRangeRefiner;
use Chrono\Locales\En\Refiners\EnMergeDateTimeRefiner;
use Chrono\Locales\En\Refiners\EnMergeRelativeAfterDateRefiner;
use Chrono\Locales\En\Refiners\EnMergeRelativeFollowByDateRefiner;
use Chrono\Locales\En\Refiners\EnMergeSpecificDateIntoTimeRangeRefiner;
use Chrono\Locales\En\Refiners\EnMergeTimeFollowedByDateRefiner;
use Chrono\Locales\En\Refiners\EnMergeTrailingTimeRangeRefiner;
use Chrono\Locales\En\Refiners\EnUnlikelyFormatFilter;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\NativeDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

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
