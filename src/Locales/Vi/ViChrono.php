<?php

namespace Chrono\Locales\Vi;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Vi\Parsers\ViCasualDateParser;
use Chrono\Locales\Vi\Parsers\ViCasualTimeParser;
use Chrono\Locales\Vi\Parsers\ViMonthYearParser;
use Chrono\Locales\Vi\Parsers\ViStandardParser;
use Chrono\Locales\Vi\Parsers\ViTimeExpressionParser;
use Chrono\Locales\Vi\Parsers\ViTimeUnitAgoFormatParser;
use Chrono\Locales\Vi\Parsers\ViTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Vi\Parsers\ViTimeUnitLaterFormatParser;
use Chrono\Locales\Vi\Parsers\ViTimeUnitWithinFormatParser;
use Chrono\Locales\Vi\Parsers\ViWeekdayParser;
use Chrono\Locales\Vi\Parsers\ViYearParser;
use Chrono\Locales\Vi\Refiners\ViMergeDateRangeRefiner;
use Chrono\Locales\Vi\Refiners\ViMergeDateTimeRefiner;
use Chrono\Locales\Vi\Refiners\ViMergeWeekdayComponentRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class ViChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Vietnamese Chrono engine.
     */
    public function __construct(?Configuration $configuration = null)
    {
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
                new ViTimeUnitCasualRelativeFormatParser(),
                new ViCasualDateParser(),
                new ViCasualTimeParser(),
                new IsoFormatParser(),
                new SlashDateFormatParser(littleEndian: true),
                new ViStandardParser(),
                new ViMonthYearParser(),
                new ViYearParser(),
                new ViWeekdayParser(),
                new ViTimeExpressionParser(),
                new ViTimeUnitAgoFormatParser(),
                new ViTimeUnitLaterFormatParser(),
                new ViTimeUnitWithinFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new ViMergeWeekdayComponentRefiner(),
                new ViMergeDateRangeRefiner(),
                new ViMergeDateTimeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(),
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
                new IsoFormatParser(),
                new SlashDateFormatParser(littleEndian: true),
                new ViStandardParser(),
                new ViMonthYearParser(),
                new ViYearParser(),
                new ViWeekdayParser(),
                new ViTimeExpressionParser(),
                new ViTimeUnitAgoFormatParser(strictMode: true),
                new ViTimeUnitLaterFormatParser(strictMode: true),
                new ViTimeUnitWithinFormatParser(strictMode: true),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new ViMergeWeekdayComponentRefiner(),
                new ViMergeDateRangeRefiner(),
                new ViMergeDateTimeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
