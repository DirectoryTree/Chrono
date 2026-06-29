<?php

namespace Chrono\Locales\Uk;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Uk\Parsers\UkCasualDateParser;
use Chrono\Locales\Uk\Parsers\UkCasualTimeParser;
use Chrono\Locales\Uk\Parsers\UkMonthNameLittleEndianParser;
use Chrono\Locales\Uk\Parsers\UkMonthNameParser;
use Chrono\Locales\Uk\Parsers\UkRelativeDateFormatParser;
use Chrono\Locales\Uk\Parsers\UkTimeExpressionParser;
use Chrono\Locales\Uk\Parsers\UkTimeUnitAgoFormatParser;
use Chrono\Locales\Uk\Parsers\UkTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Uk\Parsers\UkTimeUnitWithinFormatParser;
use Chrono\Locales\Uk\Parsers\UkWeekdayParser;
use Chrono\Locales\Uk\Refiners\UkMergeDateRangeRefiner;
use Chrono\Locales\Uk\Refiners\UkMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

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
