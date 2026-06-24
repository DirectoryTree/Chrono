<?php

namespace Chrono\Locales\De;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\De\Parsers\DeCasualDateParser;
use Chrono\Locales\De\Parsers\DeCasualTimeParser;
use Chrono\Locales\De\Parsers\DeDashDateParser;
use Chrono\Locales\De\Parsers\DeMonthNameLittleEndianParser;
use Chrono\Locales\De\Parsers\DeMonthNameParser;
use Chrono\Locales\De\Parsers\DeSpecificTimeExpressionParser;
use Chrono\Locales\De\Parsers\DeTimeExpressionExtensionParser;
use Chrono\Locales\De\Parsers\DeTimeExpressionParser as DeCommonTimeExpressionParser;
use Chrono\Locales\De\Parsers\DeTimeUnitRelativeFormatParser;
use Chrono\Locales\De\Parsers\DeTimeUnitWithinFormatParser;
use Chrono\Locales\De\Parsers\DeWeekdayParser;
use Chrono\Locales\De\Refiners\DeMergeDateRangeRefiner;
use Chrono\Locales\De\Refiners\DeMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class DeChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured German Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict German parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual German parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new DeTimeUnitRelativeFormatParser,
                new DeCasualDateParser,
                new DeCasualTimeParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new DeMonthNameLittleEndianParser,
                new DeMonthNameParser,
                new DeDashDateParser,
                new DeSpecificTimeExpressionParser,
                new DeCommonTimeExpressionParser,
                new DeTimeExpressionExtensionParser,
                new DeWeekdayParser,
                new DeTimeUnitWithinFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new DeMergeDateRangeRefiner,
                new DeMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict German parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new DeCommonTimeExpressionParser,
                new DeSpecificTimeExpressionParser,
                new DeMonthNameLittleEndianParser,
                new DeWeekdayParser,
                new DeTimeUnitWithinFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new DeMergeDateRangeRefiner,
                new DeMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
