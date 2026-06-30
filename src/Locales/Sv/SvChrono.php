<?php

namespace DirectoryTree\Chrono\Locales\Sv;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvCasualDateParser;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvWeekdayParser;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class SvChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Swedish Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Swedish parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Swedish parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new SvCasualDateParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new SvMonthNameLittleEndianParser,
                new SvWeekdayParser,
                new SvTimeUnitCasualRelativeFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Swedish parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new SvMonthNameLittleEndianParser,
                new SvWeekdayParser,
                new SvTimeUnitCasualRelativeFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
