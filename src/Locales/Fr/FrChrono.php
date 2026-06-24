<?php

namespace Chrono\Locales\Fr;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Fr\Parsers\FrCasualDateParser;
use Chrono\Locales\Fr\Parsers\FrCasualTimeParser;
use Chrono\Locales\Fr\Parsers\FrIsoDateTimeRangeParser;
use Chrono\Locales\Fr\Parsers\FrMonthNameLittleEndianParser;
use Chrono\Locales\Fr\Parsers\FrMonthNameParser;
use Chrono\Locales\Fr\Parsers\FrSlashDateParser;
use Chrono\Locales\Fr\Parsers\FrSpecificTimeExpressionParser;
use Chrono\Locales\Fr\Parsers\FrTimeExpressionParser as FrCommonTimeExpressionParser;
use Chrono\Locales\Fr\Parsers\FrTimeUnitAgoFormatParser;
use Chrono\Locales\Fr\Parsers\FrTimeUnitRelativeFormatParser;
use Chrono\Locales\Fr\Parsers\FrTimeUnitWithinFormatParser;
use Chrono\Locales\Fr\Parsers\FrWeekdayParser;
use Chrono\Locales\Fr\Refiners\FrMergeDateRangeRefiner;
use Chrono\Locales\Fr\Refiners\FrMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class FrChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured French Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict French parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual French parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new FrTimeUnitRelativeFormatParser,
                new FrCasualTimeParser,
                new FrCasualDateParser,
                new FrIsoDateTimeRangeParser,
                new IsoFormatParser,
                new FrSlashDateParser,
                new FrMonthNameLittleEndianParser,
                new FrMonthNameParser,
                new FrCommonTimeExpressionParser,
                new FrSpecificTimeExpressionParser,
                new FrTimeUnitAgoFormatParser,
                new FrTimeUnitWithinFormatParser,
                new FrWeekdayParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new FrMergeDateTimeRefiner,
                new FrMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict French parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new FrSlashDateParser,
                new FrMonthNameLittleEndianParser,
                new FrCommonTimeExpressionParser,
                new FrSpecificTimeExpressionParser,
                new FrTimeUnitAgoFormatParser,
                new FrTimeUnitWithinFormatParser,
                new FrWeekdayParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new FrMergeDateTimeRefiner,
                new FrMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
