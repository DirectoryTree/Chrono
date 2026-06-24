<?php

namespace Chrono\Locales\Fi;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Fi\Parsers\FiCasualDateParser;
use Chrono\Locales\Fi\Parsers\FiCasualTimeParser;
use Chrono\Locales\Fi\Parsers\FiMonthNameLittleEndianParser;
use Chrono\Locales\Fi\Parsers\FiTimeExpressionParser;
use Chrono\Locales\Fi\Parsers\FiTimeUnitAgoFormatParser;
use Chrono\Locales\Fi\Parsers\FiTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Fi\Parsers\FiTimeUnitWithinFormatParser;
use Chrono\Locales\Fi\Parsers\FiWeekdayParser;
use Chrono\Locales\Fi\Refiners\FiMergeDateRangeRefiner;
use Chrono\Locales\Fi\Refiners\FiMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class FiChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Finnish Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Finnish parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Finnish parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new FiTimeUnitCasualRelativeFormatParser(),
                new FiCasualDateParser(),
                new FiCasualTimeParser(),
                new IsoFormatParser(),
                new SlashDateFormatParser(littleEndian: true),
                new FiTimeExpressionParser(),
                new FiMonthNameLittleEndianParser(),
                new FiWeekdayParser(),
                new FiTimeUnitWithinFormatParser(),
                new FiTimeUnitAgoFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new MergeWeekdayComponentRefiner(),
                new FiMergeDateRangeRefiner(),
                new FiMergeDateTimeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(),
            ],
        );
    }

    /**
     * Create the source-shaped strict Finnish parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser(),
                new SlashDateFormatParser(littleEndian: true),
                new FiTimeExpressionParser(),
                new FiMonthNameLittleEndianParser(),
                new FiWeekdayParser(),
                new FiTimeUnitWithinFormatParser(),
                new FiTimeUnitAgoFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new MergeWeekdayComponentRefiner(),
                new FiMergeDateRangeRefiner(),
                new FiMergeDateTimeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
