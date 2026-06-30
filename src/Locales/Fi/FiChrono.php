<?php

namespace DirectoryTree\Chrono\Locales\Fi;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiCasualDateParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiCasualTimeParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiWeekdayParser;
use DirectoryTree\Chrono\Locales\Fi\Refiners\FiMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Fi\Refiners\FiMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class FiChrono extends ConfiguredChronoEngine
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
                new FiTimeUnitCasualRelativeFormatParser,
                new FiCasualDateParser,
                new FiCasualTimeParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new FiTimeExpressionParser,
                new FiMonthNameLittleEndianParser,
                new FiWeekdayParser,
                new FiTimeUnitWithinFormatParser,
                new FiTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new FiMergeDateRangeRefiner,
                new FiMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
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
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new FiTimeExpressionParser,
                new FiMonthNameLittleEndianParser,
                new FiWeekdayParser,
                new FiTimeUnitWithinFormatParser,
                new FiTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new FiMergeDateRangeRefiner,
                new FiMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
