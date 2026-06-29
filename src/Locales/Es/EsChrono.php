<?php

namespace Chrono\Locales\Es;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Es\Parsers\EsCasualDateParser;
use Chrono\Locales\Es\Parsers\EsCasualTimeParser;
use Chrono\Locales\Es\Parsers\EsMonthNameLittleEndianParser;
use Chrono\Locales\Es\Parsers\EsMonthNameParser;
use Chrono\Locales\Es\Parsers\EsScheduleDateTimeParser;
use Chrono\Locales\Es\Parsers\EsSlashDateParser;
use Chrono\Locales\Es\Parsers\EsTimeExpressionParser as EsCommonTimeExpressionParser;
use Chrono\Locales\Es\Parsers\EsTimeUnitAgoFormatParser;
use Chrono\Locales\Es\Parsers\EsTimeUnitWithinFormatParser;
use Chrono\Locales\Es\Parsers\EsWeekdayParser;
use Chrono\Locales\Es\Refiners\EsMergeDateRangeRefiner;
use Chrono\Locales\Es\Refiners\EsMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

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
