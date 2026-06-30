<?php

namespace DirectoryTree\Chrono\Locales\Ja;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaCasualDateParser;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaSlashDateFormatParser;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaStandardParser;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaWeekdayParser;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaWeekdayWithParenthesesParser;
use DirectoryTree\Chrono\Locales\Ja\Refiners\JaMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Ja\Refiners\JaMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\Ja\Refiners\JaMergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class JaChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Japanese Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Japanese parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Japanese parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new JaCasualDateParser,
                new IsoFormatParser,
                new JaStandardParser,
                new JaWeekdayParser,
                new JaWeekdayWithParenthesesParser,
                new JaSlashDateFormatParser,
                new JaTimeExpressionParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new JaMergeWeekdayComponentRefiner,
                new JaMergeDateTimeRefiner,
                new JaMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Japanese parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new JaStandardParser,
                new JaWeekdayParser,
                new JaWeekdayWithParenthesesParser,
                new JaSlashDateFormatParser,
                new JaTimeExpressionParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new JaMergeWeekdayComponentRefiner,
                new JaMergeDateTimeRefiner,
                new JaMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
