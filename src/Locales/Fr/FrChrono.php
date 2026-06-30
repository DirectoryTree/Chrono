<?php

namespace DirectoryTree\Chrono\Locales\Fr;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrCasualDateParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrCasualTimeParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrIsoDateTimeRangeParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrMonthNameParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrSlashDateParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrSpecificTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrTimeExpressionParser as FrCommonTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrTimeUnitRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrWeekdayParser;
use DirectoryTree\Chrono\Locales\Fr\Refiners\FrMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Fr\Refiners\FrMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class FrChrono extends ConfiguredChronoEngine
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
