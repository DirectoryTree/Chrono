<?php

namespace DirectoryTree\Chrono\Locales\Uk;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkCasualDateParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkCasualTimeParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkMonthNameParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkWeekdayParser;
use DirectoryTree\Chrono\Locales\Uk\Refiners\UkMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Uk\Refiners\UkMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class UkChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Ukrainian Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Ukrainian parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Ukrainian parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new UkTimeUnitCasualRelativeFormatParser,
                new UkRelativeDateFormatParser,
                new UkMonthNameParser,
                new UkCasualTimeParser,
                new UkCasualDateParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new UkTimeUnitWithinFormatParser,
                new UkMonthNameLittleEndianParser,
                new UkWeekdayParser,
                new UkTimeExpressionParser,
                new UkTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new UkMergeDateTimeRefiner,
                new UkMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Ukrainian parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new UkTimeUnitWithinFormatParser,
                new UkMonthNameLittleEndianParser,
                new UkWeekdayParser,
                new UkTimeExpressionParser,
                new UkTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new UkMergeDateTimeRefiner,
                new UkMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
