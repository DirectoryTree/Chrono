<?php

namespace DirectoryTree\Chrono\Locales\Ru;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuCasualDateParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuCasualTimeParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuMonthNameParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuWeekdayParser;
use DirectoryTree\Chrono\Locales\Ru\Refiners\RuMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Ru\Refiners\RuMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class RuChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Russian Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
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
                new RuTimeUnitCasualRelativeFormatParser,
                new RuRelativeDateFormatParser,
                new RuMonthNameParser,
                new RuCasualTimeParser,
                new RuCasualDateParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new RuTimeUnitWithinFormatParser,
                new RuMonthNameLittleEndianParser,
                new RuWeekdayParser,
                new RuTimeExpressionParser,
                new RuTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new RuMergeDateTimeRefiner,
                new RuMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
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
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new RuTimeUnitWithinFormatParser,
                new RuMonthNameLittleEndianParser,
                new RuWeekdayParser,
                new RuTimeExpressionParser,
                new RuTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new RuMergeDateTimeRefiner,
                new RuMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
