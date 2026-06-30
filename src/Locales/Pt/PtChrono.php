<?php

namespace DirectoryTree\Chrono\Locales\Pt;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtCasualDateParser;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtCasualTimeParser;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtWeekdayParser;
use DirectoryTree\Chrono\Locales\Pt\Refiners\PtMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Pt\Refiners\PtMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class PtChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Portuguese Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Portuguese parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Portuguese parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new PtWeekdayParser,
                new PtTimeExpressionParser,
                new PtMonthNameLittleEndianParser,
                new PtCasualDateParser,
                new PtCasualTimeParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new PtMergeDateTimeRefiner,
                new PtMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Portuguese parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new PtWeekdayParser,
                new PtTimeExpressionParser,
                new PtMonthNameLittleEndianParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new PtMergeDateTimeRefiner,
                new PtMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
