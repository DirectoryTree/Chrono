<?php

namespace Chrono\Locales\Nl;

use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Locales\Nl\Parsers\NlCasualDateParser;
use Chrono\Locales\Nl\Parsers\NlCasualDateTimeParser;
use Chrono\Locales\Nl\Parsers\NlCasualTimeParser;
use Chrono\Locales\Nl\Parsers\NlCasualYearMonthDayParser;
use Chrono\Locales\Nl\Parsers\NlMonthNameMiddleEndianParser;
use Chrono\Locales\Nl\Parsers\NlMonthNameParser;
use Chrono\Locales\Nl\Parsers\NlRelativeDateFormatParser;
use Chrono\Locales\Nl\Parsers\NlSlashMonthFormatParser;
use Chrono\Locales\Nl\Parsers\NlTimeExpressionParser;
use Chrono\Locales\Nl\Parsers\NlTimeUnitAgoFormatParser;
use Chrono\Locales\Nl\Parsers\NlTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Nl\Parsers\NlTimeUnitLaterFormatParser;
use Chrono\Locales\Nl\Parsers\NlTimeUnitWithinFormatParser;
use Chrono\Locales\Nl\Parsers\NlWeekdayParser;
use Chrono\Locales\Nl\Refiners\NlMergeDateRangeRefiner;
use Chrono\Locales\Nl\Refiners\NlMergeDateTimeRefiner;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Refiners\UnlikelyFormatFilter;

class NlChrono extends ConfiguredChronoEngine
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
