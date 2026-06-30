<?php

namespace DirectoryTree\Chrono\Locales\Zh;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansCasualDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansDeadlineFormatParser;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansRelationWeekdayParser;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansWeekdayParser;
use DirectoryTree\Chrono\Locales\Zh\Refiners\ZhMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Zh\Refiners\ZhMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class ZhHansChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured simplified Chinese Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict simplified Chinese parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual simplified Chinese parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new ZhHansCasualDateParser,
                new IsoFormatParser,
                new ZhHansDateParser,
                new ZhHansRelationWeekdayParser,
                new ZhHansWeekdayParser,
                new ZhHansTimeExpressionParser,
                new ZhHansDeadlineFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new MergeWeekdayComponentRefiner,
                new ZhMergeDateRangeRefiner,
                new ZhMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict simplified Chinese parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new ZhHansDateParser,
                new ZhHansRelationWeekdayParser,
                new ZhHansWeekdayParser,
                new ZhHansTimeExpressionParser,
                new ZhHansDeadlineFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new MergeWeekdayComponentRefiner,
                new ZhMergeDateRangeRefiner,
                new ZhMergeDateTimeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
