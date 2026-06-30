<?php

namespace DirectoryTree\Chrono\Locales\De;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\De\Parsers\DeCasualDateParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeCasualTimeParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeDashDateParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeMonthNameParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeSpecificTimeExpressionParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeTimeExpressionExtensionParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeTimeExpressionParser as DeCommonTimeExpressionParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeTimeUnitRelativeFormatParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeWeekdayParser;
use DirectoryTree\Chrono\Locales\De\Refiners\DeMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\De\Refiners\DeMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class DeChrono extends ConfiguredChronoEngine
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
