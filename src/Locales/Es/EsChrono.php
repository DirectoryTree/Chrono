<?php

namespace DirectoryTree\Chrono\Locales\Es;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsCasualDateParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsCasualTimeParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsMonthNameParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsScheduleDateTimeParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsSlashDateParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsTimeExpressionParser as EsCommonTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsWeekdayParser;
use DirectoryTree\Chrono\Locales\Es\Refiners\EsMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Es\Refiners\EsMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class EsChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Spanish Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Spanish parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Spanish parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new EsScheduleDateTimeParser,
                new EsMonthNameLittleEndianParser,
                new EsMonthNameParser,
                new EsSlashDateParser,
                new EsCasualDateParser,
                new EsCasualTimeParser,
                new EsCommonTimeExpressionParser,
                new EsWeekdayParser,
                new EsTimeUnitWithinFormatParser,
                new EsTimeUnitAgoFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new EsMergeDateTimeRefiner,
                new EsMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Spanish parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new EsSlashDateParser,
                new EsWeekdayParser,
                new EsCommonTimeExpressionParser,
                new EsMonthNameLittleEndianParser,
                new EsTimeUnitWithinFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new EsMergeDateTimeRefiner,
                new EsMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
