<?php

namespace DirectoryTree\Chrono\Locales\Nl;

use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualDateParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualDateTimeParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualTimeParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualYearMonthDayParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlMonthNameMiddleEndianParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlMonthNameParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlSlashMonthFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlTimeExpressionParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlTimeUnitAgoFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlTimeUnitLaterFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlTimeUnitWithinFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlWeekdayParser;
use DirectoryTree\Chrono\Locales\Nl\Refiners\NlMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Nl\Refiners\NlMergeDateTimeRefiner;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Refiners\UnlikelyFormatFilter;

readonly class NlChrono extends ConfiguredChronoEngine
{
    /**
     * Create a configured Dutch Chrono engine.
     */
    public function __construct(
        ?Configuration $configuration = null,
    ) {
        parent::__construct($configuration ?? self::createCasualConfiguration());
    }

    /**
     * Create the strict Dutch parser.
     */
    public static function strict(): self
    {
        return new self(self::createStrictConfiguration());
    }

    /**
     * Create the casual Dutch parser and refiner configuration.
     */
    public static function createCasualConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new NlTimeUnitCasualRelativeFormatParser,
                new NlRelativeDateFormatParser,
                new NlMonthNameParser,
                new NlCasualDateTimeParser,
                new NlCasualTimeParser,
                new NlCasualDateParser,
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new NlTimeUnitWithinFormatParser,
                new NlMonthNameMiddleEndianParser,
                new NlMonthNameParser,
                new NlWeekdayParser,
                new NlCasualYearMonthDayParser,
                new NlSlashMonthFormatParser,
                new NlTimeExpressionParser,
                new NlTimeUnitAgoFormatParser,
                new NlTimeUnitLaterFormatParser,
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new NlMergeDateTimeRefiner,
                new NlMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter,
            ],
        );
    }

    /**
     * Create the source-shaped strict Dutch parser and refiner configuration.
     */
    public static function createStrictConfiguration(): Configuration
    {
        return new Configuration(
            parsers: [
                new IsoFormatParser,
                new SlashDateFormatParser(littleEndian: true),
                new NlTimeUnitWithinFormatParser,
                new NlMonthNameMiddleEndianParser,
                new NlMonthNameParser,
                new NlWeekdayParser,
                new NlCasualYearMonthDayParser,
                new NlSlashMonthFormatParser,
                new NlTimeExpressionParser,
                new NlTimeUnitAgoFormatParser(strictMode: true),
                new NlTimeUnitLaterFormatParser(strictMode: true),
            ],
            refiners: [
                new OverlapRemovalRefiner,
                new ExtractTimezoneOffsetRefiner,
                new MergeWeekdayComponentRefiner,
                new NlMergeDateTimeRefiner,
                new NlMergeDateRangeRefiner,
                new ExtractTimezoneAbbrRefiner,
                new OverlapRemovalRefiner,
                new ForwardDateRefiner,
                new UnlikelyFormatFilter(strictMode: true),
            ],
        );
    }
}
