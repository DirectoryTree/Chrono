<?php

namespace DirectoryTree\Chrono\Locales\Zh;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantCasualDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantDeadlineFormatParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantRelationWeekdayParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantWeekdayParser;
use DirectoryTree\Chrono\Locales\Zh\Refiners\ZhMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Zh\Refiners\ZhMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class ZhHantChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured traditional Chinese Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict traditional Chinese parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual traditional Chinese parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new ZhHantCasualDateParser,
                new IsoFormatParser,
                new ZhHantDateParser,
                new ZhHantRelationWeekdayParser,
                new ZhHantWeekdayParser,
                new ZhHantTimeExpressionParser,
                new ZhHantDeadlineFormatParser,
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
     * Create the source-shaped strict traditional Chinese parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new ZhHantDateParser,
                new ZhHantRelationWeekdayParser,
                new ZhHantWeekdayParser,
                new ZhHantTimeExpressionParser,
                new ZhHantDeadlineFormatParser,
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
