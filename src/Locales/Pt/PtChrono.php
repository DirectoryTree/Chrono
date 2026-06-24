<?php

namespace Chrono\Locales\Pt;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Pt\Parsers\PtCasualDateParser;
use Chrono\Locales\Pt\Parsers\PtCasualTimeParser;
use Chrono\Locales\Pt\Parsers\PtMonthNameLittleEndianParser;
use Chrono\Locales\Pt\Parsers\PtTimeExpressionParser;
use Chrono\Locales\Pt\Parsers\PtWeekdayParser;
use Chrono\Locales\Pt\Refiners\PtMergeDateRangeRefiner;
use Chrono\Locales\Pt\Refiners\PtMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class PtChrono extends ConfiguredChronoEngine
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
