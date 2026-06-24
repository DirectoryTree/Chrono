<?php

namespace Chrono\Locales\Zh;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansDateParser;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansDeadlineFormatParser;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansRelationWeekdayParser;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansTimeExpressionParser;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansWeekdayParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantCasualDateParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantDateParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantDeadlineFormatParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantRelationWeekdayParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantTimeExpressionParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantWeekdayParser;
use Chrono\Locales\Zh\Refiners\ZhMergeDateRangeRefiner;
use Chrono\Locales\Zh\Refiners\ZhMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class ZhChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Chinese Chrono engine.
     */
    public function __construct(?Configuration $configuration = null)
    {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Chinese parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Chinese parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new ZhHantCasualDateParser(),
                new IsoFormatParser(),
                new ZhHantDateParser(),
                new ZhHansDateParser(),
                new ZhHantRelationWeekdayParser(),
                new ZhHansRelationWeekdayParser(),
                new ZhHantWeekdayParser(),
                new ZhHansWeekdayParser(),
                new ZhHantTimeExpressionParser(),
                new ZhHansTimeExpressionParser(),
                new ZhHantDeadlineFormatParser(),
                new ZhHansDeadlineFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new MergeWeekdayComponentRefiner(),
                new ZhMergeDateRangeRefiner(),
                new ZhMergeDateTimeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(),
            ],
        );
    }

    /**
     * Create the source-shaped strict Chinese parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser(),
                new ZhHantDateParser(),
                new ZhHansDateParser(),
                new ZhHantRelationWeekdayParser(),
                new ZhHansRelationWeekdayParser(),
                new ZhHantWeekdayParser(),
                new ZhHansWeekdayParser(),
                new ZhHantTimeExpressionParser(),
                new ZhHansTimeExpressionParser(),
                new ZhHantDeadlineFormatParser(),
                new ZhHansDeadlineFormatParser(),
            ],
            refiners: [
                new OverlapRemovalRefiner(),
                new MergeWeekdayComponentRefiner(),
                new ZhMergeDateRangeRefiner(),
                new ZhMergeDateTimeRefiner(),
                new ExtractTimezoneAbbrRefiner(),
                new OverlapRemovalRefiner(),
                new ForwardDateRefiner(),
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
