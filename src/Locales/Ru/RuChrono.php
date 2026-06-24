<?php

namespace Chrono\Locales\Ru;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Ru\Parsers\RuCasualDateParser;
use Chrono\Locales\Ru\Parsers\RuCasualTimeParser;
use Chrono\Locales\Ru\Parsers\RuMonthNameLittleEndianParser;
use Chrono\Locales\Ru\Parsers\RuMonthNameParser;
use Chrono\Locales\Ru\Parsers\RuRelativeDateFormatParser;
use Chrono\Locales\Ru\Parsers\RuTimeExpressionParser;
use Chrono\Locales\Ru\Parsers\RuTimeUnitAgoFormatParser;
use Chrono\Locales\Ru\Parsers\RuTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Ru\Parsers\RuTimeUnitWithinFormatParser;
use Chrono\Locales\Ru\Parsers\RuWeekdayParser;
use Chrono\Locales\Ru\Refiners\RuMergeDateRangeRefiner;
use Chrono\Locales\Ru\Refiners\RuMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class RuChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Russian Chrono engine.
     */
    public function __construct(?Configuration $configuration = null)
    {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Russian parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Russian parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new RuTimeUnitCasualRelativeFormatParser(),
                new RuRelativeDateFormatParser(),
                new RuMonthNameParser(),
                new RuCasualTimeParser(),
                new RuCasualDateParser(),
                new IsoFormatParser(),
                new SlashDateFormatParser(littleEndian: true),
                new RuTimeUnitWithinFormatParser(),
                new RuMonthNameLittleEndianParser(),
                new RuWeekdayParser(),
                new RuTimeExpressionParser(),
                new RuTimeUnitAgoFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new MergeWeekdayComponentRefiner(),
                new RuMergeDateTimeRefiner(),
                new RuMergeDateRangeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(),
            ],
        );
    }

    /**
     * Create the source-shaped strict Russian parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser(),
                new SlashDateFormatParser(littleEndian: true),
                new RuTimeUnitWithinFormatParser(),
                new RuMonthNameLittleEndianParser(),
                new RuWeekdayParser(),
                new RuTimeExpressionParser(),
                new RuTimeUnitAgoFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new MergeWeekdayComponentRefiner(),
                new RuMergeDateTimeRefiner(),
                new RuMergeDateRangeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
