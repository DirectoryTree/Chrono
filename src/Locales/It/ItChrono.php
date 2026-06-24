<?php

namespace Chrono\Locales\It;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\It\Parsers\ItCasualDateParser;
use Chrono\Locales\It\Parsers\ItCasualTimeParser;
use Chrono\Locales\It\Parsers\ItCasualYearMonthDayParser;
use Chrono\Locales\It\Parsers\ItMonthNameLittleEndianParser;
use Chrono\Locales\It\Parsers\ItMonthNameMiddleEndianParser;
use Chrono\Locales\It\Parsers\ItMonthNameParser;
use Chrono\Locales\It\Parsers\ItRelativeDateFormatParser;
use Chrono\Locales\It\Parsers\ItSlashMonthFormatParser;
use Chrono\Locales\It\Parsers\ItTimeExpressionParser;
use Chrono\Locales\It\Parsers\ItTimeUnitAgoFormatParser;
use Chrono\Locales\It\Parsers\ItTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\It\Parsers\ItTimeUnitLaterFormatParser;
use Chrono\Locales\It\Parsers\ItTimeUnitWithinFormatParser;
use Chrono\Locales\It\Parsers\ItWeekdayParser;
use Chrono\Locales\It\Refiners\ItMergeDateRangeRefiner;
use Chrono\Locales\It\Refiners\ItMergeDateTimeRefiner;
use Chrono\Locales\It\Refiners\ItMergeRelativeDateRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class ItChrono extends ConfiguredChronoEngine
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
