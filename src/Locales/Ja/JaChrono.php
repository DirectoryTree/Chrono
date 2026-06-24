<?php

namespace Chrono\Locales\Ja;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Ja\Parsers\JaCasualDateParser;
use Chrono\Locales\Ja\Parsers\JaSlashDateFormatParser;
use Chrono\Locales\Ja\Parsers\JaStandardParser;
use Chrono\Locales\Ja\Parsers\JaTimeExpressionParser;
use Chrono\Locales\Ja\Parsers\JaWeekdayParser;
use Chrono\Locales\Ja\Parsers\JaWeekdayWithParenthesesParser;
use Chrono\Locales\Ja\Refiners\JaMergeDateRangeRefiner;
use Chrono\Locales\Ja\Refiners\JaMergeDateTimeRefiner;
use Chrono\Locales\Ja\Refiners\JaMergeWeekdayComponentRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class JaChrono extends ConfiguredChronoEngine
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
                new JaCasualDateParser(),
                new IsoFormatParser(),
                new JaStandardParser(),
                new JaWeekdayParser(),
                new JaWeekdayWithParenthesesParser(),
                new JaSlashDateFormatParser(),
                new JaTimeExpressionParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new JaMergeWeekdayComponentRefiner(),
                new JaMergeDateTimeRefiner(),
                new JaMergeDateRangeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(),
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
                new IsoFormatParser(),
                new JaStandardParser(),
                new JaWeekdayParser(),
                new JaWeekdayWithParenthesesParser(),
                new JaSlashDateFormatParser(),
                new JaTimeExpressionParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new ExtractTimezoneOffsetRefiner(),
                new JaMergeWeekdayComponentRefiner(),
                new JaMergeDateTimeRefiner(),
                new JaMergeDateRangeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
