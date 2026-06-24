<?php

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Chrono\BufferedDebugHandler;
use Chrono\Calculation\Duration;
use Chrono\Calculation\MergingCalculation;
use Chrono\Calculation\Weekdays;
use Chrono\Calculation\Years;
use Chrono\CasualReferences;
use Chrono\Chrono;
use Chrono\Configuration;
use Chrono\ConfiguredChronoEngine;
use Chrono\Dates;
use Chrono\Locales\De\DeChrono;
use Chrono\Locales\De\Parsers\DeCasualDateParser;
use Chrono\Locales\De\Parsers\DeCasualTimeParser;
use Chrono\Locales\De\Parsers\DeMonthNameLittleEndianParser;
use Chrono\Locales\De\Parsers\DeMonthNameParser;
use Chrono\Locales\De\Parsers\DeTimeUnitRelativeFormatParser;
use Chrono\Locales\De\Refiners\DeMergeDateRangeRefiner;
use Chrono\Locales\De\Refiners\DeMergeDateTimeRefiner;
use Chrono\Locales\En\EnChrono;
use Chrono\Locales\En\Parsers\EnSlashDateParser;
use Chrono\Locales\En\Parsers\EnTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\En\Refiners\EnExtractYearSuffixRefiner;
use Chrono\Locales\En\Refiners\EnMergeDateRangeRefiner;
use Chrono\Locales\En\Refiners\EnMergeDateTimeRefiner;
use Chrono\Locales\En\Refiners\EnMergeRelativeAfterDateRefiner;
use Chrono\Locales\En\Refiners\EnMergeRelativeFollowByDateRefiner;
use Chrono\Locales\Es\EsChrono;
use Chrono\Locales\Es\Parsers\EsMonthNameLittleEndianParser;
use Chrono\Locales\Es\Parsers\EsMonthNameParser;
use Chrono\Locales\Fi\FiChrono;
use Chrono\Locales\Fi\Parsers\FiCasualDateParser;
use Chrono\Locales\Fi\Parsers\FiCasualTimeParser;
use Chrono\Locales\Fi\Parsers\FiMonthNameLittleEndianParser;
use Chrono\Locales\Fi\Parsers\FiTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Fi\Refiners\FiMergeDateRangeRefiner;
use Chrono\Locales\Fi\Refiners\FiMergeDateTimeRefiner;
use Chrono\Locales\Fr\FrChrono;
use Chrono\Locales\Fr\Parsers\FrCasualDateParser;
use Chrono\Locales\Fr\Parsers\FrCasualTimeParser;
use Chrono\Locales\Fr\Parsers\FrMonthNameLittleEndianParser;
use Chrono\Locales\Fr\Parsers\FrMonthNameParser;
use Chrono\Locales\Fr\Parsers\FrTimeUnitRelativeFormatParser;
use Chrono\Locales\Fr\Refiners\FrMergeDateRangeRefiner;
use Chrono\Locales\Fr\Refiners\FrMergeDateTimeRefiner;
use Chrono\Locales\It\ItChrono;
use Chrono\Locales\It\Parsers\ItCasualDateParser;
use Chrono\Locales\It\Parsers\ItCasualTimeParser;
use Chrono\Locales\It\Parsers\ItMonthNameLittleEndianParser;
use Chrono\Locales\It\Parsers\ItMonthNameParser;
use Chrono\Locales\It\Parsers\ItRelativeDateFormatParser;
use Chrono\Locales\It\Parsers\ItTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Ja\JaChrono;
use Chrono\Locales\Ja\Parsers\JaCasualDateParser;
use Chrono\Locales\Ja\Parsers\JaStandardParser;
use Chrono\Locales\Ja\Refiners\JaMergeWeekdayComponentRefiner;
use Chrono\Locales\Nl\NlChrono;
use Chrono\Locales\Nl\Parsers\NlCasualDateParser;
use Chrono\Locales\Nl\Parsers\NlCasualDateTimeParser;
use Chrono\Locales\Nl\Parsers\NlCasualTimeParser;
use Chrono\Locales\Nl\Parsers\NlMonthNameParser;
use Chrono\Locales\Nl\Parsers\NlRelativeDateFormatParser;
use Chrono\Locales\Nl\Parsers\NlTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Pt\Parsers\PtCasualDateParser;
use Chrono\Locales\Pt\Parsers\PtMonthNameLittleEndianParser;
use Chrono\Locales\Pt\PtChrono;
use Chrono\Locales\Ru\Parsers\RuCasualDateParser;
use Chrono\Locales\Ru\Parsers\RuCasualTimeParser;
use Chrono\Locales\Ru\Parsers\RuMonthNameLittleEndianParser;
use Chrono\Locales\Ru\Parsers\RuMonthNameParser;
use Chrono\Locales\Ru\Parsers\RuRelativeDateFormatParser;
use Chrono\Locales\Ru\Parsers\RuTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Ru\RuChrono;
use Chrono\Locales\Sv\Parsers\SvCasualDateParser;
use Chrono\Locales\Sv\Parsers\SvMonthNameLittleEndianParser;
use Chrono\Locales\Sv\Parsers\SvTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Sv\SvChrono;
use Chrono\Locales\Uk\Parsers\UkCasualDateParser;
use Chrono\Locales\Uk\Parsers\UkCasualTimeParser;
use Chrono\Locales\Uk\Parsers\UkMonthNameLittleEndianParser;
use Chrono\Locales\Uk\Parsers\UkMonthNameParser;
use Chrono\Locales\Uk\Parsers\UkRelativeDateFormatParser;
use Chrono\Locales\Uk\Parsers\UkTimeUnitCasualRelativeFormatParser;
use Chrono\Locales\Uk\UkChrono;
use Chrono\Locales\Vi\Parsers\ViCasualDateParser;
use Chrono\Locales\Vi\Parsers\ViStandardParser;
use Chrono\Locales\Vi\ViChrono;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansCasualDateParser;
use Chrono\Locales\Zh\Hans\Parsers\ZhHansDateParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantCasualDateParser;
use Chrono\Locales\Zh\Hant\Parsers\ZhHantDateParser;
use Chrono\Locales\Zh\ZhChrono;
use Chrono\Locales\Zh\ZhHansChrono;
use Chrono\Locales\Zh\ZhHantChrono;
use Chrono\Meridiem;
use Chrono\Month;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Parsers\AbstractParserWithWordBoundary;
use Chrono\Parsers\IsoFormatParser;
use Chrono\Parsers\SlashDateFormatParser;
use Chrono\Pattern;
use Chrono\Reference;
use Chrono\Refiner;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Refiners\MergeWeekdayComponentRefiner;
use Chrono\Refiners\OverlapRemovalRefiner;
use Chrono\Timezone;
use Chrono\Weekday;

it('buffers debug callbacks until explicitly executed', function () {
    $debugHandler = new BufferedDebugHandler;
    $calls = [];

    $debugHandler->debug(function () use (&$calls) {
        $calls[] = 'first';

        return 'a';
    });

    $debugHandler->debug(function () use (&$calls) {
        $calls[] = 'second';

        return 'b';
    });

    expect($calls)->toBe([])
        ->and($debugHandler->executeBufferedBlocks())->toBe(['a', 'b'])
        ->and($calls)->toBe(['first', 'second'])
        ->and($debugHandler->executeBufferedBlocks())->toBe([]);
});

it('exposes upstream enum values', function () {
    expect(Meridiem::AM->value)->toBe(0)
        ->and(Meridiem::PM->value)->toBe(1)
        ->and(Weekday::SUNDAY->value)->toBe(0)
        ->and(Weekday::MONDAY->value)->toBe(1)
        ->and(Weekday::SATURDAY->value)->toBe(6)
        ->and(Month::JANUARY->value)->toBe(1)
        ->and(Month::DECEMBER->value)->toBe(12);
});

it('exposes source-shaped strict locale configurations separately from PHP extensions', function () {
    $spanishParsers = array_map(fn (object $parser): string => $parser::class, EsChrono::createStrictConfiguration()->parsers);
    $spanishRefiners = array_map(fn (object $refiner): string => $refiner::class, EsChrono::createStrictConfiguration()->refiners);
    $germanParsers = array_map(fn (object $parser): string => $parser::class, DeChrono::createStrictConfiguration()->parsers);
    $germanRefiners = array_map(fn (object $refiner): string => $refiner::class, DeChrono::createStrictConfiguration()->refiners);
    $frenchParsers = array_map(fn (object $parser): string => $parser::class, FrChrono::createStrictConfiguration()->parsers);
    $frenchRefiners = array_map(fn (object $refiner): string => $refiner::class, FrChrono::createStrictConfiguration()->refiners);
    $resultTags = function (array $results): array {
        return array_values(array_unique(array_merge(...array_map(
            fn (ParsedResult $result): array => [
                ...$result->tags(),
                ...$result->start->tags(),
                ...($result->end?->tags() ?? []),
            ],
            $results,
        ))));
    };

    expect($spanishParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(EsMonthNameLittleEndianParser::class)
        ->not->toContain(EsMonthNameParser::class)
        ->and(array_slice($spanishRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($spanishRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($germanParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(DeMonthNameLittleEndianParser::class)
        ->not->toContain(DeMonthNameParser::class)
        ->and(array_slice($germanRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($germanRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($frenchParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(FrMonthNameLittleEndianParser::class)
        ->not->toContain(FrMonthNameParser::class)
        ->and(array_slice($frenchRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($frenchRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($frenchRefiners[array_search(ExtractTimezoneAbbrRefiner::class, $frenchRefiners, true) + 1])
        ->toBe(OverlapRemovalRefiner::class)
        ->and(Chrono::strictSpanish()->parseDateText('10 Agosto 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictSpanish()->parseText('viernes', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictSpanish()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and($resultTags(Chrono::strictSpanish()->parseText('Dom 15Sep', '2013-08-10')))
        ->not->toContain('parser/ESMonthNameParser')
        ->and(Chrono::strictGerman()->parseDateText('10. August 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($resultTags(Chrono::strictGerman()->parseText('Mo 10. August 2012', '2012-08-10')))
        ->not->toContain('parser/DEMonthNameParser')
        ->and(Chrono::strictGerman()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and(Chrono::strictFrench()->parseDateText('10 août 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictFrench()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and($resultTags(Chrono::strictFrench()->parseText('lundi 10 août 2012', '2012-08-10')))
        ->not->toContain('parser/FRMonthNameParser');
});

it('exposes source-shaped English refiner ordering around common configuration', function () {
    $refiners = array_map(fn (object $refiner): string => $refiner::class, EnChrono::createStrictConfiguration()->refiners);
    $timezoneAbbrIndexes = array_keys($refiners, ExtractTimezoneAbbrRefiner::class, true);
    $lateTimezoneAbbrIndex = end($timezoneAbbrIndexes);

    expect(array_slice($refiners, 0, 6))->toBe([
        OverlapRemovalRefiner::class,
        EnMergeRelativeFollowByDateRefiner::class,
        EnMergeRelativeAfterDateRefiner::class,
        OverlapRemovalRefiner::class,
        ExtractTimezoneOffsetRefiner::class,
        MergeWeekdayComponentRefiner::class,
    ])
        ->and($timezoneAbbrIndexes)
        ->toHaveCount(2)
        ->and($refiners[$lateTimezoneAbbrIndex + 1])
        ->toBe(OverlapRemovalRefiner::class)
        ->and(array_slice($refiners, -3))->toBe([
            EnMergeDateTimeRefiner::class,
            EnExtractYearSuffixRefiner::class,
            EnMergeDateRangeRefiner::class,
        ]);
});

it('exposes source-shaped strict configurations for Finnish Portuguese and Swedish', function () {
    $finnishParsers = array_map(fn (object $parser): string => $parser::class, FiChrono::createStrictConfiguration()->parsers);
    $finnishRefiners = array_map(fn (object $refiner): string => $refiner::class, FiChrono::createStrictConfiguration()->refiners);
    $portugueseParsers = array_map(fn (object $parser): string => $parser::class, PtChrono::createStrictConfiguration()->parsers);
    $portugueseRefiners = array_map(fn (object $refiner): string => $refiner::class, PtChrono::createStrictConfiguration()->refiners);
    $swedishParsers = array_map(fn (object $parser): string => $parser::class, SvChrono::createStrictConfiguration()->parsers);
    $swedishRefiners = array_map(fn (object $refiner): string => $refiner::class, SvChrono::createStrictConfiguration()->refiners);

    expect($finnishParsers)
        ->toContain(FiMonthNameLittleEndianParser::class)
        ->not->toContain(FiCasualDateParser::class)
        ->not->toContain(FiTimeUnitCasualRelativeFormatParser::class)
        ->and(array_slice($finnishRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($finnishRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and(array_search(FiMergeDateRangeRefiner::class, $finnishRefiners, true))
        ->toBeLessThan(array_search(FiMergeDateTimeRefiner::class, $finnishRefiners, true))
        ->and($portugueseParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(PtMonthNameLittleEndianParser::class)
        ->not->toContain(PtCasualDateParser::class)
        ->and(array_slice($portugueseRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($portugueseRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($swedishParsers)
        ->toContain(SvMonthNameLittleEndianParser::class)
        ->toContain(SvTimeUnitCasualRelativeFormatParser::class)
        ->not->toContain(SvCasualDateParser::class)
        ->and(array_slice($swedishRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($swedishRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and(Chrono::strictFinnish()->parseDateText('3 tammikuuta 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-01-03 12:00:00')
        ->and(Chrono::strictFinnish()->parseText('tänään', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictPortuguese()->parseDateText('10 Agosto 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictPortuguese()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and(Chrono::strictPortuguese()->parseText('hoje', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictSwedish()->parseDateText('10 augusti 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictSwedish()->parseText('idag', '2012-08-10'))
        ->toBe([]);
});

it('exposes source-shaped strict configurations for remaining locale engines', function () {
    $italianParsers = array_map(fn (object $parser): string => $parser::class, ItChrono::createStrictConfiguration()->parsers);
    $italianRefiners = array_map(fn (object $refiner): string => $refiner::class, ItChrono::createStrictConfiguration()->refiners);
    $dutchParsers = array_map(fn (object $parser): string => $parser::class, NlChrono::createStrictConfiguration()->parsers);
    $dutchRefiners = array_map(fn (object $refiner): string => $refiner::class, NlChrono::createStrictConfiguration()->refiners);
    $russianParsers = array_map(fn (object $parser): string => $parser::class, RuChrono::createStrictConfiguration()->parsers);
    $russianRefiners = array_map(fn (object $refiner): string => $refiner::class, RuChrono::createStrictConfiguration()->refiners);
    $ukrainianParsers = array_map(fn (object $parser): string => $parser::class, UkChrono::createStrictConfiguration()->parsers);
    $ukrainianRefiners = array_map(fn (object $refiner): string => $refiner::class, UkChrono::createStrictConfiguration()->refiners);
    $japaneseParsers = array_map(fn (object $parser): string => $parser::class, JaChrono::createStrictConfiguration()->parsers);
    $japaneseRefiners = array_map(fn (object $refiner): string => $refiner::class, JaChrono::createStrictConfiguration()->refiners);
    $vietnameseParsers = array_map(fn (object $parser): string => $parser::class, ViChrono::createStrictConfiguration()->parsers);
    $vietnameseRefiners = array_map(fn (object $refiner): string => $refiner::class, ViChrono::createStrictConfiguration()->refiners);

    expect($italianParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(SlashDateFormatParser::class)
        ->toContain(ItMonthNameLittleEndianParser::class)
        ->not->toContain(ItCasualDateParser::class)
        ->and(array_slice($italianRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($italianRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($dutchParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(NlMonthNameParser::class)
        ->not->toContain(NlCasualDateParser::class)
        ->and(array_slice($dutchRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($dutchRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($russianParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(RuMonthNameLittleEndianParser::class)
        ->not->toContain(RuCasualDateParser::class)
        ->and(array_slice($russianRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($russianRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($ukrainianParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(UkMonthNameLittleEndianParser::class)
        ->not->toContain(UkCasualDateParser::class)
        ->and(array_slice($ukrainianRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])
        ->and($ukrainianRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($japaneseParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(JaStandardParser::class)
        ->not->toContain(JaCasualDateParser::class)
        ->and($japaneseRefiners)
        ->toContain(ExtractTimezoneOffsetRefiner::class)
        ->toContain(JaMergeWeekdayComponentRefiner::class)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->not->toContain(MergeWeekdayComponentRefiner::class)
        ->and($vietnameseParsers)
        ->toContain(ViStandardParser::class)
        ->not->toContain(ViCasualDateParser::class)
        ->and(array_slice($vietnameseRefiners, 0, 2))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
        ])
        ->and($vietnameseRefiners)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->and($vietnameseRefiners[array_search(ExtractTimezoneAbbrRefiner::class, $vietnameseRefiners, true) + 1])
        ->toBe(OverlapRemovalRefiner::class)
        ->and(Chrono::strictItalian()->parseDateText('10 agosto 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictItalian()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and(Chrono::strictDutch()->parseDateText('10 augustus 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictRussian()->parseDateText('10 августа 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictRussian()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and(Chrono::strictUkrainian()->parseDateText('10 серпня 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictJapanese()->parseDateText('2012年8月10日', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictJapanese()->parseText('2015-05-25', '2012-08-10')[0]->start->tags())
        ->toContain('parser/ISOFormatParser')
        ->and(Chrono::strictVietnamese()->parseDateText('ngày 30 tháng 4 năm 1975', '2012-08-10')?->toDateTimeString())
        ->toBe('1975-04-30 12:00:00')
        ->and(Chrono::strictVietnamese()->parseText('hôm nay', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictVietnamese()->parseText('buổi sáng', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictVietnamese()->parseText('tuần này', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictVietnamese()->parseText('thứ hai', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictVietnamese()->parseText('chủ nhật', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictVietnamese()->parseDateText('lúc 7 giờ 30 phút', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 07:30:00')
        ->and(Chrono::strictVietnamese()->parseDateText('30/4/1975', '2012-08-10')?->toDateTimeString())
        ->toBe('1975-04-30 12:00:00')
        ->and(Chrono::strictVietnamese()->parseDateText('3 ngày trước', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-07 00:00:00')
        ->and(Chrono::strictVietnamese()->parseDateText('2 tuần sau', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-24 00:00:00');
});

it('merges time followed by date in common date-time refiners', function () {
    $german = Chrono::german()->parseText('um 5 Uhr am 10. August 2012', '2012-08-10')[0];
    $dutch = Chrono::dutch()->parseText('om 5 uur 10 augustus 2012', '2012-08-10')[0];

    expect($german->text)
        ->toBe('um 5 Uhr am 10. August 2012')
        ->and($german->index)->toBe(0)
        ->and($german->date()->toDateTimeString())->toBe('2012-08-10 05:00:00')
        ->and($german->tags())->toContain('refiner/mergeTimeFollowedByDate')
        ->and($dutch->text)->toBe('om 5 uur 10 augustus 2012')
        ->and($dutch->index)->toBe(0)
        ->and($dutch->date()->toDateTimeString())->toBe('2012-08-10 05:00:00')
        ->and($dutch->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('exposes source-shaped strict configurations for Chinese engines', function () {
    $chineseParsers = array_map(fn (object $parser): string => $parser::class, ZhChrono::createStrictConfiguration()->parsers);
    $chineseCasualParsers = array_map(fn (object $parser): string => $parser::class, ZhChrono::createCasualConfiguration()->parsers);
    $chineseRefiners = array_map(fn (object $refiner): string => $refiner::class, ZhChrono::createStrictConfiguration()->refiners);
    $hansParsers = array_map(fn (object $parser): string => $parser::class, ZhHansChrono::createStrictConfiguration()->parsers);
    $hansCasualParsers = array_map(fn (object $parser): string => $parser::class, ZhHansChrono::createCasualConfiguration()->parsers);
    $hansRefiners = array_map(fn (object $refiner): string => $refiner::class, ZhHansChrono::createStrictConfiguration()->refiners);
    $hantParsers = array_map(fn (object $parser): string => $parser::class, ZhHantChrono::createStrictConfiguration()->parsers);
    $hantCasualParsers = array_map(fn (object $parser): string => $parser::class, ZhHantChrono::createCasualConfiguration()->parsers);
    $hantRefiners = array_map(fn (object $refiner): string => $refiner::class, ZhHantChrono::createStrictConfiguration()->refiners);

    expect($chineseParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(ZhHansDateParser::class)
        ->toContain(ZhHantDateParser::class)
        ->not->toContain(ZhHansCasualDateParser::class)
        ->not->toContain(ZhHantCasualDateParser::class)
        ->and(array_slice($chineseCasualParsers, 0, 2))->toBe([
            ZhHantCasualDateParser::class,
            IsoFormatParser::class,
        ])
        ->and($chineseRefiners)
        ->toContain(MergeWeekdayComponentRefiner::class)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->not->toContain(ExtractTimezoneOffsetRefiner::class)
        ->and($hansParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(ZhHansDateParser::class)
        ->not->toContain(ZhHansCasualDateParser::class)
        ->and(array_slice($hansCasualParsers, 0, 2))->toBe([
            ZhHansCasualDateParser::class,
            IsoFormatParser::class,
        ])
        ->and($hansRefiners)
        ->toContain(MergeWeekdayComponentRefiner::class)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->not->toContain(ExtractTimezoneOffsetRefiner::class)
        ->and($hantParsers)
        ->toContain(IsoFormatParser::class)
        ->toContain(ZhHantDateParser::class)
        ->not->toContain(ZhHantCasualDateParser::class)
        ->and(array_slice($hantCasualParsers, 0, 2))->toBe([
            ZhHantCasualDateParser::class,
            IsoFormatParser::class,
        ])
        ->and($hantRefiners)
        ->toContain(MergeWeekdayComponentRefiner::class)
        ->toContain(ExtractTimezoneAbbrRefiner::class)
        ->not->toContain(ExtractTimezoneOffsetRefiner::class)
        ->and(Chrono::strictChinese()->parseDateText('2012年8月10日', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictZhHans()->parseDateText('2012年8月10日', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictZhHant()->parseDateText('2012年8月10日', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictZhHans()->parseText('今天', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::strictZhHant()->parseText('今日', '2012-08-10'))
        ->toBe([]);
});

it('exposes source-shaped casual parser order for German French Finnish Italian Dutch Russian and Ukrainian', function () {
    $germanParsers = array_map(fn (object $parser): string => $parser::class, DeChrono::createCasualConfiguration()->parsers);
    $germanRefiners = array_map(fn (object $refiner): string => $refiner::class, DeChrono::createCasualConfiguration()->refiners);
    $frenchParsers = array_map(fn (object $parser): string => $parser::class, FrChrono::createCasualConfiguration()->parsers);
    $frenchRefiners = array_map(fn (object $refiner): string => $refiner::class, FrChrono::createCasualConfiguration()->refiners);
    $finnishParsers = array_map(fn (object $parser): string => $parser::class, FiChrono::createCasualConfiguration()->parsers);
    $italianParsers = array_map(fn (object $parser): string => $parser::class, ItChrono::createCasualConfiguration()->parsers);
    $dutchParsers = array_map(fn (object $parser): string => $parser::class, NlChrono::createCasualConfiguration()->parsers);
    $russianParsers = array_map(fn (object $parser): string => $parser::class, RuChrono::createCasualConfiguration()->parsers);
    $ukrainianParsers = array_map(fn (object $parser): string => $parser::class, UkChrono::createCasualConfiguration()->parsers);

    expect(array_slice($germanParsers, 0, 3))->toBe([
        DeTimeUnitRelativeFormatParser::class,
        DeCasualDateParser::class,
        DeCasualTimeParser::class,
    ])->and(array_search(DeMergeDateRangeRefiner::class, $germanRefiners, true))
        ->toBeLessThan(array_search(DeMergeDateTimeRefiner::class, $germanRefiners, true))
        ->and(array_slice($frenchParsers, 0, 3))->toBe([
            FrTimeUnitRelativeFormatParser::class,
            FrCasualTimeParser::class,
            FrCasualDateParser::class,
        ])->and(array_slice($frenchRefiners, 0, 3))->toBe([
            OverlapRemovalRefiner::class,
            ExtractTimezoneOffsetRefiner::class,
            MergeWeekdayComponentRefiner::class,
        ])->and(array_search(FrMergeDateTimeRefiner::class, $frenchRefiners, true))
        ->toBeLessThan(array_search(FrMergeDateRangeRefiner::class, $frenchRefiners, true))
        ->and(array_slice($finnishParsers, 0, 3))->toBe([
            FiTimeUnitCasualRelativeFormatParser::class,
            FiCasualDateParser::class,
            FiCasualTimeParser::class,
        ])->and(array_slice($italianParsers, 0, 5))->toBe([
            ItTimeUnitCasualRelativeFormatParser::class,
            ItRelativeDateFormatParser::class,
            ItMonthNameParser::class,
            ItCasualTimeParser::class,
            ItCasualDateParser::class,
        ])->and(array_slice($dutchParsers, 0, 6))->toBe([
            NlTimeUnitCasualRelativeFormatParser::class,
            NlRelativeDateFormatParser::class,
            NlMonthNameParser::class,
            NlCasualDateTimeParser::class,
            NlCasualTimeParser::class,
            NlCasualDateParser::class,
        ])->and(array_slice($russianParsers, 0, 5))->toBe([
            RuTimeUnitCasualRelativeFormatParser::class,
            RuRelativeDateFormatParser::class,
            RuMonthNameParser::class,
            RuCasualTimeParser::class,
            RuCasualDateParser::class,
        ])->and(array_slice($ukrainianParsers, 0, 5))->toBe([
            UkTimeUnitCasualRelativeFormatParser::class,
            UkRelativeDateFormatParser::class,
            UkMonthNameParser::class,
            UkCasualTimeParser::class,
            UkCasualDateParser::class,
        ]);
});

it('calculates weekdays like upstream helpers', function () {
    $saturday = CarbonImmutable::parse('2022-08-20 12:00:00');
    $sunday = CarbonImmutable::parse('2022-08-21 12:00:00');
    $tuesday = CarbonImmutable::parse('2022-08-02 12:00:00');

    expect(Weekdays::getDaysToWeekday($saturday, Weekday::MONDAY, 'this'))->toBe(2)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::FRIDAY, 'this'))->toBe(5)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::SUNDAY, 'this'))->toBe(5)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::FRIDAY, 'last'))->toBe(-1)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::MONDAY, 'last'))->toBe(-5)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SUNDAY, 'last'))->toBe(-6)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SATURDAY, 'last'))->toBe(-7)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::MONDAY, 'next'))->toBe(1)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::SATURDAY, 'next'))->toBe(6)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::SUNDAY, 'next'))->toBe(7)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SATURDAY, 'next'))->toBe(7)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SUNDAY, 'next'))->toBe(8)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::MONDAY, 'next'))->toBe(6)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::FRIDAY, 'next'))->toBe(10)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::SUNDAY, 'next'))->toBe(12)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::MONDAY))->toBe(2)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::TUESDAY))->toBe(3)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::FRIDAY))->toBe(-1)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::THURSDAY))->toBe(-2)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::WEDNESDAY))->toBe(-3);
});

it('creates weekday components like upstream helpers', function () {
    $reference = Reference::make('2022-08-20 12:00:00');
    $components = Weekdays::createParsingComponentsAtWeekday($reference, Weekday::MONDAY, 'this');
    $jstReference = Reference::make([
        'instant' => '2025-02-27T17:00:00.000Z',
        'timezone' => 'JST',
    ]);
    $pstReference = Reference::make([
        'instant' => '2025-02-27T17:00:00.000Z',
        'timezone' => 'PST',
    ]);
    $jstFriday = Weekdays::createParsingComponentsAtWeekday($jstReference, Weekday::FRIDAY, 'this');
    $pstFriday = Weekdays::createParsingComponentsAtWeekday($pstReference, Weekday::FRIDAY, 'this');

    expect($components->date()->toDateTimeString())->toBe('2022-08-22 12:00:00')
        ->and($components->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($components->isCertain('weekday'))->toBeTrue()
        ->and($components->isCertain('day'))->toBeFalse()
        ->and($jstFriday->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 12:00:00 +09:00')
        ->and($pstFriday->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 12:00:00 -08:00');
});

it('calculates durations like upstream helpers', function () {
    $reference = CarbonImmutable::parse('2022-08-27 12:52:11.000');

    expect(Duration::add($reference, ['year' => 1])->toDateTimeString())->toBe('2023-08-27 12:52:11')
        ->and(Duration::add($reference, ['month' => 1])->toDateTimeString())->toBe('2022-09-27 12:52:11')
        ->and(Duration::add($reference, ['week' => 1])->toDateTimeString())->toBe('2022-09-03 12:52:11')
        ->and(Duration::add($reference, ['day' => 1])->toDateTimeString())->toBe('2022-08-28 12:52:11')
        ->and(Duration::add($reference, ['hour' => 1])->toDateTimeString())->toBe('2022-08-27 13:52:11')
        ->and(Duration::add($reference, ['minute' => 1])->toDateTimeString())->toBe('2022-08-27 12:53:11')
        ->and(Duration::add($reference, ['second' => 1])->toDateTimeString())->toBe('2022-08-27 12:52:12')
        ->and(Duration::add($reference, ['millisecond' => 1])->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.001')
        ->and(Duration::add($reference, ['y' => 1])->toDateTimeString())->toBe('2023-08-27 12:52:11')
        ->and(Duration::add($reference, ['M' => 1])->toDateTimeString())->toBe('2022-09-27 12:52:11')
        ->and(Duration::add($reference, ['w' => 1])->toDateTimeString())->toBe('2022-09-03 12:52:11')
        ->and(Duration::add($reference, ['d' => 1])->toDateTimeString())->toBe('2022-08-28 12:52:11')
        ->and(Duration::add($reference, ['h' => 1])->toDateTimeString())->toBe('2022-08-27 13:52:11')
        ->and(Duration::add($reference, ['m' => 1])->toDateTimeString())->toBe('2022-08-27 12:53:11')
        ->and(Duration::add($reference, ['s' => 1])->toDateTimeString())->toBe('2022-08-27 12:52:12')
        ->and(Duration::add($reference, ['ms' => 1])->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.001')
        ->and(Duration::add($reference, ['month' => 1, 'day' => 4])->toDateTimeString())->toBe('2022-10-01 12:52:11')
        ->and(Duration::add($reference, ['month' => 1, 'day' => 4, 'hour' => 12])->toDateTimeString())->toBe('2022-10-02 00:52:11')
        ->and(Duration::add($reference, ['year' => 0.5])->toDateTimeString())->toBe('2023-02-27 12:52:11')
        ->and(Duration::add($reference, ['month' => 0.5])->toDateTimeString())->toBe('2022-09-10 12:52:11')
        ->and(Duration::add($reference, ['week' => 0.5])->toDateTimeString())->toBe('2022-08-31 12:52:11')
        ->and(Duration::add($reference, ['day' => 0.5])->toDateTimeString())->toBe('2022-08-28 00:52:11')
        ->and(Duration::add($reference, ['hour' => 0.5])->toDateTimeString())->toBe('2022-08-27 13:22:11')
        ->and(Duration::add($reference, ['minute' => 0.5])->toDateTimeString())->toBe('2022-08-27 12:52:41')
        ->and(Duration::add($reference, ['second' => 0.5])->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.500')
        ->and(Duration::add($reference, ['year' => 0.5, 'month' => 2])->toDateTimeString())->toBe('2023-04-27 12:52:11')
        ->and(Duration::reverse(['year' => 5, 'month' => -5]))->toBe(['year' => -5, 'month' => 5])
        ->and(Duration::EMPTY)->toBe(['day' => 0, 'second' => 0, 'millisecond' => 0]);
});

it('calculates years like upstream helpers', function () {
    expect(Years::findMostLikelyADYear(1997))->toBe(1997)
        ->and(Years::findMostLikelyADYear(97))->toBe(1997)
        ->and(Years::findMostLikelyADYear(12))->toBe(2012)
        ->and(Years::findMostLikelyADYear(50))->toBe(2050)
        ->and(Years::findMostLikelyADYear(51))->toBe(1951)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-08-10'), 3, 1))->toBe(2013)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-08-10'), 10, 8))->toBe(2012)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-01-01'), 31, 12))->toBe(2011)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-12-31'), 1, 1))->toBe(2013);
});

it('builds regex patterns like upstream helpers', function () {
    $any = Pattern::matchAny(['jan' => 1, 'january' => 1, 'mar.' => 3]);
    $repeated = Pattern::repeatedTimeunitPattern('', '(\d+)\s*(hours?|minutes?)');

    expect($any)->toBe('(?:january|mar\.|jan)')
        ->and(preg_match("/^{$repeated}$/", '2 hours, 30 minutes'))->toBe(1)
        ->and(preg_match_all("/{$repeated}/", '2 hours, 30 minutes', $matches))->toBe(1)
        ->and(array_key_exists(1, $matches))->toBeFalse();
});

it('normalizes word-boundary parser captures like upstream', function () {
    $parser = new class extends AbstractParserWithWordBoundary
    {
        protected function innerPattern(Reference $reference, Options $options): string
        {
            return '(foo)(?<named>bar)';
        }

        protected function innerExtract(array $match, Reference $reference, Options $options): ParsedResult
        {
            return new ParsedResult(
                $match[0][1],
                $match[1][0].'|'.$match[2][0].'|'.$match['named'][0],
                new ParsedComponents($reference->date),
            );
        }
    };

    $result = $parser->parse('x foobar', Reference::make('2026-06-23'), new Options)[0];

    expect($result->index)->toBe(2)
        ->and($result->text)->toBe('foo|bar|bar');
});

it('continues word-boundary parser matching after failed extraction like upstream', function () {
    $parser = new class extends AbstractParserWithWordBoundary
    {
        protected function innerPattern(Reference $reference, Options $options): string
        {
            return 'aa';
        }

        protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
        {
            if ($match[0][1] === 0) {
                return null;
            }

            return new ParsedComponents($reference->date);
        }

        protected function patternLeftBoundary(): string
        {
            return '()';
        }
    };

    $results = $parser->parse('aaa', Reference::make('2026-06-23'), new Options);

    expect($results)->toHaveCount(1)
        ->and($results[0]->index)->toBe(1)
        ->and($results[0]->text)->toBe('aa');
});

it('gets timezone DST boundary dates like upstream helpers', function () {
    $secondSunday = Timezone::getNthWeekdayOfMonth(2022, Month::MARCH, Weekday::SUNDAY, 2, 2);
    $firstSunday = Timezone::getNthWeekdayOfMonth(2022, Month::NOVEMBER, Weekday::SUNDAY, 1, 2);
    $lastSunday = Timezone::getLastWeekdayOfMonth(2022, Month::OCTOBER, Weekday::SUNDAY, 3);
    $lastFriday = Timezone::getLastWeekdayOfMonth(2024, 2, 5, 9);

    expect($secondSunday->toDateTimeString())->toBe('2022-03-13 02:00:00')
        ->and($firstSunday->toDateTimeString())->toBe('2022-11-06 02:00:00')
        ->and($lastSunday->toDateTimeString())->toBe('2022-10-30 03:00:00')
        ->and($lastFriday->toDateTimeString())->toBe('2024-02-23 09:00:00');
});

it('assigns and implies date components like upstream helpers', function () {
    $date = CarbonImmutable::parse('2026-06-23 15:04:05.006');
    $assigned = new ParsedComponents(CarbonImmutable::parse('2012-08-10 01:02:03.004'));
    $implied = new ParsedComponents(CarbonImmutable::parse('2012-08-10 01:02:03.004'));

    Dates::assignSimilarDate($assigned, $date);
    Dates::assignSimilarTime($assigned, $date);
    Dates::implySimilarDate($implied, $date);
    Dates::implySimilarTime($implied, $date);

    expect($assigned->get('year'))->toBe(2026)
        ->and($assigned->get('month'))->toBe(6)
        ->and($assigned->get('day'))->toBe(23)
        ->and($assigned->get('hour'))->toBe(15)
        ->and($assigned->get('meridiem'))->toBe(Meridiem::PM)
        ->and($assigned->isCertain('meridiem'))->toBeTrue()
        ->and($implied->get('year'))->toBe(2026)
        ->and($implied->get('hour'))->toBe(15)
        ->and($implied->get('meridiem'))->toBe(Meridiem::PM)
        ->and($implied->isCertain('year'))->toBeFalse()
        ->and($implied->isCertain('meridiem'))->toBeFalse();
});

it('returns known and implied meridiem components like upstream results', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:00:00'));

    expect($components->get('meridiem'))->toBeNull()
        ->and($components->imply('meridiem', Meridiem::PM->value))->toBe($components)
        ->and($components->get('meridiem'))->toBe(Meridiem::PM)
        ->and($components->isCertain('meridiem'))->toBeFalse()
        ->and($components->assign('meridiem', Meridiem::AM->value))->toBe($components)
        ->and($components->get('meridiem'))->toBe(Meridiem::AM)
        ->and($components->isCertain('meridiem'))->toBeTrue();
});

it('merges date and time components like upstream helpers', function () {
    $date = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:00:00'), [
        'year' => 2022,
        'month' => 8,
        'day' => 27,
    ]);

    $time = new ParsedComponents(CarbonImmutable::parse('2022-08-27 09:30:00'), [
        'hour' => 9,
        'minute' => 30,
        'meridiem' => Meridiem::PM->value,
    ]);

    $merged = MergingCalculation::mergeDateTimeComponent($date, $time);

    expect($merged->date()->toDateTimeString())->toBe('2022-08-27 21:30:00')
        ->and($merged->isCertain('hour'))->toBeTrue()
        ->and($merged->isCertain('second'))->toBeFalse()
        ->and($merged->get('meridiem'))->toBe(Meridiem::PM);
});

it('moves merged overnight time ranges to the next day like upstream helpers', function () {
    $date = new ParsedResult(0, 'Tuesday', new ParsedComponents(CarbonImmutable::parse('2022-08-23 12:00:00'), [
        'year' => 2022,
        'month' => 8,
        'day' => 23,
        'weekday' => Weekday::TUESDAY->value,
    ]));

    $time = new ParsedResult(
        8,
        '9pm - 1am',
        new ParsedComponents(CarbonImmutable::parse('2022-08-23 21:00:00'), [
            'hour' => 9,
            'minute' => 0,
            'meridiem' => Meridiem::PM->value,
        ]),
        new ParsedComponents(CarbonImmutable::parse('2022-08-23 01:00:00'), [
            'hour' => 1,
            'minute' => 0,
            'meridiem' => Meridiem::AM->value,
        ]),
    );

    $merged = MergingCalculation::mergeDateTimeResult($date, $time);

    expect($merged->start->date()->toDateTimeString())->toBe('2022-08-23 21:00:00')
        ->and($merged->end?->date()->toDateTimeString())->toBe('2022-08-24 01:00:00')
        ->and($merged->end?->isCertain('day'))->toBeTrue();
});

it('forwards same-day weekday components by a full week like upstream refiner', function () {
    $result = new ParsedResult(0, 'Friday', new ParsedComponents(CarbonImmutable::parse('2023-04-07 12:00:00'), [
        'weekday' => Weekday::FRIDAY->value,
    ]));

    $results = (new ForwardDateRefiner)->refine(
        'Friday',
        [$result],
        Reference::make('2023-04-07 13:00:00'),
        new Options(['forwardDate' => true]),
    );

    expect($results[0]->start->date()->toDateTimeString())->toBe('2023-04-14 12:00:00')
        ->and($results[0]->start->isCertain('weekday'))->toBeTrue()
        ->and($results[0]->start->isCertain('day'))->toBeFalse();
});

it('ignores lowercase timezone abbreviations when an implied offset conflicts like upstream refiner', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2023-04-07 12:00:00'));
    $components->imply('timezoneOffset', 240);

    $result = new ParsedResult(0, 'tomorrow', $components);

    $results = (new ExtractTimezoneAbbrRefiner)->refine(
        'tomorrow est',
        [$result],
        Reference::make('2023-04-06 12:00:00'),
        new Options,
    );

    expect($results[0]->text)->toBe('tomorrow')
        ->and($results[0]->start->get('timezoneOffset'))->toBe(240)
        ->and($results[0]->start->isCertain('timezoneOffset'))->toBeFalse();
});

it('parses iso dates into carbon instances', function () {
    $result = Chrono::parse('Ship on 2026-06-23 14:30')[0];

    expect($result)->toBeInstanceOf(ParsedResult::class)
        ->and($result->text)->toBe('2026-06-23 14:30')
        ->and($result->start->date())->toBeInstanceOf(CarbonImmutable::class)
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-23 14:30:00')
        ->and($result->tags())->toContain('parser/ISOFormatParser')
        ->and($result->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($result->tags())->toContain('refiner/mergeDateFollowedByTime');
});

it('parses iso datetimes with timezone suffixes', function () {
    $offset = Chrono::parse('1994-11-05T08:15:30-05:30')[0];
    $utc = Chrono::parse('1994-11-05T13:15:30Z')[0];
    $fractional = Chrono::parse('2016-05-07T23:45:00.487+01:00')[0];
    $longFractional = Chrono::parse('2016-05-07T12:45:00.1234+01:00')[0];
    $local = Chrono::parse('1994-11-05T13:15:30')[0];
    $hourOnlyPositive = Chrono::parse('1994-11-05T13:15:30+09')[0];
    $hourOnlyNegative = Chrono::parse('1994-11-05T13:15:30-05')[0];
    $compact = Chrono::parse('1994-11-05T13:15:30+0900')[0];

    expect($offset->text)->toBe('1994-11-05T08:15:30-05:30')
        ->and($offset->start->timezoneOffset())->toBe(-330)
        ->and($offset->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 -05:30')
        ->and($offset->tags())->toContain('parser/ISOFormatParser')
        ->and($utc->text)->toBe('1994-11-05T13:15:30Z')
        ->and($utc->start->timezoneOffset())->toBe(0)
        ->and($utc->tags())->toContain('parser/ISOFormatParser')
        ->and($fractional->text)->toBe('2016-05-07T23:45:00.487+01:00')
        ->and($fractional->start->timezoneOffset())->toBe(60)
        ->and($fractional->start->get('millisecond'))->toBe(487)
        ->and($fractional->start->isCertain('millisecond'))->toBeTrue()
        ->and($fractional->start->date()->format('Y-m-d H:i:s.v P'))->toBe('2016-05-07 23:45:00.487 +01:00')
        ->and($fractional->tags())->toContain('parser/ISOFormatParser')
        ->and($longFractional->start->get('millisecond'))->toBe(1234)
        ->and($longFractional->start->date()->format('Y-m-d H:i:s.v P'))->toBe('2016-05-07 12:45:01.234 +01:00')
        ->and($local->start->timezoneOffset())->toBeNull()
        ->and($hourOnlyPositive->text)->toBe('1994-11-05T13:15:30+09')
        ->and($hourOnlyPositive->start->timezoneOffset())->toBe(540)
        ->and($hourOnlyPositive->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 13:15:30 +09:00')
        ->and($hourOnlyNegative->text)->toBe('1994-11-05T13:15:30-05')
        ->and($hourOnlyNegative->start->timezoneOffset())->toBe(-300)
        ->and($compact->text)->toBe('1994-11-05T13:15:30+0900')
        ->and($compact->start->timezoneOffset())->toBe(540);
});

it('parses native javascript and rfc date strings', function () {
    $positiveOffset = Chrono::parse('1994-11-05T08:15:30+11:30')[0];
    $negativeOffset = Chrono::parse('2014-11-30T08:15:30-05:30')[0];
    $rfc = Chrono::parse('Sat, 21 Feb 2015 11:50:48 -0500')[0];
    $utcOffset = Chrono::parse('22 Feb 2015 04:12:00 -0000')[0];
    $slash = Chrono::parse('09/25/2017 10:31:50.522 PM')[0];
    $javascript = Chrono::parse('Sat Nov 05 1994 22:45:30 GMT+0900 (JST)')[0];
    $utcName = Chrono::parse('Fri, 31 Mar 2000 07:00:00 UTC')[0];

    expect($positiveOffset->text)->toBe('1994-11-05T08:15:30+11:30')
        ->and($positiveOffset->start->timezoneOffset())->toBe(690)
        ->and($positiveOffset->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 +11:30')
        ->and($negativeOffset->text)->toBe('2014-11-30T08:15:30-05:30')
        ->and($negativeOffset->start->timezoneOffset())->toBe(-330)
        ->and($negativeOffset->start->date()->format('Y-m-d H:i:s P'))->toBe('2014-11-30 08:15:30 -05:30')
        ->and($rfc->text)->toBe('Sat, 21 Feb 2015 11:50:48 -0500')
        ->and($rfc->start->timezoneOffset())->toBe(-300)
        ->and($rfc->start->date()->format('Y-m-d H:i:s P'))->toBe('2015-02-21 11:50:48 -05:00')
        ->and($rfc->tags())->toContain('parser/NativeDateFormatParser')
        ->and($utcOffset->text)->toBe('22 Feb 2015 04:12:00 -0000')
        ->and($utcOffset->start->timezoneOffset())->toBe(0)
        ->and($utcOffset->start->date()->format('Y-m-d H:i:s P'))->toBe('2015-02-22 04:12:00 +00:00')
        ->and($utcOffset->tags())->toContain('parser/NativeDateFormatParser')
        ->and($slash->text)->toBe('09/25/2017 10:31:50.522 PM')
        ->and($slash->start->get('millisecond'))->toBe(522)
        ->and($slash->start->date()->format('Y-m-d H:i:s.v'))->toBe('2017-09-25 22:31:50.522')
        ->and($slash->tags())->toContain('parser/NativeDateFormatParser')
        ->and($javascript->text)->toBe('Sat Nov 05 1994 22:45:30 GMT+0900 (JST)')
        ->and($javascript->start->timezoneOffset())->toBe(540)
        ->and($javascript->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 22:45:30 +09:00')
        ->and($javascript->tags())->toContain('parser/NativeDateFormatParser')
        ->and($utcName->text)->toBe('Fri, 31 Mar 2000 07:00:00 UTC')
        ->and($utcName->start->timezoneOffset())->toBe(0)
        ->and($utcName->start->date()->format('Y-m-d H:i:s P'))->toBe('2000-03-31 07:00:00 +00:00')
        ->and($utcName->tags())->toContain('parser/NativeDateFormatParser');
});

it('parses slash dates with forward date option', function () {
    $date = Chrono::parseDate('Book 6/20', '2026-06-23 09:00', ['forwardDate' => true]);

    expect($date?->toDateTimeString())->toBe('2027-06-20 12:00:00');
});

it('uses the closest year for slash dates without explicit years', function () {
    expect(Chrono::parseDate('1/1', '2012-12-31 09:00')?->toDateTimeString())
        ->toBe('2013-01-01 12:00:00')
        ->and(Chrono::parseDate('12/31', '2012-01-01 09:00')?->toDateTimeString())
        ->toBe('2011-12-31 12:00:00')
        ->and(Chrono::parseDate('12/31', '2012-01-01 09:00', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2012-12-31 12:00:00');
});

it('parses month name dates and ranges', function () {
    $result = Chrono::parse('Sep 12-13', '2026-06-23')[0];

    expect($result->start->date()->toDateString())->toBe('2026-09-12')
        ->and($result->end?->date()->toDateString())->toBe('2026-09-13');
});

it('parses little endian month name dates with two digit years', function () {
    $result = Chrono::parse('3rd Feb 82', '2012-08-10')[0];

    expect($result->text)->toBe('3rd Feb 82')
        ->and($result->start->date()->toDateTimeString())->toBe('1982-02-03 12:00:00')
        ->and($result->start->get('year'))->toBe(1982);
});

it('parses little endian same month ranges', function () {
    $result = Chrono::parse('10 - 22 August 2012', '2012-08-10')[0];

    expect($result->text)->toBe('10 - 22 August 2012')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00');
});

it('parses little endian cross month ranges', function () {
    $result = Chrono::parse('10 August - 12 September', '2012-08-10')[0];

    expect($result->text)->toBe('10 August - 12 September')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00');
});

it('uses an end year for both dates in little endian cross month ranges', function () {
    $result = Chrono::parse('10 August - 12 September 2013', '2012-08-10')[0];
    $startYear = Chrono::parse('10 August 2013 - 12 September', '2012-08-10')[0];

    expect($result->text)->toBe('10 August - 12 September 2013')
        ->and($result->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($startYear->text)->toBe('10 August 2013 - 12 September')
        ->and($startYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($startYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00');
});

it('parses middle endian cross month ranges', function () {
    $result = Chrono::parse('August 10 - November 12', '2012-08-10')[0];

    expect($result->text)->toBe('August 10 - November 12')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-11-12 12:00:00');
});

it('parses middle endian dates and ranges with compact comma years', function () {
    $date = Chrono::parse('Published November 1,2001', '2012-08-10')[0];
    $range = Chrono::parse('174 November 1,2001- March 31,2002', '2012-08-10')[0];

    expect($date->text)->toBe('November 1,2001')
        ->and($date->start->date()->toDateTimeString())->toBe('2001-11-01 12:00:00')
        ->and($date->tags())->toContain('parser/ENMonthNameMiddleEndianParser')
        ->and($range->text)->toBe('November 1,2001- March 31,2002')
        ->and($range->start->date()->toDateTimeString())->toBe('2001-11-01 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2002-03-31 12:00:00');
});

it('skips year-like middle endian month dates for british english', function () {
    $middleEndian = Chrono::casual()->parseText('Dec. 21', '2024-01-10')[0];
    $littleEndian = Chrono::gb()->parseText('Dec. 21', '2024-01-10')[0];

    expect($middleEndian->text)->toBe('Dec. 21')
        ->and($middleEndian->start->date()->toDateTimeString())->toBe('2023-12-21 12:00:00')
        ->and($littleEndian->text)->toBe('Dec. 21')
        ->and($littleEndian->start->date()->toDateTimeString())->toBe('2021-12-01 12:00:00');
});

it('parses month name dates with separators', function () {
    expect(Chrono::parse('August-10, 2012', '2012-08-10')[0]->text)
        ->toBe('August-10, 2012')
        ->and(Chrono::parseDate('August/10/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10-August-2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10/August/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00');
});

it('parses weekday prefixed month name dates', function () {
    $compact = Chrono::parse('Sun 15Sep', '2013-08-10')[0];
    $punctuated = Chrono::parse('Wed, Jan 20th, 2016', '2012-08-10')[0];

    expect($compact->text)->toBe('Sun 15Sep')
        ->and($compact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($compact->start->isCertain('weekday'))->toBeTrue()
        ->and($punctuated->text)->toBe('Wed, Jan 20th, 2016')
        ->and($punctuated->start->date()->toDateTimeString())->toBe('2016-01-20 12:00:00')
        ->and(Chrono::parseDate('Sunday, March, 6th 2016', '2012-08-10')?->toDateTimeString())
        ->toBe('2016-03-06 12:00:00');
});

it('parses little endian month name dates followed by times', function () {
    expect(Chrono::parse('12th of July at 19:00', '2012-08-10')[0]->text)
        ->toBe('12th of July at 19:00')
        ->and(Chrono::parse('12th August', '2012-08-10')[0]->text)
        ->toBe('12th August')
        ->and(Chrono::parseDate('12 August', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and(Chrono::parseDate('12th of August', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and(Chrono::parseDate('12th of July at 19:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:00:00')
        ->and(Chrono::parseDate('5 May 12:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-05 12:00:00')
        ->and(Chrono::parseDate('24th October, 9 am', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 09:00:00')
        ->and(Chrono::parse('24 October, 9 p.m.', '2017-07-07 15:00')[0]->text)
        ->toBe('24 October, 9 p.m.')
        ->and(Chrono::parseDate('24 October, 9 p.m.', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 21:00:00')
        ->and(Chrono::parseDate('24 October 10 o clock', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 10:00:00');
});

it('parses month name dates with ordinal words', function () {
    expect(Chrono::parse('May eighth, 2010', '2012-08-10')[0]->text)
        ->toBe('May eighth, 2010')
        ->and(Chrono::parseDate('May eighth, 2010', '2012-08-10')?->toDateTimeString())
        ->toBe('2010-05-08 12:00:00')
        ->and(Chrono::parseDate('May twenty-fourth', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-24 12:00:00')
        ->and(Chrono::parseDate('Twenty-fourth of May', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-24 12:00:00');
});

it('parses month name ranges with ordinal words', function () {
    $littleEndian = Chrono::parse('Eighth to eleventh May 2010', '2012-08-10')[0];
    $middleEndian = Chrono::parse('May eighth - tenth, 2010', '2012-08-10')[0];

    expect($littleEndian->text)->toBe('Eighth to eleventh May 2010')
        ->and($littleEndian->start->date()->toDateTimeString())->toBe('2010-05-08 12:00:00')
        ->and($littleEndian->end?->date()->toDateTimeString())->toBe('2010-05-11 12:00:00')
        ->and($middleEndian->text)->toBe('May eighth - tenth, 2010')
        ->and($middleEndian->start->date()->toDateTimeString())->toBe('2010-05-08 12:00:00')
        ->and($middleEndian->end?->date()->toDateTimeString())->toBe('2010-05-10 12:00:00');
});

it('parses month only expressions', function () {
    $explicitYear = Chrono::parse('She is getting married soon (July 2017).')[0];
    $monthOnly = Chrono::parse('She is leaving in August.', '2012-08-10')[0];
    $monthYear = Chrono::parse('I am arriving sometime in August, 2012, probably.', '2012-08-10')[0];

    expect($explicitYear->start->date()->toDateTimeString())
        ->toBe('2017-07-01 12:00:00')
        ->and($explicitYear->start->tags())->toContain('parser/ENMonthNameParser')
        ->and($monthOnly->start->date()->toDateTimeString())
        ->toBe('2012-08-01 12:00:00')
        ->and($monthOnly->tags())->toContain('parser/ENMonthNameParser')
        ->and($monthYear->text)
        ->toBe('August, 2012')
        ->and($monthYear->start->tags())->toContain('parser/ENMonthNameParser')
        ->and(Chrono::parseDate('I am arriving sometime in August, 2012, probably.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-01 12:00:00');
});

it('parses month year expressions with alternate separators', function () {
    expect(Chrono::parse('Sep-2012', '2012-08-10')[0]->text)
        ->toBe('Sep-2012')
        ->and(Chrono::parseDate('Sep-2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and(Chrono::parse('in June of 2022', '2012-08-10')[0]->text)
        ->toBe('June of 2022')
        ->and(Chrono::parseDate('in June of 2022', '2012-08-10')?->toDateTimeString())
        ->toBe('2022-06-01 12:00:00')
        ->and(Chrono::parse('Aug 96', '2012-08-10')[0]->text)
        ->toBe('Aug 96')
        ->and(Chrono::parseDate('Aug 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-08-01 12:00:00')
        ->and(Chrono::parseDate('August 10', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00');
});

it('skips unlikely bare month abbreviations', function () {
    $context = Chrono::parse('By Angie Mar November 2019', '2012-08-10')[0];

    expect($context->text)->toBe('November 2019')
        ->and($context->start->date()->toDateTimeString())->toBe('2019-11-01 12:00:00')
        ->and(Chrono::parse('Mar', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('in Jan', '2020-11-22')[0]->text)->toBe('Jan')
        ->and(Chrono::parseDate('in Jan', '2020-11-22')?->toDateTimeString())->toBe('2021-01-01 12:00:00');
});

it('parses month only ranges', function () {
    $sameYear = Chrono::parse('From May to December', '2023-05-09')[0];
    $crossYear = Chrono::parse('From December to May', '2023-05-09')[0];
    $explicitSameYear = Chrono::parse('From May to December, 2022', '2023-05-09')[0];
    $explicitCrossYear = Chrono::parse('From December to May 2025', '2023-05-09')[0];
    $forwardCrossYear = Chrono::parse('From December to May', '2023-05-09', ['forwardDate' => true])[0];
    $monthYearRange = Chrono::parse('July 2020 to August 2020', '2012-08-10');

    expect($sameYear->text)->toBe('From May to December')
        ->and($sameYear->start->date()->toDateTimeString())->toBe('2023-05-01 12:00:00')
        ->and($sameYear->end?->date()->toDateTimeString())->toBe('2023-12-01 12:00:00')
        ->and($crossYear->text)->toBe('From December to May')
        ->and($crossYear->start->date()->toDateTimeString())->toBe('2022-12-01 12:00:00')
        ->and($crossYear->end?->date()->toDateTimeString())->toBe('2023-05-01 12:00:00')
        ->and($explicitSameYear->start->date()->toDateTimeString())->toBe('2022-05-01 12:00:00')
        ->and($explicitSameYear->end?->date()->toDateTimeString())->toBe('2022-12-01 12:00:00')
        ->and($explicitCrossYear->start->date()->toDateTimeString())->toBe('2024-12-01 12:00:00')
        ->and($explicitCrossYear->end?->date()->toDateTimeString())->toBe('2025-05-01 12:00:00')
        ->and($forwardCrossYear->start->date()->toDateTimeString())->toBe('2023-12-01 12:00:00')
        ->and($forwardCrossYear->end?->date()->toDateTimeString())->toBe('2024-05-01 12:00:00')
        ->and($monthYearRange)->toHaveCount(1)
        ->and($monthYearRange[0]->text)->toBe('July 2020 to August 2020')
        ->and($monthYearRange[0]->start->date()->toDateTimeString())->toBe('2020-07-01 12:00:00')
        ->and($monthYearRange[0]->end?->date()->toDateTimeString())->toBe('2020-08-01 12:00:00')
        ->and($monthYearRange[0]->tags())->toContain('parser/ENMonthNameParser');
});

it('uses forward date option for month only expressions', function () {
    expect(Chrono::parseDate('in December', '2023-04-09', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2023-12-01 12:00:00')
        ->and(Chrono::parseDate('in May', '2023-04-09', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2023-05-01 12:00:00');
});

it('does not attach timezone abbreviations to month only expressions', function () {
    $result = Chrono::parse('People visiting Buñol towards the end of August get a good chance', '2012-08-10')[0];

    expect($result->text)->toBe('August')
        ->and($result->start->timezoneOffset())->toBeNull()
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00');
});

it('does not parse modal may as a month', function () {
    expect(Chrono::parse('The mountain may not move', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('May not be correct', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('in May', '2012-08-10')[0]->text)
        ->toBe('May');
});

it('filters unlikely english second phrases', function () {
    expect(Chrono::parse('the second half', '2012-08-10'))
        ->toBe([]);
});

it('does not parse reporting-period prose as a relative duration', function () {
    $result = Chrono::parse('Statement of comprehensive income for the year ended Dec. 2021', '2012-08-10')[0];

    expect($result->text)->toBe('Dec. 2021')
        ->and($result->start->date()->toDateTimeString())->toBe('2021-12-01 12:00:00');
});

it('uses the closest year for month day expressions without explicit years', function () {
    $result = Chrono::parse('The Deadline is January 10', '2012-08-10')[0];

    expect($result->text)->toBe('January 10')
        ->and($result->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00');
});

it('parses trailing years after month date time expressions', function () {
    $plain = Chrono::parse('Thu Oct 26 11:00:09 2023', '2016-10-01 08:00')[0];
    $zoned = Chrono::parse('Thu Oct 26 11:00:09 EDT 2023', '2016-10-01 08:00')[0];

    expect($plain->text)->toBe('Thu Oct 26 11:00:09 2023')
        ->and($plain->start->date()->toDateTimeString())->toBe('2023-10-26 11:00:09')
        ->and($plain->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($zoned->text)->toBe('Thu Oct 26 11:00:09 EDT 2023')
        ->and($zoned->start->timezoneOffset())->toBe(-240)
        ->and($zoned->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($zoned->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-10-26 11:00:09 -04:00');
});

it('parses trailing years after month date time ranges', function () {
    $dayRange = Chrono::parse('Thu Oct 26 - 28, 11:00:09 2023', '2016-10-01 08:00')[0];
    $timeRange = Chrono::parse('Thu Oct 26, 10:00 - 11:00:09 2023', '2016-10-01 08:00')[0];

    expect($dayRange->text)->toBe('Thu Oct 26 - 28, 11:00:09 2023')
        ->and($dayRange->start->date()->toDateTimeString())->toBe('2023-10-26 11:00:09')
        ->and($dayRange->end?->date()->toDateTimeString())->toBe('2023-10-28 11:00:09')
        ->and($timeRange->text)->toBe('Thu Oct 26, 10:00 - 11:00:09 2023')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2023-10-26 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2023-10-26 11:00:09');
});

it('extracts english year suffixes from unknown-year dates', function () {
    $refiner = new EnExtractYearSuffixRefiner;
    $reference = Reference::make('2012-08-10');
    $options = new Options;
    $date = CarbonImmutable::parse('2012-03-14 12:00');

    $components = new ParsedComponents($date);
    $components->assign('month', 3);
    $components->assign('day', 14);

    $result = new ParsedResult(0, 'March 14', $components);
    $refined = $refiner->refine('March 14 2026', [$result], $reference, $options)[0];

    $shortComponents = new ParsedComponents($date);
    $shortComponents->assign('month', 3);
    $shortComponents->assign('day', 14);

    $shortResult = new ParsedResult(0, 'March 14', $shortComponents);
    $shortRefined = $refiner->refine('March 14 90', [$shortResult], $reference, $options)[0];

    expect($refined->text)->toBe('March 14 2026')
        ->and($refined->start->get('year'))->toBe(2026)
        ->and($refined->start->isCertain('year'))->toBeTrue()
        ->and($refined->tags())->toContain('refiner/extractYearSuffix')
        ->and($shortRefined->text)->toBe('March 14')
        ->and($shortRefined->start->isCertain('year'))->toBeFalse();
});

it('parses year month day expressions', function () {
    $slash = Chrono::parse('2012/8/10', '2012-08-10')[0];

    expect($slash->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($slash->start->tags())->toContain('parser/ENYearMonthDayParser')
        ->and(Chrono::parseDate('2014.12.28', '2012-08-10')?->toDateTimeString())
        ->toBe('2014-12-28 12:00:00')
        ->and(Chrono::strict()->parseText('2014 12 28', '2012-08-10')[0]->text)
        ->toBe('2014 12 28')
        ->and(Chrono::strict()->parseDateText('2014 12 28', '2012-08-10')?->toDateTimeString())
        ->toBe('2014-12-28 12:00:00')
        ->and(Chrono::parseDate('2018 March 18', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-03-18 12:00:00')
        ->and(Chrono::parse('2018 Mar. 18', '2012-08-10')[0]->text)
        ->toBe('2018 Mar. 18')
        ->and(Chrono::parseDate('2018/Mar./18', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-03-18 12:00:00');
});

it('swaps year month day order when month is impossible and day can be month', function () {
    $result = Chrono::parse('2024/13/1', '2012-08-10')[0];
    $strict = Chrono::strict()->parseText('2024/13/1', '2012-08-10');

    expect($result->start->get('year'))->toBe(2024)
        ->and($result->start->get('month'))->toBe(1)
        ->and($result->start->get('day'))->toBe(13)
        ->and($strict)->toBe([]);
});

it('does not parse impossible year month day expressions', function () {
    expect(Chrono::parse('2014-08-32'))->toBe([])
        ->and(Chrono::parse('2014-02-30'))->toBe([])
        ->and(Chrono::parse('2012/80/10'))->toBe([])
        ->and(Chrono::parse('2012 80 10'))->toBe([])
        ->and(Chrono::parse('2012-14'))->toBe([])
        ->and(Chrono::parse('2012-1400'))->toBe([])
        ->and(Chrono::parse('2200-25'))->toBe([]);
});

it('does not parse partial invalid date expressions', function () {
    expect(Chrono::parse('4/13/1', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('August 32', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('February 29', '2014-08-10'))
        ->toBe([])
        ->and(Chrono::parse('February 29, 2022', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('June 31, 2022', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('February 151998', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('February 20 - 29, 2022', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('June 10 - 31, 2022', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parseDate('4/13', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-04-13 12:00:00')
        ->and(Chrono::parseDate('February 15 1998', '2012-08-10')?->toDateTimeString())
        ->toBe('1998-02-15 12:00:00');
});

it('does not parse random numeric text as dates or times', function () {
    expect(Chrono::parse(' 3', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('       1', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('  11 ', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse(' 0.5 ', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse(' 35.49 ', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('12.53%', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('6358fe2310> *5.0* / 5 Outstanding', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('6358fe2310> *1.5* / 5 Outstanding', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('Total: $1,194.09 [image: View Reservation', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('%e7%b7%8a', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('https://tenor.com/view/%e3%83%89%e3%82%ad%e3%83%89%e3%82%ad-%e7%b7%8a%e5%bc%b5-%e5%a5%bd%e3%81%8d-%e3%83%8f%e3%83%bc%e3%83%88-%e5%8f%af%e6%84%9b%e3%81%84-gif-15876325', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('at 6.5 kilograms', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('ah that is unusual', '2012-08-10', ['forwardDate' => true]))
        ->toBe([])
        ->and(Chrono::parse('14PM', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('1-2', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('1-2-3', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('4-5-6', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('20-30-12', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2012', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2012-14', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2012-1400', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2200-25', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('An appointment on 13/31/2018', '2012-08-10'))
        ->toBe([]);
});

it('does not parse version numbers as dates', function () {
    expect(Chrono::parse('Version: 1.1.3', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('Version: 1.1.30', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('Version: 1.10.30', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('1.5.3 - 2015-09-24', '2012-08-10')[0]->text)
        ->toBe('2015-09-24');
});

it('parses slash month year shorthand', function () {
    $result = Chrono::parse('The event is going ahead (04/2016)', '2012-08-10')[0];

    expect($result->text)->toBe('04/2016')
        ->and($result->index)->toBe(26)
        ->and($result->start->date()->toDateTimeString())->toBe('2016-04-01 12:00:00')
        ->and($result->start->isCertain('year'))->toBeTrue()
        ->and($result->start->isCertain('month'))->toBeTrue()
        ->and($result->start->isCertain('day'))->toBeFalse()
        ->and(Chrono::parseDate('Published: 06/2004', '2012-08-10')?->toDateTimeString())
        ->toBe('2004-06-01 12:00:00');
});

it('parses slash dates with leading slash and inferred day month order', function () {
    $plain = Chrono::parse('8/10/2012', '2012-08-10')[0];
    $twoDigitPastYear = Chrono::parse('8/10/82', '2012-08-10')[0];

    expect($plain->text)
        ->toBe('8/10/2012')
        ->and($plain->tags())->toContain('parser/SlashDateFormatParser')
        ->and($plain->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($twoDigitPastYear->start->get('year'))->toBe(1982)
        ->and($twoDigitPastYear->start->date()->toDateTimeString())->toBe('1982-08-10 12:00:00')
        ->and(Chrono::parse('/05/25/2015', '2012-08-10')[0]->text)
        ->toBe('/05/25/2015')
        ->and(Chrono::parseDate('/05/25/2015', '2012-08-10')?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parse('25/05/2015', '2012-08-10')[0]->text)
        ->toBe('25/05/2015')
        ->and(Chrono::parseDate('25/05/2015', '2012-08-10')?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parse('14/4 90', '2012-08-10')[0]->text)
        ->toBe('14/4')
        ->and(Chrono::parseDate('14/4 90', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-04-14 12:00:00');
});

it('parses upstream slash date splitter variants', function () {
    $reference = '2015-05-25';

    expect(Chrono::parseDate('2015-05-25', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('2015/05/25', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('2015.05.25', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('05-25-2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('05/25/2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('05.25.2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('/05/25/2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('25/05/2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00');
});

it('supports british english slash dates', function () {
    $british = Chrono::gb()->parseText('Book 6/10/2018', '2012-08-10')[0];
    $weekday = Chrono::british()->parseText('Friday 30-12-16', '2012-08-10')[0];

    expect(Chrono::parseDate('6/10/2018', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-06-10 12:00:00')
        ->and($british->text)->toBe('6/10/2018')
        ->and($british->start->date()->toDateTimeString())->toBe('2018-10-06 12:00:00')
        ->and(Chrono::enGb()->parseDateText('6/10/2018', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-10-06 12:00:00')
        ->and($weekday->text)->toBe('Friday 30-12-16')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ENSlashDateParser');
});

it('parses weekday prefixed slash dates', function () {
    $longYear = Chrono::parse('The Deadline is Tuesday 11/3/2015', '2015-11-03')[0];
    $shortYear = Chrono::parse('Friday 12-30-16', '2012-08-10')[0];
    $littleEndian = Chrono::parse('Friday 30-12-16', '2012-08-10')[0];

    expect($longYear->text)->toBe('Tuesday 11/3/2015')
        ->and($longYear->start->date()->toDateTimeString())->toBe('2015-11-03 12:00:00')
        ->and($longYear->start->isCertain('weekday'))->toBeTrue()
        ->and($longYear->start->tags())->toContain('parser/ENSlashDateParser')
        ->and($shortYear->text)->toBe('Friday 12-30-16')
        ->and($shortYear->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($littleEndian->text)->toBe('Friday 30-12-16')
        ->and($littleEndian->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00');
});

it('parses slash dates with month names and attached times', function () {
    $plain = Chrono::parse('06/Nov/2023:06:36:02', '2012-08-10')[0];
    $zoned = Chrono::parse('06/Nov/2023:06:36:02 +0200', '2012-08-10')[0];

    expect(Chrono::parseDate('8/Oct/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-10-08 12:00:00')
        ->and($plain->text)->toBe('06/Nov/2023:06:36:02')
        ->and($plain->start->date()->toDateTimeString())->toBe('2023-11-06 06:36:02')
        ->and($plain->start->tags())->toContain('parser/ENSlashDateParser')
        ->and($zoned->text)->toBe('06/Nov/2023:06:36:02 +0200')
        ->and($zoned->start->timezoneOffset())->toBe(120)
        ->and($zoned->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-11-06 06:36:02 +02:00');
});

it('parses month name dates with era labels', function () {
    expect(Chrono::parse('10 August 234 BCE', '2012-08-10')[0]->start->get('year'))
        ->toBe(-234)
        ->and(Chrono::parse('10 August 88 CE', '2012-08-10')[0]->start->get('year'))
        ->toBe(88)
        ->and(Chrono::parse('10 August 2555 BE', '2012-08-10')[0]->start->get('year'))
        ->toBe(2012);
});

it('parses casual dates with time', function () {
    $date = Chrono::parseDate('tomorrow at 4pm', '2026-06-23 09:00');

    expect($date?->toDateTimeString())->toBe('2026-06-24 16:00:00');
});

it('preserves the reference timestamp for now', function () {
    $result = Chrono::parse('The Deadline is now', '2012-08-10 08:09:10.011')[0];

    expect($result->text)->toBe('now')
        ->and($result->start->get('hour'))->toBe(8)
        ->and($result->start->get('minute'))->toBe(9)
        ->and($result->start->get('second'))->toBe(10)
        ->and($result->start->get('millisecond'))->toBe(11)
        ->and($result->start->isCertain('millisecond'))->toBeTrue()
        ->and($result->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011');
});

it('parses casual date aliases', function () {
    expect(Chrono::parseDate('tmr', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-24 09:00:00')
        ->and(Chrono::parseDate('tmrw', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-24 09:00:00')
        ->and(Chrono::parseDate('overmorrow', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-25 09:00:00');
});

it('supports casual and strict chrono variants', function () {
    $casual = Chrono::casual();
    $strict = Chrono::strict();

    expect($casual->parseDateText('tomorrow', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-24 09:00:00')
        ->and($strict->parseText('tomorrow', '2026-06-23 09:00'))->toBe([])
        ->and($strict->parseDateText('7:00PM July 5th, 2020', '2012-08-10')?->toDateTimeString())
        ->toBe('2020-07-05 19:00:00');
});

it('supports custom parsers that participate in refiners', function () {
    $christmas = new class implements Parser
    {
        public function parse(string $text, Reference $reference, Options $options): array
        {
            preg_match_all('/\bChristmas\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

            return array_map(function (array $match) use ($reference): ParsedResult {
                $date = CarbonImmutable::create($reference->date->year, 12, 25, 12, 0, 0, $reference->date->timezone);

                return new ParsedResult($match[0][1], $match[0][0], (new ParsedComponents($date, [
                    'year' => true,
                    'month' => true,
                    'day' => true,
                ]))->addTag('parser/ChristmasDayParser'));
            }, $matches);
        }
    };

    $chrono = Chrono::casual()->withParser($christmas);
    $result = $chrono->parseText("I'll arrive at 2:30AM on Christmas", '2017-11-19 12:00')[0];

    expect($result->text)->toBe('at 2:30AM on Christmas')
        ->and($result->start->date()->toDateTimeString())->toBe('2017-12-25 02:30:00')
        ->and($result->tags())->toContain('parser/ChristmasDayParser')
        ->and($result->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($result->tags())->toContain('refiner/mergeTimeFollowedByDate');

    $casualTime = $chrono->parseText('I will arrive at Christmas night', '2017-11-19 12:00')[0];

    expect($casualTime->text)->toBe('Christmas night')
        ->and($casualTime->start->date()->toDateTimeString())->toBe('2017-12-25 20:00:00')
        ->and($casualTime->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($casualTime->tags())->toContain('parser/ChristmasDayParser')
        ->and($casualTime->tags())->toContain('parser/ENCasualTimeParser')
        ->and($casualTime->tags())->toContain('refiner/mergeDateFollowedByTime');
});

it('supports custom refiners that mutate ambiguous parsed results like upstream chrono instances', function () {
    $afternoonGuess = new class implements Refiner
    {
        public function refine(string $text, array $results, Reference $reference, Options $options): array
        {
            foreach ($results as $result) {
                if (! $result->start->isCertain('meridiem')
                    && $result->start->get('hour') >= 1
                    && $result->start->get('hour') < 4) {
                    $result->start->assign('meridiem', Meridiem::PM->value);
                    $result->start->assign('hour', (int) $result->start->get('hour') + 12);
                }
            }

            return $results;
        }
    };

    $custom = Chrono::casual()->withRefiner($afternoonGuess);
    $ambiguous = $custom->parseText('This is at 2.30', '2016-10-01 08:00')[0];
    $explicit = $custom->parseText('This is at 2.30 AM', '2016-10-01 08:00')[0];

    expect($ambiguous->text)->toBe('at 2.30')
        ->and($ambiguous->start->get('hour'))->toBe(14)
        ->and($ambiguous->start->get('minute'))->toBe(30)
        ->and($ambiguous->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($explicit->text)->toBe('at 2.30 AM')
        ->and($explicit->start->get('hour'))->toBe(2)
        ->and($explicit->start->get('minute'))->toBe(30)
        ->and($explicit->start->get('meridiem'))->toBe(Meridiem::AM);
});

it('keeps parser order for same-index results like upstream chrono', function () {
    $short = new class implements Parser
    {
        public function parse(string $text, Reference $reference, Options $options): array
        {
            return [
                new ParsedResult(0, 'foo', new ParsedComponents($reference->date)),
            ];
        }
    };

    $long = new class implements Parser
    {
        public function parse(string $text, Reference $reference, Options $options): array
        {
            return [
                new ParsedResult(0, 'foobar', new ParsedComponents($reference->date)),
            ];
        }
    };

    $results = (new ConfiguredChronoEngine(new Configuration(
        parsers: [$short, $long],
        refiners: [],
    )))->parse('foobar', Reference::make('2026-06-23'), new Options);

    expect(array_map(fn (ParsedResult $result): string => $result->text, $results))
        ->toBe(['foo', 'foobar']);
});

it('clones parser and refiner configuration like upstream chrono instances', function () {
    $christmas = new class implements Parser
    {
        public function parse(string $text, Reference $reference, Options $options): array
        {
            preg_match_all('/\bChristmas\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

            return array_map(function (array $match) use ($reference): ParsedResult {
                $date = CarbonImmutable::create($reference->date->year, 12, 25, 12, 0, 0, $reference->date->timezone);

                return new ParsedResult($match[0][1], $match[0][0], new ParsedComponents($date, [
                    'year' => true,
                    'month' => true,
                    'day' => true,
                ]));
            }, $matches);
        }
    };

    $newYears = new class implements Parser
    {
        public function parse(string $text, Reference $reference, Options $options): array
        {
            preg_match_all('/\bNew Years\b/i', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

            return array_map(function (array $match) use ($reference): ParsedResult {
                $date = CarbonImmutable::create($reference->date->year + 1, 1, 1, 12, 0, 0, $reference->date->timezone);

                return new ParsedResult($match[0][1], $match[0][0], new ParsedComponents($date, [
                    'year' => true,
                    'month' => true,
                    'day' => true,
                ]));
            }, $matches);
        }
    };

    $original = Chrono::casual()->withParser($christmas);
    $clone = $original->clone()->withParser($newYears);

    expect($clone->parseDateText('Christmas', '2017-11-19')?->toDateTimeString())
        ->toBe('2017-12-25 12:00:00')
        ->and($clone->parseDateText('New Years', '2017-11-19')?->toDateTimeString())
        ->toBe('2018-01-01 12:00:00')
        ->and($original->parseDateText('New Years', '2017-11-19'))
        ->toBeNull();
});

it('removes and replaces parser configuration like upstream chrono instances', function () {
    $strict = Chrono::strict();
    $replaced = $strict
        ->clone()
        ->withoutParser(EnSlashDateParser::class)
        ->withParser(new EnSlashDateParser(littleEndian: true), prepend: true);

    expect($strict->parseDateText('6/10/2018', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-06-10 12:00:00')
        ->and($strict->parseText('6/10/2018', '2012-08-10')[0]->start->tags())
        ->toContain('parser/SlashDateFormatParser')
        ->and($replaced->parseDateText('6/10/2018', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-10-06 12:00:00')
        ->and($replaced->parseText('6/10/2018', '2012-08-10')[0]->start->tags())
        ->toContain('parser/SlashDateFormatParser');
});

it('replaces casual relative time unit parser options like upstream chrono instances', function () {
    $custom = Chrono::casual()
        ->clone()
        ->withoutParser(EnTimeUnitCasualRelativeFormatParser::class)
        ->withParser(new EnTimeUnitCasualRelativeFormatParser(allowAbbreviations: false));

    expect(Chrono::parseDate('next 5m', '2016-10-01 14:52')?->toDateTimeString())
        ->toBe('2016-10-01 14:57:00')
        ->and($custom->parseText('next 5m', '2016-10-01 14:52'))
        ->toBe([])
        ->and($custom->parseDateText('next 5 minutes', '2016-10-01 14:52')?->toDateTimeString())
        ->toBe('2016-10-01 14:57:00');
});

it('removes refiner configuration like upstream chrono instances', function () {
    $forceYear = new class implements Refiner
    {
        public function refine(string $text, array $results, Reference $reference, Options $options): array
        {
            foreach ($results as $result) {
                $result->start->assign('year', 2030);
            }

            return $results;
        }
    };

    $custom = Chrono::casual()->withRefiner($forceYear);
    $withoutRefiner = $custom->clone()->withoutRefiner($forceYear::class);

    expect($custom->parseDateText('tomorrow', '2012-08-10')?->toDateTimeString())
        ->toBe('2030-08-11 00:00:00')
        ->and($withoutRefiner->parseDateText('tomorrow', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00');
});

it('combines parsed result tags from result start and end components', function () {
    $start = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 09:00')))->addTag('custom/start');
    $end = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 10:00')))->addTag('custom/end');
    $result = new ParsedResult(0, '9-10', $start, $end, ['custom/result']);

    $result->addTag('custom/added');
    $result->addTags(['custom/batch']);

    expect($result->tags())->toContain('custom/start')
        ->and($result->tags())->toContain('custom/end')
        ->and($result->tags())->toContain('custom/result')
        ->and($result->tags())->toContain('custom/added')
        ->and($result->tags())->toContain('custom/batch')
        ->and($result->start->tags())->toContain('custom/added')
        ->and($result->end?->tags())->toContain('custom/added')
        ->and($result->start->tags())->toContain('custom/batch')
        ->and($result->end?->tags())->toContain('custom/batch');
});

it('exposes parsed result date clone and string helpers', function () {
    $start = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 09:00')))->addTag('custom/start');
    $end = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 10:00')))->addTag('custom/end');
    $result = new ParsedResult(4, '9-10', $start, $end, ['custom/result']);

    $clone = $result->clone();
    $clone->start->assign('hour', 11)->addTag('custom/clone');

    expect($result->date()->toDateTimeString())->toBe('2026-06-23 12:00:00')
        ->and($clone->date()->toDateTimeString())->toBe('2026-06-23 11:00:00')
        ->and($result->date()->toDateTimeString())->toBe('2026-06-23 12:00:00')
        ->and($result->tags())->not->toContain('custom/clone')
        ->and($clone->tags())->not->toContain('custom/start')
        ->and($clone->tags())->not->toContain('custom/end')
        ->and($clone->tags())->not->toContain('custom/result')
        ->and($clone->tags())->toContain('custom/clone')
        ->and((string) $result)->toContain('index: 4')
        ->and((string) $result)->toContain("text: '9-10'")
        ->and((string) $result)->toContain('custom/start')
        ->and((string) $result)->toContain('custom/end');
});

it('manipulates parsed components like upstream parsing components', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:00:00'), [
        'year' => true,
        'month' => true,
        'day' => true,
    ]);

    expect($components->get('weekday'))->toBeNull()
        ->and($components->isCertain('weekday'))->toBeFalse();

    $components->imply('weekday', 1);

    expect($components->get('weekday'))->toBe(1)
        ->and($components->isCertain('weekday'))->toBeFalse();

    $components->assign('weekday', 2);
    $components->imply('year', 2013);
    $components->assign('year', 2013);
    $components->addTags(['custom/one', 'custom/two']);

    $clone = $components->clone();
    $clone->delete(['weekday', 'year'])->addTag('custom/clone');

    expect($components->get('weekday'))->toBe(2)
        ->and($components->isCertain('weekday'))->toBeTrue()
        ->and($components->get('year'))->toBe(2013)
        ->and($components->getCertainComponents())->toContain('year')
        ->and($components->getCertainComponents())->toContain('month')
        ->and($components->getCertainComponents())->toContain('day')
        ->and($components->getCertainComponents())->toContain('weekday')
        ->and($components->tags())->toContain('custom/one')
        ->and($components->tags())->toContain('custom/two')
        ->and($components->isOnlyDate())->toBeTrue()
        ->and($clone->isCertain('weekday'))->toBeFalse()
        ->and($clone->isCertain('year'))->toBeFalse()
        ->and($clone->get('year'))->toBeNull()
        ->and($clone->tags())->not->toContain('custom/one')
        ->and($clone->tags())->not->toContain('custom/two')
        ->and($clone->tags())->toContain('custom/clone')
        ->and($components->tags())->not->toContain('custom/clone')
        ->and((string) $components)->toContain('custom/one')
        ->and((string) $components)->toContain('knownValues')
        ->and((string) $components)->toContain('impliedValues');
});

it('derives component dates from constructor values like upstream parsing components', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2012-08-10 12:00:00'), [
        'year' => 2020,
        'month' => 4,
        'day' => 5,
        'hour' => 6,
        'minute' => 7,
    ]);

    expect($components->date()->toDateTimeString())->toBe('2020-04-05 06:07:00')
        ->and($components->get('year'))->toBe(2020)
        ->and($components->get('month'))->toBe(4)
        ->and($components->get('day'))->toBe(5)
        ->and($components->get('hour'))->toBe(6)
        ->and($components->get('minute'))->toBe(7);
});

it('does not infer meridiem from hour-only components like upstream parsing components', function () {
    $components = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 12:00:00')))
        ->assign('hour', 15);

    expect($components->get('hour'))->toBe(15)
        ->and($components->get('meridiem'))->toBeNull()
        ->and($components->isCertain('meridiem'))->toBeFalse();
});

it('creates relative parsed components from references like upstream parsing components', function () {
    $reference = Reference::make('2022-08-27 12:52:11');

    $empty = ParsedComponents::createRelativeFromReference($reference);
    $days = ParsedComponents::createRelativeFromReference($reference, ['day' => 3]);
    $dateTime = ParsedComponents::createRelativeFromReference($reference, ['day' => 1, 'hour' => 3]);
    $weeks = ParsedComponents::createRelativeFromReference($reference, ['week' => 1]);
    $months = ParsedComponents::createRelativeFromReference($reference, ['month' => 1]);
    $years = ParsedComponents::createRelativeFromReference($reference, ['year' => 1]);

    expect($empty->date()->toDateTimeString())->toBe('2022-08-27 12:52:11')
        ->and($empty->isCertain('day'))->toBeTrue()
        ->and($empty->isCertain('hour'))->toBeTrue()
        ->and($empty->tags())->toContain('result/relativeDate')
        ->and($empty->tags())->toContain('result/relativeDateAndTime')
        ->and($days->date()->toDateTimeString())->toBe('2022-08-30 12:52:11')
        ->and($days->isCertain('day'))->toBeTrue()
        ->and($days->isCertain('hour'))->toBeFalse()
        ->and($dateTime->date()->toDateTimeString())->toBe('2022-08-28 15:52:11')
        ->and($dateTime->isCertain('day'))->toBeTrue()
        ->and($dateTime->isCertain('hour'))->toBeTrue()
        ->and($dateTime->tags())->toContain('result/relativeDateAndTime')
        ->and($weeks->date()->toDateTimeString())->toBe('2022-09-03 12:52:11')
        ->and($weeks->isCertain('day'))->toBeTrue()
        ->and($weeks->isCertain('weekday'))->toBeFalse()
        ->and($months->date()->toDateTimeString())->toBe('2022-09-27 12:52:11')
        ->and($months->isCertain('month'))->toBeTrue()
        ->and($months->isCertain('day'))->toBeFalse()
        ->and($years->date()->toDateTimeString())->toBe('2023-08-27 12:52:11')
        ->and($years->isCertain('year'))->toBeTrue()
        ->and($years->isCertain('month'))->toBeFalse();
});

it('creates casual last-night components like upstream common references', function () {
    $early = CasualReferences::lastNight(Reference::make('2012-08-10 01:00'));
    $morningBoundary = CasualReferences::lastNight(Reference::make('2012-08-10 06:00'));
    $midday = CasualReferences::lastNight(Reference::make('2012-08-10 12:00'));

    expect($early->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($early->get('hour'))->toBe(0)
        ->and($morningBoundary->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($midday->date()->toDateTimeString())->toBe('2012-08-10 00:00:00');
});

it('adds durations as implied parsed component values', function () {
    $time = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);

    $time->addDurationAsImplied(['hour' => 3]);

    $date = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);

    $date->addDurationAsImplied(['day' => 3]);

    $millisecond = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11.400'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
        'millisecond' => true,
    ]);

    $millisecond->addDurationAsImplied(['millisecond' => 250]);

    expect($time->date()->toDateTimeString())->toBe('2022-08-27 15:52:11')
        ->and($time->get('hour'))->toBe(15)
        ->and($time->isCertain('hour'))->toBeFalse()
        ->and($time->isCertain('minute'))->toBeFalse()
        ->and($time->isCertain('second'))->toBeFalse()
        ->and($time->isCertain('day'))->toBeTrue()
        ->and($date->date()->toDateTimeString())->toBe('2022-08-30 12:52:11')
        ->and($date->get('day'))->toBe(30)
        ->and($date->isCertain('day'))->toBeFalse()
        ->and($date->isCertain('weekday'))->toBeFalse()
        ->and($date->isCertain('month'))->toBeFalse()
        ->and($date->isCertain('year'))->toBeFalse()
        ->and($date->isCertain('hour'))->toBeTrue()
        ->and($millisecond->date()->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.400')
        ->and($millisecond->get('millisecond'))->toBe(400)
        ->and($millisecond->isCertain('millisecond'))->toBeTrue();
});

it('adds fractional durations using upstream duration cascading', function () {
    $halfYear = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfYear->addDurationAsImplied(['year' => 0.5]);

    $halfMonth = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfMonth->addDurationAsImplied(['month' => 0.5]);

    $halfWeek = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfWeek->addDurationAsImplied(['week' => 0.5]);

    $halfHour = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfHour->addDurationAsImplied(['hour' => 0.5]);

    $shortMonth = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $shortMonth->addDurationAsImplied(['M' => 1]);

    expect($halfYear->date()->toDateTimeString())->toBe('2023-02-27 12:52:11')
        ->and($halfYear->isCertain('month'))->toBeFalse()
        ->and($halfMonth->date()->toDateTimeString())->toBe('2022-09-10 12:52:11')
        ->and($halfMonth->isCertain('day'))->toBeFalse()
        ->and($halfWeek->date()->toDateTimeString())->toBe('2022-08-31 12:52:11')
        ->and($halfWeek->get('day'))->toBe(31)
        ->and($halfHour->date()->toDateTimeString())->toBe('2022-08-27 13:22:11')
        ->and($halfHour->get('minute'))->toBe(22)
        ->and($halfHour->isCertain('minute'))->toBeFalse()
        ->and($shortMonth->date()->toDateTimeString())->toBe('2022-09-27 12:52:11');
});

it('adds durations as implied values using timezone-adjusted references', function () {
    $jstReference = Reference::make([
        'instant' => 'Thu Feb 27 2025 17:00:00 GMT+0000',
        'timezone' => 'JST',
    ]);
    $jst = ParsedComponents::createRelativeFromReference($jstReference);

    $pstReference = Reference::make([
        'instant' => 'Thu Feb 27 2025 17:00:00 GMT+0000',
        'timezone' => 'PST',
    ]);
    $pst = ParsedComponents::createRelativeFromReference($pstReference);

    $jst->addDurationAsImplied(['hour' => 3]);
    $pst->addDurationAsImplied(['hour' => 3]);

    expect($jstReference->date->format('Y-m-d H:i:s P'))->toBe('2025-02-28 02:00:00 +09:00')
        ->and($jst->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 05:00:00 +09:00')
        ->and($jst->isCertain('hour'))->toBeFalse()
        ->and($pstReference->date->format('Y-m-d H:i:s P'))->toBe('2025-02-27 09:00:00 -08:00')
        ->and($pst->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-27 12:00:00 -08:00')
        ->and($pst->isCertain('hour'))->toBeFalse();

    $jst->addDurationAsImplied(['day' => 3]);
    $pst->addDurationAsImplied(['day' => 3]);

    expect($jst->date()->format('Y-m-d H:i:s P'))->toBe('2025-03-03 05:00:00 +09:00')
        ->and($jst->isCertain('day'))->toBeFalse()
        ->and($pst->date()->format('Y-m-d H:i:s P'))->toBe('2025-03-02 12:00:00 -08:00')
        ->and($pst->isCertain('day'))->toBeFalse();
});

it('exposes timezone-adjusted reference dates like upstream references', function () {
    $numeric = Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT+0900',
        'timezone' => 540,
    ]);
    $named = Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT+0900',
        'timezone' => 'JST',
    ]);
    $adjusted = Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT-0500',
        'timezone' => 'CDT',
    ]);

    expect($numeric->getDateWithAdjustedTimezone()->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 +09:00')
        ->and($numeric->getTimezoneOffset())->toBe(540)
        ->and($named->getDateWithAdjustedTimezone()->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 +09:00')
        ->and($named->getTimezoneOffset())->toBe(540)
        ->and($adjusted->getDateWithAdjustedTimezone()->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 -05:00')
        ->and($adjusted->getTimezoneOffset())->toBe(-300);
});

it('validates assigned parsed component time bounds before carbon normalization', function () {
    $valid = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'));
    $validCalendar = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'), [
        'year' => 2014,
        'month' => 11,
        'day' => 24,
        'hour' => 12,
        'minute' => 30,
        'second' => 30,
    ]);
    $validWithImpliedTimezone = new ParsedComponents(CarbonImmutable::parse('2021-03-13 14:22:14'), [
        'year' => 2021,
        'month' => 3,
        'day' => 13,
        'hour' => 14,
        'minute' => 22,
        'second' => 14,
        'millisecond' => 0,
    ]);
    $invalidMonth = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'), [
        'year' => 2014,
        'month' => 13,
        'day' => 24,
    ]);
    $invalidDay = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'), [
        'year' => 2014,
        'month' => 11,
        'day' => 32,
    ]);
    $validBeforeCommonEraLeapDay = new ParsedComponents(CarbonImmutable::parse('-0240-02-29 12:30:30'), [
        'year' => -240,
        'month' => 2,
        'day' => 29,
    ]);
    $invalidBeforeCommonEraDay = new ParsedComponents(CarbonImmutable::parse('-0234-02-28 12:30:30'), [
        'year' => -234,
        'month' => 2,
        'day' => 31,
    ]);
    $invalidHour = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('hour', 24);
    $invalidNegativeHour = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('hour', -1);
    $invalidMinute = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('minute', 60);
    $invalidSecond = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('second', 60);
    $validMillisecondOverflow = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('millisecond', 1000);

    $validWithImpliedTimezone->imply('timezoneOffset', -300);

    expect($valid->isValidDate())->toBeTrue()
        ->and($validCalendar->isValidDate())->toBeTrue()
        ->and($validWithImpliedTimezone->isValidDate())->toBeTrue()
        ->and($validWithImpliedTimezone->isCertain('timezoneOffset'))->toBeFalse()
        ->and($validWithImpliedTimezone->date()->format('Y-m-d H:i:s P'))->toBe('2021-03-13 14:22:14 -05:00')
        ->and($invalidMonth->isValidDate())->toBeFalse()
        ->and($invalidDay->isValidDate())->toBeFalse()
        ->and($validBeforeCommonEraLeapDay->isValidDate())->toBeTrue()
        ->and($invalidBeforeCommonEraDay->isValidDate())->toBeFalse()
        ->and($invalidHour->get('hour'))->toBe(24)
        ->and($invalidHour->isValidDate())->toBeFalse()
        ->and($invalidNegativeHour->get('hour'))->toBe(-1)
        ->and($invalidNegativeHour->isValidDate())->toBeFalse()
        ->and($invalidMinute->get('minute'))->toBe(60)
        ->and($invalidMinute->isValidDate())->toBeFalse()
        ->and($invalidSecond->get('second'))->toBe(60)
        ->and($invalidSecond->isValidDate())->toBeFalse()
        ->and($validMillisecondOverflow->get('millisecond'))->toBe(1000)
        ->and($validMillisecondOverflow->date()->format('Y-m-d H:i:s.v'))->toBe('2014-11-24 12:00:01.000')
        ->and($validMillisecondOverflow->isValidDate())->toBeTrue();
});

it('detects only-time components by absence of certain date fields', function () {
    $impliedTime = new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00'));
    $impliedTime->imply('hour', 8);

    $weekday = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))->assign('weekday', 2);
    $date = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))->assign('day', 23);

    expect($impliedTime->isCertain('hour'))->toBeFalse()
        ->and($impliedTime->isOnlyTime())->toBeTrue()
        ->and($weekday->isOnlyTime())->toBeFalse()
        ->and($date->isOnlyTime())->toBeFalse();
});

it('detects only-weekday components like upstream parsing components', function () {
    $weekday = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))->assign('weekday', 2);
    $weekdayWithYear = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))
        ->assign('weekday', 2)
        ->assign('year', 2026);
    $weekdayWithHour = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))
        ->assign('weekday', 2)
        ->assign('hour', 8);
    $weekdayWithDay = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))
        ->assign('weekday', 2)
        ->assign('day', 23);

    expect($weekday->isOnlyWeekdayComponent())->toBeTrue()
        ->and($weekdayWithYear->isOnlyWeekdayComponent())->toBeTrue()
        ->and($weekdayWithHour->isOnlyWeekdayComponent())->toBeTrue()
        ->and($weekdayWithDay->isOnlyWeekdayComponent())->toBeFalse();
});

it('detects unknown-year dates by month certainty like upstream parsing components', function () {
    $monthOnly = (new ParsedComponents(CarbonImmutable::parse('2026-06-01 12:00')))->assign('month', 6);
    $monthDay = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 12:00')))
        ->assign('month', 6)
        ->assign('day', 23);
    $explicitYear = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 12:00')))
        ->assign('month', 6)
        ->assign('year', 2026);

    expect($monthOnly->isDateWithUnknownYear())->toBeTrue()
        ->and($monthDay->isDateWithUnknownYear())->toBeTrue()
        ->and($explicitYear->isDateWithUnknownYear())->toBeFalse()
        ->and(Chrono::parseDate('in May', '2026-06-23', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2027-05-01 12:00:00');
});

it('supports custom refiners', function () {
    $afternoonAmbiguousTimes = new class implements Refiner
    {
        public function refine(string $text, array $results, Reference $reference, Options $options): array
        {
            foreach ($results as $result) {
                if (
                    ! $result->start->isCertain('meridiem')
                    && $result->start->get('hour') >= 1
                    && $result->start->get('hour') < 4
                ) {
                    $result->start->assign('meridiem', Meridiem::PM->value);
                    $result->start->assign('hour', $result->start->get('hour') + 12);
                }
            }

            return $results;
        }
    };

    $ambiguousTime = Chrono::casual()
        ->withRefiner($afternoonAmbiguousTimes)
        ->parseText('This is at 2.30', '2026-06-23 09:00')[0];

    $explicitMorning = Chrono::casual()
        ->withRefiner($afternoonAmbiguousTimes)
        ->parseText('This is at 2.30 AM', '2026-06-23 09:00')[0];

    $onlyTomorrow = new class implements Refiner
    {
        public function refine(string $text, array $results, Reference $reference, Options $options): array
        {
            return array_filter($results, fn (ParsedResult $result): bool => $result->text === 'tomorrow');
        }
    };

    $results = Chrono::casual()
        ->withRefiner($onlyTomorrow)
        ->parseText('today and tomorrow', '2026-06-23 09:00');

    expect($ambiguousTime->text)->toBe('at 2.30')
        ->and($ambiguousTime->start->date()->toDateTimeString())->toBe('2026-06-23 14:30:00')
        ->and($explicitMorning->text)->toBe('at 2.30 AM')
        ->and($explicitMorning->start->date()->toDateTimeString())->toBe('2026-06-23 02:30:00')
        ->and($results)->toHaveCount(1)
        ->and($results[0]->text)->toBe('tomorrow')
        ->and($results[0]->start->date()->toDateTimeString())->toBe('2026-06-24 09:00:00');
});

it('parses spanish casual dates and times', function () {
    $spanish = Chrono::spanish();
    $now = $spanish->parseText('La fecha limite es ahora', '2012-08-10 08:09:10.011')[0];

    expect($now->text)->toBe('ahora')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/ESCasualDateParser')
        ->and($spanish->parseDateText('La fecha limite es hoy', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($spanish->parseDateText('La fecha limite es Mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00')
        ->and($spanish->parseDateText('La fecha limite fue ayer', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($spanish->parseDateText('ayer de noche', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 22:00:00')
        ->and($spanish->parseDateText('esta mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($spanish->parseDateText('esta tarde', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($spanish->parseDateText('esta noche', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 22:00:00')
        ->and($spanish->parseDateText('esta noche 8pm', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($spanish->parseDateText('esta noche a las 8', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($spanish->parseDateText('La fecha límite es hoy a las 5PM', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00')
        ->and($spanish->parseDateText('el mediodía', '2020-09-01 11:00')?->toDateTimeString())
        ->toBe('2020-09-01 12:00:00')
        ->and($spanish->parseDateText('la medianoche', '2020-09-01 11:00')?->toDateTimeString())
        ->toBe('2020-09-02 00:00:00');
});

it('parses spanish casual time references', function () {
    $spanish = Chrono::spanish();

    expect($spanish->parseText('Nos vemos esta mañana', '2012-08-10 12:00')[0]->text)
        ->toBe('esta mañana')
        ->and($spanish->parseText('Nos vemos tarde', '2012-08-10 12:00')[0]->start->tags())->toContain('parser/ESCasualTimeParser')
        ->and($spanish->parseDateText('Nos vemos esta mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($spanish->parseDateText('Nos vemos tarde', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($spanish->parseDateText('Nos vemos noche', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 22:00:00')
        ->and($spanish->parseDateText('Nos vemos mediodía', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($spanish->parseDateText('Nos vemos medianoche', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($spanish->parseDateText('Nos vemos mañana', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00');
});

it('parses german casual dates and times', function () {
    $german = Chrono::de();
    $now = $german->parseText('Die Deadline ist jetzt', '2012-08-10 08:09:10.011')[0];

    expect($now->text)->toBe('jetzt')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/DECasualDateParser')
        ->and($german->parseDateText('Die Deadline ist heute', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($german->parseDateText('Die Deadline ist morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00')
        ->and($german->parseDateText('Die Deadline war gestern', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($german->parseDateText('Die Deadline war letzte Nacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and($german->parseDateText('Die Deadline war gestern Nacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 22:00:00')
        ->and($german->parseDateText('Die Deadline war heute Morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($german->parseDateText('Die Deadline war heute Nachmittag', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($german->parseDateText('Die Deadline war heute Abend', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($german->parseDateText('Die Deadline ist mittags', '2012-08-10 08:09:10.011')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($german->parseDateText('um Mitternacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($german->parseDateText('um Mitternacht', '2012-08-10 01:00')?->toDateTimeString())
        ->toBe('2012-08-10 00:00:00')
        ->and($german->parseDateText('Die Deadline ist heute 17 Uhr', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00')
        ->and($german->parseDateText('Die Deadline ist heute um 17 Uhr', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00');
});

it('parses german casual time references', function () {
    $german = Chrono::de();

    expect($german->parseText('Treffen wir uns vormittag', '2012-08-10 12:00')[0]->text)
        ->toBe('vormittag')
        ->and($german->parseText('Treffen wir uns vormittag', '2012-08-10 12:00')[0]->start->tags())
        ->toContain('parser/DECasualTimeParser')
        ->and($german->parseDateText('Treffen wir uns vormittag', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 09:00:00')
        ->and($german->parseDateText('Treffen wir uns nachmittag', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($german->parseDateText('Treffen wir uns abend', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($german->parseDateText('Treffen wir uns nacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 22:00:00')
        ->and($german->parseDateText('Treffen wir uns diesen morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($german->parseDateText('Treffen wir uns mitternacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($german->parseDateText('Die Deadline ist morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00');
});

it('parses german casual date ranges and daypart variants', function () {
    $german = Chrono::de();
    $earlyRange = $german->parseText('Der Event ist heute - nächsten Freitag', '2012-08-04 12:00')[0];
    $sameDayRange = $german->parseText('Der Event ist heute - nächsten Freitag', '2012-08-10 12:00')[0];

    expect($earlyRange->text)->toBe('heute - nächsten Freitag')
        ->and($earlyRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($earlyRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->end?->date()->toDateTimeString())->toBe('2012-08-17 12:00:00')
        ->and($german->parseDateText('heute Nacht', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 22:00:00')
        ->and($german->parseDateText('heute Nacht um 20 Uhr', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($german->parseDateText('heute Abend um 8', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($german->parseDateText('gestern Nachmittag', '2016-10-01')?->toDateTimeString())
        ->toBe('2016-09-30 15:00:00')
        ->and($german->parseDateText('morgen Morgen', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-10-02 06:00:00')
        ->and($german->parseDateText('uebermorgen Abend', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-10-03 18:00:00')
        ->and($german->parseDateText('vorgestern Vormittag', '2016-10-01')?->toDateTimeString())
        ->toBe('2016-09-29 09:00:00');
});

it('parses german relative durations', function () {
    $german = Chrono::de();
    $fiveDays = $german->parseText('Wir müssen etwas in 5 Tagen erledigen.', '2012-08-10')[0];
    $fiveDaysWord = $german->parseText('Wir müssen etwas in fünf Tagen erledigen.', '2012-08-10 11:12')[0];
    $timer = $german->parseText('starte einen Timer für 5 Minuten', '2012-08-10 12:14')[0];
    $home = $german->parseText('In 5 Minuten gehe ich nach Hause', '2012-08-10 12:14')[0];
    $seconds = $german->parseText('In 5 Sekunden wird ein Auto fahren', '2012-08-10 12:14')[0];
    $abbreviated = $german->parseText('In 5 Min wird ein Auto fahren', '2012-08-10 12:14')[0];

    expect($fiveDays->text)
        ->toBe('in 5 Tagen')
        ->and($fiveDays->index)->toBe(18)
        ->and($fiveDays->tags())->toContain('result/relativeDate')
        ->and($fiveDays->tags())->toContain('parser/DETimeUnitRelativeFormatParser')
        ->and($fiveDays->start->date()->toDateTimeString())
        ->toBe('2012-08-15 00:00:00')
        ->and($fiveDaysWord->text)->toBe('in fünf Tagen')
        ->and($fiveDaysWord->index)->toBe(18)
        ->and($fiveDaysWord->start->date()->toDateTimeString())
        ->toBe('2012-08-15 11:12:00')
        ->and($german->parseDateText('in 5 Minuten', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($german->parseDateText('für 5 minuten', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($german->parseDateText('in einer Stunde', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($timer->index)->toBe(19)
        ->and($timer->text)
        ->toBe('für 5 Minuten')
        ->and($home->text)->toBe('In 5 Minuten')
        ->and($home->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($seconds->text)->toBe('In 5 Sekunden')
        ->and($seconds->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:14:05')
        ->and($german->parseDateText('in zwei Wochen', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-24 12:14:00')
        ->and($german->parseDateText('in einem Monat', '2012-08-10 07:14')?->toDateTimeString())
        ->toBe('2012-09-10 07:14:00')
        ->and($german->parseDateText('in einigen Monaten', '2012-07-10 22:14')?->toDateTimeString())
        ->toBe('2012-10-10 22:14:00')
        ->and($german->parseDateText('in einem Jahr', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($german->parseDateText('in 20 Jahren', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2032-08-10 12:14:00')
        ->and($abbreviated->text)->toBe('In 5 Min')
        ->and($abbreviated->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00');
});

it('parses german casual relative units', function () {
    $german = Chrono::de();
    $days = $german->parseText('in den 30 vorangegangenen Tagen', '2017-05-12')[0];
    $hours = $german->parseText('die vergangenen 24 Stunden', '2017-05-12 11:27')[0];
    $seconds = $german->parseText('in den folgenden 90 sekunden', '2017-05-12 11:27:03')[0];
    $minutes = $german->parseText('die letzten acht Minuten', '2017-05-12 11:27')[0];
    $quarter = $german->parseText('letztes Quartal', '2017-05-12 11:27')[0];
    $year = $german->parseText('kommendes Jahr', '2017-05-12 11:27')[0];

    expect($german->parseDateText('kommende Woche', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-05-19 00:00:00')
        ->and($german->parseDateText('in drei Wochen', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-06-02 00:00:00')
        ->and($german->parseDateText('letzten Monat', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-04-12 00:00:00')
        ->and($days->text)->toBe('30 vorangegangenen Tagen')
        ->and($days->tags())->toContain('result/relativeDate')
        ->and($days->tags())->toContain('parser/DETimeUnitRelativeFormatParser')
        ->and($days->start->date()->toDateTimeString())->toBe('2017-04-12 00:00:00')
        ->and($hours->text)->toBe('vergangenen 24 Stunden')
        ->and($hours->start->date()->toDateTimeString())->toBe('2017-05-11 11:27:00')
        ->and($seconds->text)->toBe('folgenden 90 sekunden')
        ->and($seconds->start->date()->toDateTimeString())->toBe('2017-05-12 11:28:33')
        ->and($minutes->text)->toBe('letzten acht Minuten')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2017-05-12 11:19:00')
        ->and($quarter->text)->toBe('letztes Quartal')
        ->and($quarter->start->date()->toDateTimeString())->toBe('2017-02-12 11:27:00')
        ->and($quarter->start->isCertain('month'))->toBeFalse()
        ->and($quarter->start->isCertain('day'))->toBeFalse()
        ->and($quarter->start->isCertain('hour'))->toBeFalse()
        ->and($year->text)->toBe('kommendes Jahr')
        ->and($year->start->date()->toDateTimeString())->toBe('2018-05-12 11:27:00')
        ->and($year->start->isCertain('month'))->toBeFalse()
        ->and($year->start->isCertain('day'))->toBeFalse()
        ->and($year->start->isCertain('hour'))->toBeFalse()
        ->and($year->start->isCertain('minute'))->toBeFalse()
        ->and($year->start->isCertain('second'))->toBeFalse()
        ->and($german->parseText('Letzte Aktualisierun 03/12/2025')[0]->text)
        ->toBe('03/12/2025')
        ->and($german->parseText('Letzte Aktualisierun 03/12/2025')[0]->start->date()->toDateTimeString())
        ->toBe('2025-12-03 12:00:00');
});

it('parses german weekdays', function () {
    $german = Chrono::de();
    $monday = $german->parseText('Montag', '2012-08-09')[0];
    $lastFriday = $german->parseText('Die Deadline war letzten Freitag...', '2012-08-09')[0];
    $nextFriday = $german->parseText('Treffen wir uns am Freitag nächste Woche', '2015-04-18')[0];
    $nextTuesday = $german->parseText('Ich habe vor, am Dienstag nächste Woche freizunehmen', '2015-04-18')[0];
    $range = $german->parseText('diesen Freitag bis diesen Montag', '2016-08-04', ['forwardDate' => true])[0];
    $monthOverlap = $german->parseText('Sonntag, den 7. Dezember 2014', '2012-08-09')[0];
    $dashOverlap = $german->parseText('Sonntag 7.12.2014', '2012-08-09')[0];

    expect($monday->text)->toBe('Montag')
        ->and($monday->index)->toBe(0)
        ->and($monday->start->date()->toDateTimeString())
        ->toBe('2012-08-06 12:00:00')
        ->and($monday->start->tags())->toContain('parser/DEWeekdayParser')
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($german->parseText('am Donnerstag', '2012-08-09')[0]->text)->toBe('am Donnerstag')
        ->and($german->parseDateText('am Donnerstag', '2012-08-09')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($german->parseText('Sonntag', '2012-08-09')[0]->text)->toBe('Sonntag')
        ->and($german->parseText('Sonntag', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($german->parseDateText('Sonntag', '2012-08-09')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and($lastFriday->index)->toBe(17)
        ->and($lastFriday->text)
        ->toBe('letzten Freitag')
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($lastFriday->start->date()->toDateTimeString())
        ->toBe('2012-08-03 12:00:00')
        ->and($nextFriday->index)->toBe(16)
        ->and($nextFriday->text)
        ->toBe('am Freitag nächste Woche')
        ->and($nextFriday->start->date()->toDateTimeString())
        ->toBe('2015-04-24 12:00:00')
        ->and($nextTuesday->index)->toBe(14)
        ->and($nextTuesday->text)
        ->toBe('am Dienstag nächste Woche')
        ->and($nextTuesday->start->get('weekday'))->toBe(2)
        ->and($nextTuesday->start->date()->toDateTimeString())
        ->toBe('2015-04-21 12:00:00')
        ->and($range->index)->toBe(0)
        ->and($range->text)->toBe('diesen Freitag bis diesen Montag')
        ->and($range->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($range->start->get('weekday'))->toBe(5)
        ->and($range->start->isCertain('day'))->toBeFalse()
        ->and($range->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($range->end?->get('weekday'))->toBe(1)
        ->and($range->end?->isCertain('day'))->toBeFalse()
        ->and($monthOverlap->text)->toBe('Sonntag, den 7. Dezember 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($dashOverlap->text)->toBe('Sonntag 7.12.2014')
        ->and($dashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($dashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('weekday'))->toBeTrue();
});

it('parses german dash and dot numeric dates', function () {
    $german = Chrono::de();
    $dash = $german->parseText('30-12-16')[0];
    $prefixedDash = $german->parseText('Freitag 30-12-16')[0];
    $dot = $german->parseText('30.12.16')[0];
    $prefixedDot = $german->parseText('Freitag 30.12.16')[0];

    expect($dash->text)->toBe('30-12-16')
        ->and($dash->index)->toBe(0)
        ->and($dash->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($dash->start->get('year'))->toBe(2016)
        ->and($dash->start->get('month'))->toBe(12)
        ->and($dash->start->get('day'))->toBe(30)
        ->and($german->parseDateText('30-12-16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00')
        ->and($prefixedDash->text)
        ->toBe('Freitag 30-12-16')
        ->and($prefixedDash->index)->toBe(0)
        ->and($prefixedDash->start->tags())->toContain('parser/DEDashDateParser')
        ->and($prefixedDash->start->get('year'))->toBe(2016)
        ->and($prefixedDash->start->get('month'))->toBe(12)
        ->and($prefixedDash->start->get('day'))->toBe(30)
        ->and($german->parseDateText('Freitag 30-12-16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00')
        ->and($dot->text)->toBe('30.12.16')
        ->and($dot->start->tags())->toContain('parser/DEDashDateParser')
        ->and($dot->start->get('year'))->toBe(2016)
        ->and($dot->start->get('month'))->toBe(12)
        ->and($dot->start->get('day'))->toBe(30)
        ->and($german->parseDateText('30.12.16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00')
        ->and($prefixedDot->text)
        ->toBe('Freitag 30.12.16')
        ->and($prefixedDot->start->tags())->toContain('parser/DEDashDateParser')
        ->and($prefixedDot->start->get('year'))->toBe(2016)
        ->and($prefixedDot->start->get('month'))->toBe(12)
        ->and($prefixedDot->start->get('day'))->toBe(30)
        ->and($german->parseDateText('Freitag 30.12.16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00');
});

it('parses german month-name dates and ranges', function () {
    $german = Chrono::de();
    $ancient = $german->parseText('10. August 113 v. Chr.', '2012-08-10')[0];
    $commonEra = $german->parseText('10. August 85 n. Chr.', '2012-08-10')[0];
    $prefixed = $german->parseText('Die Deadline ist am Dienstag, den 10. Januar', '2012-08-10')[0];
    $abbreviatedWeekday = $german->parseText('Die Deadline ist Di, 10. Januar', '2012-08-10')[0];
    $sameMonthRange = $german->parseText('10. - 22. August 2012', '2012-08-10')[0];
    $crossMonthRange = $german->parseText('10. Oktober - 12. Dezember', '2012-08-10')[0];

    expect($german->parseText('10. August 2012', '2012-08-10')[0]->text)
        ->toBe('10. August 2012')
        ->and($german->parseText('10. August 2012', '2012-08-10')[0]->index)
        ->toBe(0)
        ->and($german->parseDateText('10. August 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($ancient->text)->toBe('10. August 113 v. Chr.')
        ->and($ancient->index)->toBe(0)
        ->and($ancient->start->get('year'))->toBe(-113)
        ->and($ancient->start->get('month'))->toBe(8)
        ->and($ancient->start->get('day'))->toBe(10)
        ->and($commonEra->text)->toBe('10. August 85 n. Chr.')
        ->and($commonEra->start->get('year'))->toBe(85)
        ->and($german->parseText('So 15.Sep', '2013-08-10')[0]->text)
        ->toBe('So 15.Sep')
        ->and($german->parseDateText('So 15.Sep', '2013-08-10')?->toDateTimeString())
        ->toBe('2013-09-15 12:00:00')
        ->and($german->parseText('SO 15.SEPT', '2013-08-10')[0]->text)
        ->toBe('SO 15.SEPT')
        ->and($german->parseDateText('SO 15.SEPT', '2013-08-10')?->toDateTimeString())
        ->toBe('2013-09-15 12:00:00')
        ->and($german->parseText('Die Deadline ist am 10. August', '2012-08-10')[0]->text)
        ->toBe('am 10. August')
        ->and($german->parseText('Die Deadline ist am 10. August', '2012-08-10')[0]->index)
        ->toBe(17)
        ->and($prefixed->text)
        ->toBe('am Dienstag, den 10. Januar')
        ->and($prefixed->index)->toBe(17)
        ->and($prefixed->start->get('weekday'))->toBe(2)
        ->and($prefixed->start->date()->toDateTimeString())
        ->toBe('2013-01-10 12:00:00')
        ->and($prefixed->start->tags())->toContain('parser/DEMonthNameParser')
        ->and($abbreviatedWeekday->text)
        ->toBe('Di, 10. Januar')
        ->and($abbreviatedWeekday->index)->toBe(17)
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(2)
        ->and($abbreviatedWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($german->parseDateText('31. März 2016', '2012-08-10')?->toDateTimeString())
        ->toBe('2016-03-31 12:00:00')
        ->and($german->parseDateText('31.Maerz 2016', '2012-08-10')?->toDateTimeString())
        ->toBe('2016-03-31 12:00:00')
        ->and($german->parseDateText('10. jänner 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-01-10 12:00:00')
        ->and($sameMonthRange->text)->toBe('10. - 22. August 2012')
        ->and($sameMonthRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($german->parseText('10. bis 22. Oktober 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-10-22 12:00:00')
        ->and($german->parseText('10. bis zum 22. Oktober 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-10-22 12:00:00')
        ->and($crossMonthRange->text)->toBe('10. Oktober - 12. Dezember')
        ->and($crossMonthRange->start->date()->toDateTimeString())->toBe('2012-10-10 12:00:00')
        ->and($crossMonthRange->end?->date()->toDateTimeString())->toBe('2012-12-12 12:00:00')
        ->and($german->parseText('10. August - 12. Oktober 2013', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2013-10-12 12:00:00')
        ->and($german->parseDateText('12. Juli um 19:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:00:00')
        ->and($german->parseDateText('12. Juli um 19 Uhr', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:00:00')
        ->and($german->parseDateText('12. Juli um 19:53 Uhr', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:53:00')
        ->and($german->parseDateText('5. Juni 12:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-06-05 12:00:00')
        ->and($german->parseText('32. Oktober 2015', '2012-08-10'))
        ->toBe([]);
});

it('parses german month-name dates with alternative era labels', function () {
    $german = Chrono::de();

    expect($german->parseText('10. August 234 v.u.Z.', '2012-08-10')[0]->text)
        ->toBe('10. August 234 v.u.Z.')
        ->and($german->parseText('10. August 234 v.u.Z.', '2012-08-10')[0]->start->get('year'))
        ->toBe(-234)
        ->and($german->parseText('10. August 88 nuZ', '2012-08-10')[0]->start->get('year'))
        ->toBe(88)
        ->and($german->parseText('10. August 88 uZ', '2012-08-10')[0]->start->get('year'))
        ->toBe(88)
        ->and($german->parseText('10. August 88 d.g.Z.', '2012-08-10')[0]->text)
        ->toBe('10. August 88 d.g.Z.')
        ->and($german->parseText('10. August 88 d.g.Z.', '2012-08-10')[0]->start->get('year'))
        ->toBe(88)
        ->and($german->parseText('10. August 234 v.Chr.', '2012-08-10')[0]->start->get('year'))
        ->toBe(-234)
        ->and($german->parseText('10. August 88 nC', '2012-08-10')[0]->start->get('year'))
        ->toBe(88)
        ->and($german->parseText('10. August 234 v.d.Z.', '2012-08-10')[0]->start->get('year'))
        ->toBe(-234)
        ->and($german->parseText('10. August 88 ndZ', '2012-08-10')[0]->start->get('year'))
        ->toBe(88)
        ->and($german->parseText('10. August 234 v.d.g.Z.', '2012-08-10')[0]->start->get('year'))
        ->toBe(-234)
        ->and($german->parseText('10. August 88 ndgZ', '2012-08-10')[0]->start->get('year'))
        ->toBe(88);
});

it('parses german time expressions', function () {
    $german = Chrono::de();
    $simple = $german->parseText('18:10', '2012-08-10')[0];
    $morning = $german->parseText('um 7 morgens', '2012-08-10')[0];
    $night = $german->parseText('um 8 Uhr in der Nacht', '2012-08-10')[0];
    $earlyNight = $german->parseText('um 5 Uhr in der Nacht', '2012-08-10')[0];
    $range = $german->parseText('18:10 - 22.32', '2012-08-10')[0];
    $tildeRange = $german->parseText('18:10 ~ 22.32', '2012-08-10')[0];
    $milliseconds = $german->parseText('18:10:30.123', '2012-08-10')[0];
    $vonRange = $german->parseText(' von 6:30 bis 23:00 ', '2012-08-10')[0];
    $hRange = $german->parseText(' von 6h30 bis 23h00 ', '2012-08-10')[0];
    $suffixRange = $german->parseText(' von 6h30 morgens bis 11 am Abend', '2012-08-10')[0];
    $specific = $german->parseText('8h10m00s Uhr', '2012-08-10')[0];

    expect($simple->text)->toBe('18:10')
        ->and($simple->index)->toBe(0)
        ->and($simple->start->date()->toDateTimeString())->toBe('2012-08-10 18:10:00')
        ->and($simple->start->isCertain('day'))->toBeFalse()
        ->and($simple->start->isCertain('month'))->toBeFalse()
        ->and($simple->start->isCertain('year'))->toBeFalse()
        ->and($simple->start->isCertain('hour'))->toBeTrue()
        ->and($simple->start->isCertain('minute'))->toBeTrue()
        ->and($simple->start->isCertain('second'))->toBeFalse()
        ->and($german->parseDateText('um 14 Uhr', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 14:00:00')
        ->and($german->parseDateText('um 16h', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 16:00:00')
        ->and($specific->text)->toBe('8h10m00s Uhr')
        ->and($specific->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($specific->start->tags())->toContain('parser/DESpecificTimeExpressionParser')
        ->and($specific->start->isCertain('second'))->toBeTrue()
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 07:00:00')
        ->and($morning->start->get('meridiem')->value)->toBe(0)
        ->and($morning->start->isCertain('meridiem'))->toBeTrue()
        ->and($german->parseText('11:00 Uhr vormittags', '2012-08-10')[0]->start->get('meridiem')->value)->toBe(0)
        ->and($german->parseDateText('um 8 Uhr nachmittags', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($night->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($night->start->get('meridiem')->value)->toBe(1)
        ->and($earlyNight->start->date()->toDateTimeString())->toBe('2012-08-10 05:00:00')
        ->and($earlyNight->start->get('meridiem')->value)->toBe(0)
        ->and($range->text)->toBe('18:10 - 22.32')
        ->and($range->index)->toBe(0)
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 18:10:00')
        ->and($range->start->isCertain('second'))->toBeFalse()
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 22:32:00')
        ->and($range->end?->isCertain('second'))->toBeFalse()
        ->and($tildeRange->text)->toBe('18:10 ~ 22.32')
        ->and($tildeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 22:32:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 18:10:30.123')
        ->and($german->parseText('Jahr 2020', '2012-08-10'))
        ->toBe([])
        ->and($vonRange->text)->toBe('von 6:30 bis 23:00')
        ->and($vonRange->index)->toBe(1)
        ->and($vonRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($vonRange->start->get('meridiem')->value)->toBe(0)
        ->and($vonRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($vonRange->end?->get('meridiem')->value)->toBe(1)
        ->and($hRange->text)->toBe('von 6h30 bis 23h00')
        ->and($hRange->index)->toBe(1)
        ->and($hRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($hRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($suffixRange->text)->toBe('von 6h30 morgens bis 11 am Abend')
        ->and($suffixRange->index)->toBe(1)
        ->and($suffixRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($suffixRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($german->parseText('am Mittag')[0]->text)->toBe('Mittag')
        ->and($german->parseDateText('am Mittag', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('parses german timezones and weekday times', function () {
    $german = Chrono::de();
    $cet = $german->parseText('um 14 Uhr CET', '2016-02-28')[0];
    $cest = $german->parseText('14 Uhr cet', '2016-05-28')[0];
    $falsePositive = $german->parseText('am Freitag um 14 Uhr cetteln wir etwas an', '2016-02-28')[0];
    $weekdayTime = $german->parseText('Freitag um 14 Uhr CET', '2016-05-28')[0];

    expect($cet->text)->toBe('um 14 Uhr CET')
        ->and($cet->start->tags())->toContain('parser/DETimeExpressionExtensionParser')
        ->and($cet->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cet->start->get('timezoneOffset'))->toBe(60)
        ->and($cest->text)->toBe('14 Uhr cet')
        ->and($cest->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cest->start->get('timezoneOffset'))->toBe(120)
        ->and($falsePositive->text)->toBe('am Freitag um 14 Uhr')
        ->and($falsePositive->start->date()->toDateTimeString())->toBe('2016-02-26 14:00:00')
        ->and($falsePositive->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($falsePositive->start->get('timezoneOffset'))->toBeNull()
        ->and($weekdayTime->text)->toBe('Freitag um 14 Uhr CET')
        ->and($weekdayTime->start->date()->toDateTimeString())->toBe('2016-05-27 14:00:00')
        ->and($weekdayTime->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($weekdayTime->start->get('timezoneOffset'))->toBe(120);
});

it('parses german random time expressions', function () {
    $german = Chrono::de();

    expect($german->parseText('um 12')[0]->text)
        ->toBe('um 12')
        ->and($german->parseText('am Mittag')[0]->text)
        ->toBe('Mittag')
        ->and($german->parseText('am Freitag um 14 Uhr cetteln wir etwas an', '2016-02-28')[0]->text)
        ->toBe('am Freitag um 14 Uhr')
        ->and($german->parseText('am Freitag um 14 Uhr cetteln wir etwas an', '2016-02-28')[0]->start->isCertain('timezoneOffset'))
        ->toBeFalse()
        ->and($german->parseText('Freitag um 14 Uhr CET', '2016-05-28')[0]->text)
        ->toBe('Freitag um 14 Uhr CET')
        ->and($german->parseText('Freitag um 14 Uhr CET', '2016-05-28')[0]->start->get('timezoneOffset'))
        ->toBe(120);
});

it('parses italian casual dates', function () {
    $italian = Chrono::it();
    $now = $italian->parseText('La scadenza è ora', '2012-08-10 08:09:10.011')[0];

    expect($now->text)->toBe('ora')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/ITCasualDateParser')
        ->and($italian->parseText('La scadenza è oggi', '2012-08-10 14:12')[0]->text)
        ->toBe('oggi')
        ->and($italian->parseDateText('La scadenza è oggi', '2012-08-10 14:12')?->toDateTimeString())
        ->toBe('2012-08-10 14:12:00')
        ->and($italian->parseText('La scadenza è domani', '2012-08-10 17:10')[0]->text)
        ->toBe('domani')
        ->and($italian->parseDateText('La scadenza è domani', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($italian->parseDateText('La scadenza è dmn', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($italian->parseDateText('Ci vediamo questa sera', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($italian->parseDateText('Ci vediamo ieri sera', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00');
});

it('parses finnish casual dates and times', function () {
    $finnish = Chrono::fi();
    $now = $finnish->parseText('Määräaika on nyt', '2012-08-10 08:09:10.011')[0];
    $today = $finnish->parseText('tänään', '2012-08-10')[0];
    $tomorrow = $finnish->parseText('huomenna', '2012-08-10')[0];
    $yesterday = $finnish->parseText('eilen', '2012-08-10')[0];
    $dayAfterTomorrow = $finnish->parseText('ylihuomenna', '2012-08-10')[0];
    $dayBeforeYesterday = $finnish->parseText('toissapäivänä', '2012-08-10')[0];
    $todayMorning = $finnish->parseText('tänään aamulla', '2012-08-10')[0];
    $todayLateMorning = $finnish->parseText('tänään aamupäivällä', '2012-08-10')[0];
    $todayNoon = $finnish->parseText('tänään päivällä', '2012-08-10')[0];
    $todayAfternoon = $finnish->parseText('tänään iltapäivällä', '2012-08-10')[0];
    $todayEvening = $finnish->parseText('tänään illalla', '2012-08-10')[0];
    $todayNight = $finnish->parseText('tänään yöllä', '2012-08-10')[0];
    $todayMidnight = $finnish->parseText('tänään keskiyöllä', '2012-08-10')[0];
    $morning = $finnish->parseText('aamulla', '2012-08-10 14:00')[0];
    $casualTime = $finnish->parseText('aamupäivällä', '2012-08-10 14:00')[0];
    $noon = $finnish->parseText('päivällä', '2012-08-10 14:00')[0];
    $afternoon = $finnish->parseText('iltapäivällä', '2012-08-10 14:00')[0];
    $evening = $finnish->parseText('illalla', '2012-08-10 14:00')[0];
    $night = $finnish->parseText('yöllä', '2012-08-10 14:00')[0];
    $midnight = $finnish->parseText('keskiyöllä', '2012-08-10 14:00')[0];
    $lastNight = $finnish->parseText('viime yönä', '2012-08-10 14:00')[0];

    expect($now->text)->toBe('nyt')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/FICasualDateParser')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($dayAfterTomorrow->start->get('year'))->toBe(2012)
        ->and($dayAfterTomorrow->start->get('month'))->toBe(8)
        ->and($dayAfterTomorrow->start->get('day'))->toBe(12)
        ->and($dayBeforeYesterday->start->get('year'))->toBe(2012)
        ->and($dayBeforeYesterday->start->get('month'))->toBe(8)
        ->and($dayBeforeYesterday->start->get('day'))->toBe(8)
        ->and($todayMorning->start->get('year'))->toBe(2012)
        ->and($todayMorning->start->get('month'))->toBe(8)
        ->and($todayMorning->start->get('day'))->toBe(10)
        ->and($todayMorning->start->get('hour'))->toBe(6)
        ->and($todayLateMorning->start->get('hour'))->toBe(9)
        ->and($todayNoon->start->get('hour'))->toBe(12)
        ->and($todayAfternoon->start->get('hour'))->toBe(15)
        ->and($todayEvening->start->get('hour'))->toBe(18)
        ->and($todayNight->start->get('hour'))->toBe(22)
        ->and($todayMidnight->start->get('hour'))->toBe(0)
        ->and($todayMidnight->start->get('day'))->toBe(10)
        ->and($morning->text)->toBe('aamulla')
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($morning->start->get('minute'))->toBe(0)
        ->and($casualTime->text)->toBe('aamupäivällä')
        ->and($casualTime->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($casualTime->start->tags())->toContain('parser/FICasualTimeParser')
        ->and($casualTime->start->get('minute'))->toBe(0)
        ->and($noon->text)->toBe('päivällä')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($noon->start->get('minute'))->toBe(0)
        ->and($afternoon->text)->toBe('iltapäivällä')
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($afternoon->start->get('minute'))->toBe(0)
        ->and($evening->text)->toBe('illalla')
        ->and($evening->start->get('hour'))->toBe(18)
        ->and($evening->start->get('minute'))->toBe(0)
        ->and($night->text)->toBe('yöllä')
        ->and($night->start->get('hour'))->toBe(22)
        ->and($night->start->get('minute'))->toBe(0)
        ->and($midnight->text)->toBe('keskiyöllä')
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($midnight->start->get('minute'))->toBe(0)
        ->and($midnight->start->get('day'))->toBe(11)
        ->and($lastNight->text)->toBe('viime yönä')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($lastNight->start->get('year'))->toBe(2012)
        ->and($lastNight->start->get('month'))->toBe(8)
        ->and($lastNight->start->get('day'))->toBe(9)
        ->and($lastNight->start->get('hour'))->toBe(0)
        ->and($finnish->parseDateText('Määräaika on tänään', '2012-08-10 14:12')?->toDateTimeString())
        ->toBe('2012-08-10 14:12:00')
        ->and($finnish->parseDateText('Määräaika on huomenna', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($finnish->parseDateText('Määräaika on ylihuomenna', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-12 17:10:00')
        ->and($finnish->parseDateText('Määräaika oli eilen', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 17:10:00')
        ->and($finnish->parseDateText('Määräaika oli toissapäivänä', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-08 17:10:00')
        ->and($finnish->parseDateText('Määräaika oli viime yönä', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and($finnish->parseDateText('Määräaika on huomenna illalla', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 18:00:00')
        ->and($finnish->parseDateText('Määräaika on tänä aamuna', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($finnish->parseDateText('Määräaika on keskiyöllä', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00');
});

it('parses finnish little endian month name dates and ranges', function () {
    $finnish = Chrono::fi();
    $dayMonth = $finnish->parseText('15. elokuuta', '2012-08-10')[0];
    $dayMonthYear = $finnish->parseText('15 elokuuta 2012', '2012-08-10')[0];
    $abbreviated = $finnish->parseText('15. elo 2012', '2012-08-10')[0];
    $closestYear = $finnish->parseText('3 tammikuuta', '2012-08-10')[0];
    $december = $finnish->parseText('1 joulukuuta 2023', '2023-11-01')[0];
    $hyphenRange = $finnish->parseText('15-16 elokuuta', '2012-08-10')[0];
    $range = $finnish->parseText('Tapahtuma 10.-12. elokuuta 2012', '2012-08-10')[0];

    expect($dayMonth->start->get('year'))->toBe(2012)
        ->and($dayMonth->start->get('month'))->toBe(8)
        ->and($dayMonth->start->get('day'))->toBe(15)
        ->and($dayMonthYear->start->get('year'))->toBe(2012)
        ->and($dayMonthYear->start->get('month'))->toBe(8)
        ->and($dayMonthYear->start->get('day'))->toBe(15)
        ->and($abbreviated->start->get('year'))->toBe(2012)
        ->and($abbreviated->start->get('month'))->toBe(8)
        ->and($abbreviated->start->get('day'))->toBe(15)
        ->and($closestYear->start->get('year'))->toBe(2013)
        ->and($closestYear->start->get('month'))->toBe(1)
        ->and($closestYear->start->get('day'))->toBe(3)
        ->and($december->start->get('year'))->toBe(2023)
        ->and($december->start->get('month'))->toBe(12)
        ->and($december->start->get('day'))->toBe(1)
        ->and($hyphenRange->start->get('year'))->toBe(2012)
        ->and($hyphenRange->start->get('month'))->toBe(8)
        ->and($hyphenRange->start->get('day'))->toBe(15)
        ->and($hyphenRange->end?->get('year'))->toBe(2012)
        ->and($hyphenRange->end?->get('month'))->toBe(8)
        ->and($hyphenRange->end?->get('day'))->toBe(16)
        ->and($finnish->parseText('32 elokuuta', '2012-08-10'))->toBe([])
        ->and($finnish->parseText('Tapahtuma 10. elokuuta', '2012-08-10')[0]->text)
        ->toBe('10. elokuuta')
        ->and($finnish->parseText('Tapahtuma 10. elokuuta', '2012-08-10')[0]->start->tags())->toContain('parser/FIMonthNameLittleEndianParser')
        ->and($finnish->parseDateText('Tapahtuma 10. elokuuta', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($finnish->parseDateText('Tapahtuma 10 elokuu 2026', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-08-10 12:00:00')
        ->and($range->text)->toBe('10.-12. elokuuta 2012')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});

it('parses finnish weekdays', function () {
    $finnish = Chrono::fi();
    $merged = $finnish->parseText('maanantaina 10. elokuuta 2012', '2012-08-10')[0];
    $monday = $finnish->parseText('maanantai', '2012-08-09')[0];
    $mondayEssive = $finnish->parseText('maanantaina', '2012-08-09')[0];
    $nextMonday = $finnish->parseText('ensi maanantai', '2012-08-09')[0];
    $lastMonday = $finnish->parseText('viime maanantai', '2012-08-09')[0];

    expect($finnish->parseText('Nähdään maanantaina', '2012-08-10')[0]->text)
        ->toBe('maanantaina')
        ->and($finnish->parseText('Nähdään maanantaina', '2012-08-10')[0]->start->tags())->toContain('parser/FIWeekdayParser')
        ->and($finnish->parseDateText('Nähdään maanantaina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($finnish->parseDateText('Nähdään ensi maanantaina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($finnish->parseDateText('Nähdään viime sunnuntaina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 12:00:00')
        ->and($monday->index)->toBe(0)
        ->and($monday->text)->toBe('maanantai')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($mondayEssive->index)->toBe(0)
        ->and($mondayEssive->text)->toBe('maanantaina')
        ->and($mondayEssive->start->get('year'))->toBe(2012)
        ->and($mondayEssive->start->get('month'))->toBe(8)
        ->and($mondayEssive->start->get('day'))->toBe(6)
        ->and($mondayEssive->start->get('weekday'))->toBe(1)
        ->and($nextMonday->index)->toBe(0)
        ->and($nextMonday->text)->toBe('ensi maanantai')
        ->and($nextMonday->start->get('year'))->toBe(2012)
        ->and($nextMonday->start->get('month'))->toBe(8)
        ->and($nextMonday->start->get('day'))->toBe(13)
        ->and($nextMonday->start->get('weekday'))->toBe(1)
        ->and($lastMonday->index)->toBe(0)
        ->and($lastMonday->text)->toBe('viime maanantai')
        ->and($lastMonday->start->get('year'))->toBe(2012)
        ->and($lastMonday->start->get('month'))->toBe(8)
        ->and($lastMonday->start->get('day'))->toBe(6)
        ->and($lastMonday->start->get('weekday'))->toBe(1)
        ->and($finnish->parseText('sunnuntai', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($finnish->parseText('tiistai', '2012-08-09')[0]->start->get('weekday'))->toBe(2)
        ->and($finnish->parseText('keskiviikko', '2012-08-09')[0]->start->get('weekday'))->toBe(3)
        ->and($finnish->parseText('torstai', '2012-08-09')[0]->start->get('weekday'))->toBe(4)
        ->and($finnish->parseText('perjantai', '2012-08-09')[0]->start->get('weekday'))->toBe(5)
        ->and($finnish->parseText('lauantai', '2012-08-09')[0]->start->get('weekday'))->toBe(6)
        ->and($finnish->parseText('ma', '2012-08-09')[0]->start->get('weekday'))->toBe(1)
        ->and($finnish->parseText('pe', '2012-08-09')[0]->start->get('weekday'))->toBe(5)
        ->and($finnish->parseText('su', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($merged->text)->toBe('maanantaina 10. elokuuta 2012')
        ->and($merged->start->isCertain('weekday'))->toBeTrue()
        ->and($merged->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('parses finnish time expressions and ranges', function () {
    $finnish = Chrono::fi();
    $specific = $finnish->parseText('klo 15:00', '2012-08-10')[0];
    $kello = $finnish->parseText('kello 8:30', '2012-08-10')[0];
    $dotted = $finnish->parseText('klo 13.00', '2012-08-10')[0];
    $milliseconds = $finnish->parseText('klo 8:10:30.123', '2012-08-10')[0];
    $range = $finnish->parseText('klo 6:30 - 8:45', '2012-08-10')[0];
    $upstreamRange = $finnish->parseText('klo 10:00-12:00', '2012-08-10')[0];
    $compactRange = $finnish->parseText('klo 10:00-12:00', '2012-08-10')[0];
    $dateTime = $finnish->parseText('15 elokuuta 2012 klo 14:00', '2012-08-10')[0];

    expect($specific->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($specific->start->get('hour'))->toBe(15)
        ->and($specific->start->get('minute'))->toBe(0)
        ->and($specific->start->tags())->toContain('parser/FITimeExpressionParser')
        ->and($kello->start->date()->toDateTimeString())->toBe('2012-08-10 08:30:00')
        ->and($kello->start->get('hour'))->toBe(8)
        ->and($kello->start->get('minute'))->toBe(30)
        ->and($dotted->start->get('hour'))->toBe(13)
        ->and($dotted->start->get('minute'))->toBe(0)
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($finnish->parseText('Nähdään klo 6:13', '2012-08-10')[0]->text)
        ->toBe('klo 6:13')
        ->and($finnish->parseDateText('Nähdään klo 6:13', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:13:00')
        ->and($finnish->parseDateText('Nähdään kello 18.30', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:30:00')
        ->and($finnish->parseDateText('Nähdään klo 630', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:30:00')
        ->and($finnish->parseDateText('Nähdään klo 6pm', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($range->text)->toBe('klo 6:30 - 8:45')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($upstreamRange->start->get('hour'))->toBe(10)
        ->and($upstreamRange->start->get('minute'))->toBe(0)
        ->and($upstreamRange->end?->get('hour'))->toBe(12)
        ->and($upstreamRange->end?->get('minute'))->toBe(0)
        ->and($dateTime->start->get('year'))->toBe(2012)
        ->and($dateTime->start->get('month'))->toBe(8)
        ->and($dateTime->start->get('day'))->toBe(15)
        ->and($dateTime->start->get('hour'))->toBe(14)
        ->and($dateTime->start->get('minute'))->toBe(0)
        ->and($compactRange->text)->toBe('klo 10:00-12:00')
        ->and($compactRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($finnish->parseText('Vuosi 2020', '2012-08-10'))
        ->toBe([]);
});

it('parses finnish time unit relative expressions', function () {
    $finnish = Chrono::fi();
    $fiveDaysAgo = $finnish->parseText('5 päivää sitten tehtiin jotain', '2012-08-10')[0];
    $tenDaysAgo = $finnish->parseText('10 päivää sitten tehtiin jotain', '2012-08-10 13:30')[0];
    $minutesAgo = $finnish->parseText('15 minuuttia sitten', '2012-08-10 12:14')[0];
    $prefixedHoursAgo = $finnish->parseText('   12 tuntia sitten', '2012-08-10 12:14')[0];
    $hoursAgo = $finnish->parseText('12 tuntia sitten tapahtui jotain', '2012-08-10 12:14')[0];
    $monthsAgo = $finnish->parseText('5 kuukautta sitten tehtiin jotain', '2012-10-10')[0];
    $yearsAgo = $finnish->parseText('5 vuotta sitten tehtiin jotain', '2012-08-10 22:22')[0];
    $weekAgo = $finnish->parseText('yksi viikkoa sitten tehtiin jotain', '2012-08-03 08:34')[0];
    $withinDays = $finnish->parseText('pitää tehdä jotain 5 päivää sisällä', '2012-08-10')[0];
    $withinMinutes = $finnish->parseText('5 minuuttia sisällä', '2012-08-10 12:14')[0];
    $withinHours = $finnish->parseText('1 tuntia sisällä', '2012-08-10 12:14')[0];
    $withinWeeks = $finnish->parseText('2 viikkoa sisällä', '2012-08-10 12:14')[0];
    $duringDays = $finnish->parseText('5 päivää kuluessa', '2012-08-10')[0];
    $duringYears = $finnish->parseText('yksi vuotta kuluessa', '2012-08-10 12:14')[0];
    $fromNowMinutes = $finnish->parseText('5 minuuttia päästä', '2012-08-10 12:14')[0];
    $fromNowDays = $finnish->parseText('3 päivää päästä', '2012-08-10 12:14')[0];
    $fromNowWeeks = $finnish->parseText('2 viikkoa päästä', '2016-10-01')[0];
    $nextTwoWeeks = $finnish->parseText('seuraavat 2 viikkoa', '2016-10-01 12:00')[0];
    $nextTwoDays = $finnish->parseText('seuraavat 2 päivää', '2016-10-01 12:00')[0];
    $nextTwoYears = $finnish->parseText('seuraavat kaksi vuotta', '2016-10-01 12:00')[0];
    $compoundFuture = $finnish->parseText('seuraavat 2 viikkoa 3 päivää', '2016-10-01 12:00')[0];
    $nextOneYear = $finnish->parseText('seuraava yksi vuotta', '2016-10-01 12:00')[0];
    $previousTwoWeeks = $finnish->parseText('edelliset 2 viikkoa', '2016-10-01 12:00')[0];
    $lastTwoDays = $finnish->parseText('viimeiset 2 päivää', '2016-10-01 12:00')[0];
    $pastTwoWeeks = $finnish->parseText('kuluneet kaksi viikkoa', '2016-10-01 12:00')[0];
    $compoundPlus = $finnish->parseText('+2 kuukautta 5 päivää', '2016-10-01 12:00')[0];
    $plusMinutes = $finnish->parseText('+15 minuuttia', '2012-07-10 12:14')[0];
    $plusCompactMinutes = $finnish->parseText('+15min', '2012-07-10 12:14')[0];
    $plusCompound = $finnish->parseText('+1 päivä 2 tuntia', '2012-07-10 12:14')[0];
    $minusYears = $finnish->parseText('-3vuotta', '2015-07-10 12:14')[0];

    expect($finnish->parseText('Nähdään 2 päivän päästä', '2012-08-10 09:30')[0]->text)
        ->toBe('2 päivän päästä')
        ->and($finnish->parseDateText('Nähdään 2 päivän päästä', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00')
        ->and($finnish->parseDateText('Nähtiin 3 päivää sitten', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-07 09:30:00')
        ->and($finnish->parseDateText('Nähdään seuraavat 2 viikkoa', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-24 09:30:00')
        ->and($finnish->parseDateText('Nähdään seuraava yksi vuotta', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-10-01 12:00:00')
        ->and($finnish->parseDateText('Nähtiin edelliset 2 viikkoa', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-07-27 09:30:00')
        ->and($finnish->parseDateText('Kuluneet kaksi viikkoa', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-17 12:00:00')
        ->and($finnish->parseDateText('+15min', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and($finnish->parseDateText('-3vuotta', '2015-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:14:00')
        ->and($finnish->parseDateText('Nähdään kahden tunnin päästä', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 11:30:00')
        ->and($fiveDaysAgo->index)->toBe(0)
        ->and($fiveDaysAgo->text)->toBe('5 päivää sitten')
        ->and($fiveDaysAgo->start->get('year'))->toBe(2012)
        ->and($fiveDaysAgo->start->get('month'))->toBe(8)
        ->and($fiveDaysAgo->start->get('day'))->toBe(5)
        ->and($tenDaysAgo->index)->toBe(0)
        ->and($tenDaysAgo->text)->toBe('10 päivää sitten')
        ->and($tenDaysAgo->start->get('year'))->toBe(2012)
        ->and($tenDaysAgo->start->get('month'))->toBe(7)
        ->and($tenDaysAgo->start->get('day'))->toBe(31)
        ->and($minutesAgo->index)->toBe(0)
        ->and($minutesAgo->text)->toBe('15 minuuttia sitten')
        ->and($minutesAgo->start->get('hour'))->toBe(11)
        ->and($minutesAgo->start->get('minute'))->toBe(59)
        ->and($prefixedHoursAgo->index)->toBe(3)
        ->and($prefixedHoursAgo->text)->toBe('12 tuntia sitten')
        ->and($prefixedHoursAgo->start->get('hour'))->toBe(0)
        ->and($prefixedHoursAgo->start->get('minute'))->toBe(14)
        ->and($hoursAgo->index)->toBe(0)
        ->and($hoursAgo->text)->toBe('12 tuntia sitten')
        ->and($hoursAgo->start->get('hour'))->toBe(0)
        ->and($hoursAgo->start->get('minute'))->toBe(14)
        ->and($monthsAgo->index)->toBe(0)
        ->and($monthsAgo->text)->toBe('5 kuukautta sitten')
        ->and($monthsAgo->start->get('year'))->toBe(2012)
        ->and($monthsAgo->start->get('month'))->toBe(5)
        ->and($monthsAgo->start->get('day'))->toBe(10)
        ->and($yearsAgo->index)->toBe(0)
        ->and($yearsAgo->text)->toBe('5 vuotta sitten')
        ->and($yearsAgo->start->get('year'))->toBe(2007)
        ->and($yearsAgo->start->get('month'))->toBe(8)
        ->and($yearsAgo->start->get('day'))->toBe(10)
        ->and($weekAgo->index)->toBe(0)
        ->and($weekAgo->text)->toBe('yksi viikkoa sitten')
        ->and($weekAgo->start->get('year'))->toBe(2012)
        ->and($weekAgo->start->get('month'))->toBe(7)
        ->and($weekAgo->start->get('day'))->toBe(27)
        ->and($withinDays->text)->toBe('5 päivää sisällä')
        ->and($withinDays->start->get('year'))->toBe(2012)
        ->and($withinDays->start->get('month'))->toBe(8)
        ->and($withinDays->start->get('day'))->toBe(15)
        ->and($withinMinutes->index)->toBe(0)
        ->and($withinMinutes->text)->toBe('5 minuuttia sisällä')
        ->and($withinMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($withinHours->index)->toBe(0)
        ->and($withinHours->text)->toBe('1 tuntia sisällä')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($withinWeeks->text)->toBe('2 viikkoa sisällä')
        ->and($withinWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($duringDays->text)->toBe('5 päivää kuluessa')
        ->and($duringDays->start->get('year'))->toBe(2012)
        ->and($duringDays->start->get('month'))->toBe(8)
        ->and($duringDays->start->get('day'))->toBe(15)
        ->and($duringYears->text)->toBe('yksi vuotta kuluessa')
        ->and($duringYears->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($fromNowMinutes->text)->toBe('5 minuuttia päästä')
        ->and($fromNowMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($fromNowDays->text)->toBe('3 päivää päästä')
        ->and($fromNowDays->start->get('year'))->toBe(2012)
        ->and($fromNowDays->start->get('month'))->toBe(8)
        ->and($fromNowDays->start->get('day'))->toBe(13)
        ->and($fromNowWeeks->text)->toBe('2 viikkoa päästä')
        ->and($fromNowWeeks->start->get('year'))->toBe(2016)
        ->and($fromNowWeeks->start->get('month'))->toBe(10)
        ->and($fromNowWeeks->start->get('day'))->toBe(15)
        ->and($nextTwoWeeks->text)->toBe('seuraavat 2 viikkoa')
        ->and($nextTwoWeeks->start->get('year'))->toBe(2016)
        ->and($nextTwoWeeks->start->get('month'))->toBe(10)
        ->and($nextTwoWeeks->start->get('day'))->toBe(15)
        ->and($nextTwoDays->text)->toBe('seuraavat 2 päivää')
        ->and($nextTwoDays->start->get('year'))->toBe(2016)
        ->and($nextTwoDays->start->get('month'))->toBe(10)
        ->and($nextTwoDays->start->get('day'))->toBe(3)
        ->and($nextTwoDays->start->get('hour'))->toBe(12)
        ->and($nextTwoYears->text)->toBe('seuraavat kaksi vuotta')
        ->and($nextTwoYears->start->get('year'))->toBe(2018)
        ->and($nextTwoYears->start->get('month'))->toBe(10)
        ->and($nextTwoYears->start->get('day'))->toBe(1)
        ->and($nextTwoYears->start->get('hour'))->toBe(12)
        ->and($compoundFuture->text)->toBe('seuraavat 2 viikkoa 3 päivää')
        ->and($compoundFuture->start->get('year'))->toBe(2016)
        ->and($compoundFuture->start->get('month'))->toBe(10)
        ->and($compoundFuture->start->get('day'))->toBe(18)
        ->and($compoundFuture->start->get('hour'))->toBe(12)
        ->and($nextOneYear->text)->toBe('seuraava yksi vuotta')
        ->and($nextOneYear->start->get('year'))->toBe(2017)
        ->and($nextOneYear->start->get('month'))->toBe(10)
        ->and($nextOneYear->start->get('day'))->toBe(1)
        ->and($previousTwoWeeks->text)->toBe('edelliset 2 viikkoa')
        ->and($previousTwoWeeks->start->get('year'))->toBe(2016)
        ->and($previousTwoWeeks->start->get('month'))->toBe(9)
        ->and($previousTwoWeeks->start->get('day'))->toBe(17)
        ->and($lastTwoDays->text)->toBe('viimeiset 2 päivää')
        ->and($lastTwoDays->start->get('year'))->toBe(2016)
        ->and($lastTwoDays->start->get('month'))->toBe(9)
        ->and($lastTwoDays->start->get('day'))->toBe(29)
        ->and($pastTwoWeeks->text)->toBe('kuluneet kaksi viikkoa')
        ->and($pastTwoWeeks->start->get('year'))->toBe(2016)
        ->and($pastTwoWeeks->start->get('month'))->toBe(9)
        ->and($pastTwoWeeks->start->get('day'))->toBe(17)
        ->and($compoundPlus->text)->toBe('+2 kuukautta 5 päivää')
        ->and($compoundPlus->start->get('year'))->toBe(2016)
        ->and($compoundPlus->start->get('month'))->toBe(12)
        ->and($compoundPlus->start->get('day'))->toBe(6)
        ->and($plusMinutes->text)->toBe('+15 minuuttia')
        ->and($plusMinutes->start->get('hour'))->toBe(12)
        ->and($plusMinutes->start->get('minute'))->toBe(29)
        ->and($plusCompactMinutes->text)->toBe('+15min')
        ->and($plusCompactMinutes->start->get('hour'))->toBe(12)
        ->and($plusCompactMinutes->start->get('minute'))->toBe(29)
        ->and($plusCompound->text)->toBe('+1 päivä 2 tuntia')
        ->and($plusCompound->start->get('day'))->toBe(11)
        ->and($plusCompound->start->get('hour'))->toBe(14)
        ->and($plusCompound->start->get('minute'))->toBe(14)
        ->and($minusYears->text)->toBe('-3vuotta')
        ->and($minusYears->start->get('year'))->toBe(2012)
        ->and($minusYears->start->get('month'))->toBe(7)
        ->and($minusYears->start->get('day'))->toBe(10)
        ->and($minusYears->start->get('hour'))->toBe(12)
        ->and($minusYears->start->get('minute'))->toBe(14);
});

it('merges finnish dates with times and date ranges', function () {
    $finnish = Chrono::fi();
    $dateTime = $finnish->parseText('Nähdään 10. elokuuta 2012 klo 6:30', '2012-08-10')[0];
    $timeRange = $finnish->parseText('Nähdään 10. elokuuta 2012 klo 6:30 - 8:45', '2012-08-10')[0];
    $dateRange = $finnish->parseText('Tapahtuma 10. elokuuta 2012 - 12. elokuuta 2012', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10. elokuuta 2012 klo 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->start->isCertain('hour'))->toBeTrue()
        ->and($timeRange->text)->toBe('10. elokuuta 2012 klo 6:30 - 8:45')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dateRange->text)->toBe('10. elokuuta 2012 - 12. elokuuta 2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});

it('parses finnish common iso and slash date formats', function () {
    $finnish = Chrono::fi();
    $iso = $finnish->parseText('Julkaistu 2026-06-23 14:30', '2012-08-10')[0];

    expect($iso->text)->toBe('2026-06-23 14:30')
        ->and($iso->start->date()->toDateTimeString())->toBe('2026-06-23 14:30:00')
        ->and($finnish->parseText('Julkaistu 10/08/2012', '2012-08-10')[0]->text)
        ->toBe('10/08/2012')
        ->and($finnish->parseDateText('Julkaistu 10/08/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($finnish->parseDateText('Tapahtuma 6/20', '2026-06-23 09:00', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2027-06-20 12:00:00');
});

it('parses dutch casual dates and times', function () {
    $dutch = Chrono::nl();
    $now = $dutch->parseText('De deadline is nu', '2012-08-10 08:09:10.011')[0];
    $today = $dutch->parseText('De deadline is vandaag', '2012-08-10 14:12')[0];
    $tomorrow = $dutch->parseText('De deadline is morgen', '2012-08-10 17:10')[0];
    $yesterday = $dutch->parseText('De deadline was gisteren', '2012-08-10 12:00')[0];
    $thisMorning = $dutch->parseText('De Deadline was deze ochtend', '2012-08-10 12:00')[0];
    $thisAfternoon = $dutch->parseText('De Deadline was deze namiddag ', '2012-08-10 12:00')[0];
    $thisEvening = $dutch->parseText('De Deadline was deze avond ', '2012-08-10 12:00')[0];
    $tonight = $dutch->parseText('De deadline is vanavond', '2012-08-10 12:00')[0];
    $midnight = $dutch->parseText('The Deadline is om middernacht ', '2012-08-10 01:00')[0];
    $todayAtFive = $dutch->parseText('De deadline is vandaag om 17:00', '2012-08-10 12:00')[0];
    $yesterdayMorning = $dutch->parseText('gisterenochtend', '2012-08-10 14:00')[0];
    $yesterdayNoon = $dutch->parseText('gisterenmiddag', '2012-08-10 14:00')[0];
    $yesterdayEvening = $dutch->parseText('gisterenavond', '2012-08-10 14:00')[0];
    $thisMorningCompact = $dutch->parseText('vanochtend', '2012-08-10 14:00')[0];
    $thisNoonCompact = $dutch->parseText('vanmiddag', '2012-08-10 14:00')[0];
    $tonightCompact = $dutch->parseText('vanavond', '2012-08-10 14:00')[0];
    $tomorrowMorning = $dutch->parseText('morgenochtend', '2012-08-10 14:00')[0];
    $tomorrowNoon = $dutch->parseText('morgenmiddag', '2012-08-10 14:00')[0];
    $tomorrowEvening = $dutch->parseText('morgenavond', '2012-08-10 14:00')[0];

    expect($now->text)->toBe('nu')
        ->and($now->index)->toBe(15)
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->get('timezoneOffset'))->toBe($now->start->date()->offsetMinutes)
        ->and($now->start->tags())->toContain('parser/NLCasualDateParser')
        ->and($today->index)->toBe(15)
        ->and($today->text)->toBe('vandaag')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->index)->toBe(15)
        ->and($tomorrow->text)->toBe('morgen')
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($yesterday->index)->toBe(16)
        ->and($yesterday->text)->toBe('gisteren')
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($thisMorning->index)->toBe(16)
        ->and($thisMorning->text)->toBe('deze ochtend')
        ->and($thisMorning->start->get('year'))->toBe(2012)
        ->and($thisMorning->start->get('month'))->toBe(8)
        ->and($thisMorning->start->get('day'))->toBe(10)
        ->and($thisMorning->start->get('hour'))->toBe(6)
        ->and($thisAfternoon->index)->toBe(16)
        ->and($thisAfternoon->text)->toBe('deze namiddag')
        ->and($thisAfternoon->start->get('hour'))->toBe(15)
        ->and($thisEvening->index)->toBe(16)
        ->and($thisEvening->text)->toBe('deze avond')
        ->and($thisEvening->start->get('hour'))->toBe(20)
        ->and($tonight->text)->toBe('vanavond')
        ->and($tonight->start->get('year'))->toBe(2012)
        ->and($tonight->start->get('month'))->toBe(8)
        ->and($tonight->start->get('day'))->toBe(10)
        ->and($tonight->start->get('hour'))->toBe(20)
        ->and($midnight->text)->toBe('middernacht')
        ->and($midnight->start->get('year'))->toBe(2012)
        ->and($midnight->start->get('month'))->toBe(8)
        ->and($midnight->start->get('day'))->toBe(11)
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($todayAtFive->index)->toBe(15)
        ->and($todayAtFive->text)->toBe('vandaag om 17:00')
        ->and($todayAtFive->start->get('year'))->toBe(2012)
        ->and($todayAtFive->start->get('month'))->toBe(8)
        ->and($todayAtFive->start->get('day'))->toBe(10)
        ->and($todayAtFive->start->get('hour'))->toBe(17)
        ->and($yesterdayMorning->start->get('year'))->toBe(2012)
        ->and($yesterdayMorning->start->get('month'))->toBe(8)
        ->and($yesterdayMorning->start->get('day'))->toBe(9)
        ->and($yesterdayMorning->start->get('hour'))->toBe(6)
        ->and($yesterdayNoon->start->get('day'))->toBe(9)
        ->and($yesterdayNoon->start->get('hour'))->toBe(12)
        ->and($yesterdayEvening->start->get('day'))->toBe(9)
        ->and($yesterdayEvening->start->get('hour'))->toBe(20)
        ->and($thisMorningCompact->start->get('day'))->toBe(10)
        ->and($thisMorningCompact->start->get('hour'))->toBe(6)
        ->and($thisNoonCompact->start->get('day'))->toBe(10)
        ->and($thisNoonCompact->start->get('hour'))->toBe(12)
        ->and($tonightCompact->start->get('day'))->toBe(10)
        ->and($tonightCompact->start->get('hour'))->toBe(20)
        ->and($tomorrowMorning->start->get('day'))->toBe(11)
        ->and($tomorrowMorning->start->get('hour'))->toBe(6)
        ->and($tomorrowNoon->start->get('day'))->toBe(11)
        ->and($tomorrowNoon->start->get('hour'))->toBe(12)
        ->and($tomorrowEvening->start->get('day'))->toBe(11)
        ->and($tomorrowEvening->start->get('hour'))->toBe(20)
        ->and($dutch->parseDateText('Deadline is vandaag', '2012-08-10 14:12')?->toDateTimeString())
        ->toBe('2012-08-10 14:12:00')
        ->and($dutch->parseDateText('Deadline is morgen', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($dutch->parseDateText('Deadline was gisteren', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 17:10:00')
        ->and($dutch->parseDateText('Afspraak deze ochtend', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($dutch->parseText('Afspraak deze ochtend', '2012-08-10 17:10')[0]->start->tags())->toContain('parser/NLCasualTimeParser')
        ->and($dutch->parseDateText('Afspraak avond', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($dutch->parseDateText('Afspraak middernacht', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($dutch->parseDateText('Afspraak morgenochtend', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 06:00:00')
        ->and($dutch->parseText('Afspraak morgenochtend', '2012-08-10 17:10')[0]->start->tags())->toContain('parser/NLCasualDateTimeParser')
        ->and($dutch->parseText('Afspraak morgenochtend', '2012-08-10 17:10')[0]->start->isCertain('day'))->toBeTrue()
        ->and($dutch->parseDateText('Afspraak vanavond', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($dutch->parseDateText('Afspraak gisterenmiddag', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00');
});

it('parses dutch month name dates and ranges', function () {
    $dutch = Chrono::nl();
    $range = $dutch->parseText('Evenement 10 - 25 maart 2019', '2012-08-10')[0];
    $explicitRange = $dutch->parseText('10 augustus - 12 september 2013', '2012-08-10')[0];
    $beforeChrist = $dutch->parseText('10 augustus 234 voor Christus', '2012-08-10')[0];
    $afterChrist = $dutch->parseText('10 augustus 88 na Christus', '2012-08-10')[0];

    expect($dutch->parseText('Afspraak 1 januari 2019', '2012-08-10')[0]->text)
        ->toBe('1 januari 2019')
        ->and($dutch->parseText('Afspraak 1 januari 2019', '2012-08-10')[0]->start->tags())->toContain('parser/NLMonthNameMiddleEndianParser')
        ->and($dutch->parseDateText('Afspraak 1 januari 2019', '2012-08-10')?->toDateTimeString())
        ->toBe('2019-01-01 12:00:00')
        ->and($dutch->parseDateText('Afspraak 12de juli 2013', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-07-12 12:00:00')
        ->and($dutch->parseDateText('Afspraak eerste november 2013', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-11-01 12:00:00')
        ->and($range->text)->toBe('10 - 25 maart 2019')
        ->and($range->start->date()->toDateTimeString())->toBe('2019-03-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2019-03-25 12:00:00')
        ->and($dutch->parseText('10 augustus 2012', '2012-08-10')[0]->text)->toBe('10 augustus 2012')
        ->and($dutch->parseDateText('3 februari 82', '2012-08-10')?->toDateTimeString())->toBe('1982-02-03 12:00:00')
        ->and($beforeChrist->index)->toBe(0)
        ->and($beforeChrist->text)->toBe('10 augustus 234 voor Christus')
        ->and($beforeChrist->start->get('year'))->toBe(-234)
        ->and($beforeChrist->start->get('month'))->toBe(8)
        ->and($beforeChrist->start->get('day'))->toBe(10)
        ->and($afterChrist->index)->toBe(0)
        ->and($afterChrist->text)->toBe('10 augustus 88 na Christus')
        ->and($afterChrist->start->get('year'))->toBe(88)
        ->and($afterChrist->start->get('month'))->toBe(8)
        ->and($afterChrist->start->get('day'))->toBe(10)
        ->and($dutch->parseText('Zon 15 Sept', '2013-08-10')[0]->text)->toBe('Zon 15 Sept')
        ->and($dutch->parseDateText('Zon 15 Sept', '2013-08-10')?->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($dutch->parseText('De deadline is dinsdag, 10 januari', '2012-08-10')[0]->text)->toBe('dinsdag, 10 januari')
        ->and($dutch->parseText('De deadline is dinsdag, 10 januari', '2012-08-10')[0]->start->get('weekday'))->toBe(2)
        ->and($dutch->parseDateText('31ste maart 2016', '2012-08-10')?->toDateTimeString())->toBe('2016-03-31 12:00:00')
        ->and($dutch->parseText('10 - 22 augustus 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($explicitRange->text)->toBe('10 augustus - 12 september 2013')
        ->and($explicitRange->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($explicitRange->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($dutch->parseDateText('12de juli om 19:00', '2012-08-10')?->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($dutch->parseDateText('5 mei 12:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($dutch->parseText('vierentwintigste mei', '2012-08-10')[0]->start->get('day'))->toBe(24)
        ->and($dutch->parseText('achtste tot elfde mei 2010', '2012-08-10')[0]->end?->get('day'))->toBe(11)
        ->and($dutch->parseDateText('24ste oktober, 21:00', '2017-07-07 15:00')?->toDateTimeString())->toBe('2017-10-24 21:00:00');
});

it('parses dutch month only and month year expressions', function () {
    $dutch = Chrono::nl();

    expect($dutch->parseText('Planning januari, 2012', '2012-08-10')[0]->text)
        ->toBe('januari, 2012')
        ->and($dutch->parseText('Planning januari, 2012', '2012-08-10')[0]->start->tags())->toContain('parser/NLMonthNameParser')
        ->and($dutch->parseDateText('Planning januari, 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-01-01 12:00:00')
        ->and($dutch->parseDateText('Planning januari', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-01-01 12:00:00')
        ->and($dutch->parseDateText('Planning jan 87', '2012-08-10')?->toDateTimeString())
        ->toBe('1987-01-01 12:00:00');
});

it('parses dutch slash and casual year month day formats', function () {
    $dutch = Chrono::nl();

    expect($dutch->parseText('Gepubliceerd 10/08/2012', '2012-08-10')[0]->text)
        ->toBe('10/08/2012')
        ->and($dutch->parseDateText('Gepubliceerd 10/08/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($dutch->parseText('    04/2016   ', '2012-08-10')[0]->index)->toBe(4)
        ->and($dutch->parseText('    04/2016   ', '2012-08-10')[0]->text)->toBe('04/2016')
        ->and($dutch->parseText('Contract geldig vanaf 06/2005', '2012-08-10')[0]->text)
        ->toBe('06/2005')
        ->and($dutch->parseText('Contract geldig vanaf 06/2005', '2012-08-10')[0]->start->tags())->toContain('parser/NLSlashMonthFormatParser')
        ->and($dutch->parseDateText('Contract geldig vanaf 06/2005', '2012-08-10')?->toDateTimeString())
        ->toBe('2005-06-01 12:00:00')
        ->and($dutch->parseText('8/10/2012', '2012-10-08')[0]->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($dutch->parseDateText('8/10/2012', '2012-10-08')?->toDateTimeString())->toBe('2012-10-08 12:00:00')
        ->and($dutch->parseText(': 8/1/2012', '2012-01-08')[0]->index)->toBe(2)
        ->and($dutch->parseDateText(': 8/1/2012', '2012-01-08')?->toDateTimeString())->toBe('2012-01-08 12:00:00')
        ->and($dutch->parseDateText('8/10', '2012-10-08')?->toDateTimeString())->toBe('2012-10-08 12:00:00')
        ->and($dutch->parseText('De deadline is dinsdag 11/3/2015', '2015-11-03')[0]->text)->toBe('dinsdag 11/3/2015')
        ->and($dutch->parseDateText('28/2/2014', '2012-08-10')?->toDateTimeString())->toBe('2014-02-28 12:00:00')
        ->and($dutch->parseDateText('30-12-16', '2012-08-10')?->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($dutch->parseText('vrijdag 30-12-16', '2012-08-10')[0]->text)->toBe('vrijdag 30-12-16')
        ->and($dutch->parseText('10/8/2012 - 15/8/2012', '2012-08-10')[0]->end?->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($dutch->parseText('2015-05-25', '2012-08-10')[0]->start->tags())->toContain('parser/ISOFormatParser')
        ->and($dutch->parseDateText('2015-05-25', '2012-08-10')?->toDateTimeString())->toBe('2015-05-25 12:00:00')
        ->and($dutch->parseDateText('25.05.2015', '2012-08-10')?->toDateTimeString())->toBe('2015-05-25 12:00:00')
        ->and($dutch->parseText('Gepubliceerd 2026/06/23', '2012-08-10')[0]->start->tags())->toContain('parser/NLCasualYearMonthDayParser')
        ->and($dutch->parseDateText('Gepubliceerd 2026/06/23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($dutch->parseDateText('Gepubliceerd 2026 juni 23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00');
});

it('parses swedish casual date references', function () {
    $swedish = Chrono::sv();
    $now = $swedish->parseText('nu', '2012-08-10 09:30:45.123')[0];
    $tomorrowMorning = $swedish->parseText('imorgon på morgonen', '2012-08-10 09:30')[0];
    $morning = $swedish->parseText('idag på morgonen', '2012-08-10')[0];
    $forenoon = $swedish->parseText('idag på förmiddagen', '2012-08-10')[0];
    $midday = $swedish->parseText('idag på middagen', '2012-08-10')[0];
    $afternoon = $swedish->parseText('idag på eftermiddagen', '2012-08-10')[0];
    $evening = $swedish->parseText('idag på kvällen', '2012-08-10')[0];
    $night = $swedish->parseText('idag på natten', '2012-08-10')[0];
    $midnight = $swedish->parseText('idag vid midnatt', '2012-08-10 09:30')[0];

    expect($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 09:30:45.123')
        ->and($now->start->tags())->toContain('parser/SVCasualDateParser')
        ->and($swedish->parseDateText('idag', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-10 09:30:00')
        ->and($swedish->parseDateText('imorgon', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-11 09:30:00')
        ->and($swedish->parseDateText('igår', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 09:30:00')
        ->and($swedish->parseDateText('förrgår', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($swedish->parseDateText('i förrgår', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($tomorrowMorning->text)->toBe('imorgon på morgonen')
        ->and($tomorrowMorning->start->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($forenoon->start->get('hour'))->toBe(9)
        ->and($midday->start->get('hour'))->toBe(12)
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($evening->start->get('hour'))->toBe(20)
        ->and($night->start->get('hour'))->toBe(2)
        ->and($midnight->text)->toBe('idag vid midnatt')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00');
});

it('parses swedish month name and slash dates', function () {
    $swedish = Chrono::sv();
    $dayMonth = $swedish->parseText('den 15 augusti', '2012-08-10')[0];
    $dayMonthYear = $swedish->parseText('15 augusti 2012', '2012-08-10')[0];
    $abbreviated = $swedish->parseText('15 aug 2012', '2012-08-10')[0];
    $explicit = $swedish->parseText('den 10 augusti 2012', '2012-08-10')[0];
    $hyphenRange = $swedish->parseText('15-16 augusti', '2012-08-10')[0];
    $range = $swedish->parseText('10-12 augusti', '2012-08-10')[0];
    $tillRange = $swedish->parseText('15 till 16 augusti', '2012-08-10')[0];
    $slash = $swedish->parseText('10/08/2012', '2012-08-10')[0];

    expect($dayMonth->start->get('year'))->toBe(2012)
        ->and($dayMonth->start->get('month'))->toBe(8)
        ->and($dayMonth->start->get('day'))->toBe(15)
        ->and($dayMonthYear->start->get('year'))->toBe(2012)
        ->and($dayMonthYear->start->get('month'))->toBe(8)
        ->and($dayMonthYear->start->get('day'))->toBe(15)
        ->and($abbreviated->start->get('year'))->toBe(2012)
        ->and($abbreviated->start->get('month'))->toBe(8)
        ->and($abbreviated->start->get('day'))->toBe(15)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($explicit->start->tags())->toContain('parser/SVMonthNameLittleEndianParser')
        ->and($hyphenRange->start->get('year'))->toBe(2012)
        ->and($hyphenRange->start->get('month'))->toBe(8)
        ->and($hyphenRange->start->get('day'))->toBe(15)
        ->and($hyphenRange->end?->get('year'))->toBe(2012)
        ->and($hyphenRange->end?->get('month'))->toBe(8)
        ->and($hyphenRange->end?->get('day'))->toBe(16)
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->end?->tags())->toContain('parser/SVMonthNameLittleEndianParser')
        ->and($tillRange->end?->date()->toDateTimeString())->toBe('2012-08-16 12:00:00')
        ->and($slash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($swedish->parseText('32 augusti', '2012-08-10'))->toBe([]);
});

it('parses swedish weekday references', function () {
    $swedish = Chrono::sv();
    $monday = $swedish->parseText('måndag', '2012-08-09')[0];
    $weekday = $swedish->parseText('på onsdag', '2012-08-10')[0];
    $prefixedMonday = $swedish->parseText('på måndag', '2012-08-09')[0];
    $nextMonday = $swedish->parseText('nästa måndag', '2012-08-09')[0];
    $lastMonday = $swedish->parseText('förra måndag', '2012-08-09')[0];

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('måndag')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($weekday->text)->toBe('på onsdag')
        ->and($weekday->start->tags())->toContain('parser/SVWeekdayParser')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($prefixedMonday->index)->toBe(0)
        ->and($prefixedMonday->text)->toBe('på måndag')
        ->and($prefixedMonday->start->get('year'))->toBe(2012)
        ->and($prefixedMonday->start->get('month'))->toBe(8)
        ->and($prefixedMonday->start->get('day'))->toBe(6)
        ->and($prefixedMonday->start->get('weekday'))->toBe(1)
        ->and($nextMonday->text)->toBe('nästa måndag')
        ->and($nextMonday->start->get('day'))->toBe(13)
        ->and($nextMonday->start->get('weekday'))->toBe(1)
        ->and($lastMonday->text)->toBe('förra måndag')
        ->and($lastMonday->start->get('day'))->toBe(6)
        ->and($lastMonday->start->get('weekday'))->toBe(1)
        ->and($swedish->parseText('söndag', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($swedish->parseText('tisdag', '2012-08-09')[0]->start->get('weekday'))->toBe(2)
        ->and($swedish->parseText('fredag', '2012-08-09')[0]->start->get('weekday'))->toBe(5)
        ->and($swedish->parseText('lördag', '2012-08-09')[0]->start->get('weekday'))->toBe(6)
        ->and($swedish->parseDateText('nästa måndag', '2012-08-10')?->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($swedish->parseDateText('förra måndag', '2012-08-10')?->toDateTimeString())->toBe('2012-08-06 12:00:00');
});

it('parses swedish casual relative time units', function () {
    $swedish = Chrono::sv();
    $next = $swedish->parseText('nästa 2 dagar', '2012-08-10 09:30')[0];
    $weeks = $swedish->parseText('nästa 2 veckor', '2016-10-01 12:00')[0];
    $compound = $swedish->parseText('nästa 2 veckor 3 dagar', '2016-10-01 12:00')[0];
    $previous = $swedish->parseText('förra 3 veckor', '2012-08-10 09:30')[0];
    $previousWords = $swedish->parseText('förra två veckor', '2016-10-01 12:00')[0];
    $passed = $swedish->parseText('passerade 2 dagar', '2016-10-01 12:00')[0];
    $plusMinutes = $swedish->parseText('+15 minuter', '2012-07-10 12:14')[0];
    $plusOneCompactMinute = $swedish->parseText('+1min', '2012-07-10 12:14')[0];
    $compactPast = $swedish->parseText('-2tim5min', '2016-10-01 12:00')[0];
    $minusYears = $swedish->parseText('-3år', '2015-07-10 12:14')[0];

    expect($next->start->date()->toDateTimeString())->toBe('2012-08-12 09:30:00')
        ->and($next->start->tags())->toContain('parser/SVTimeUnitCasualRelativeFormatParser')
        ->and($next->tags())->toContain('result/relativeDate')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2016-10-15 12:00:00')
        ->and($compound->start->date()->toDateTimeString())->toBe('2016-10-18 12:00:00')
        ->and($previous->start->date()->toDateTimeString())->toBe('2012-07-20 09:30:00')
        ->and($previousWords->start->date()->toDateTimeString())->toBe('2016-09-17 12:00:00')
        ->and($passed->start->date()->toDateTimeString())->toBe('2016-09-29 12:00:00')
        ->and($swedish->parseText('nästa två år', '2016-10-01 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2018-10-01 12:00:00')
        ->and($swedish->parseText('efter en timme', '2016-10-01 15:00')[0]->start->date()->toDateTimeString())
        ->toBe('2016-10-01 16:00:00')
        ->and($swedish->parseText('+2 månader, 5 dagar', '2016-10-01 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2016-12-06 12:00:00')
        ->and($plusMinutes->text)->toBe('+15 minuter')
        ->and($plusMinutes->start->get('hour'))->toBe(12)
        ->and($plusMinutes->start->get('minute'))->toBe(29)
        ->and($swedish->parseText('+15min', '2012-07-10 12:14')[0]->start->date()->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and($swedish->parseText('+1 dag 2 timmar', '2012-07-10 12:14')[0]->start->date()->toDateTimeString())
        ->toBe('2012-07-11 14:14:00')
        ->and($plusOneCompactMinute->text)->toBe('+1min')
        ->and($plusOneCompactMinute->start->get('hour'))->toBe(12)
        ->and($plusOneCompactMinute->start->get('minute'))->toBe(15)
        ->and($compactPast->text)->toBe('-2tim5min')
        ->and($compactPast->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00')
        ->and($minusYears->text)->toBe('-3år')
        ->and($minusYears->start->get('year'))->toBe(2012)
        ->and($minusYears->start->get('month'))->toBe(7)
        ->and($minusYears->start->get('day'))->toBe(10)
        ->and($minusYears->start->get('hour'))->toBe(12)
        ->and($minusYears->start->get('minute'))->toBe(14)
        ->and($swedish->parseText('3år', '2016-10-01 12:00'))->toBe([])
        ->and($swedish->parseText('1 månad', '2016-10-01 12:00'))->toBe([]);
});

it('parses portuguese casual date and time references', function () {
    $portuguese = Chrono::pt();
    $prefixedNow = $portuguese->parseText('O prazo é agora', '2012-08-10 08:09:10.011')[0];
    $prefixedToday = $portuguese->parseText('O prazo é hoje', '2012-08-10 12:00')[0];
    $prefixedTomorrow = $portuguese->parseText('O prazo é Amanhã', '2012-08-10 12:00')[0];
    $prefixedYesterday = $portuguese->parseText('O prazo foi ontem', '2012-08-10 12:00')[0];
    $lastNight = $portuguese->parseText('O prazo foi ontem à noite ', '2012-08-10 12:00')[0];
    $morning = $portuguese->parseText('O prazo foi esta manhã ', '2012-08-10 12:00')[0];
    $afternoon = $portuguese->parseText('O prazo foi esta tarde ', '2012-08-10 12:00')[0];
    $combined = $portuguese->parseText('O prazo é hoje às 5PM', '2012-08-10 12:00')[0];
    $tonightOnly = $portuguese->parseText('esta noite', '2012-01-01 12:00')[0];
    $tonightWithMeridiem = $portuguese->parseText('esta noite 8pm', '2012-01-01 12:00')[0];
    $tonight = $portuguese->parseText('esta noite às 8', '2012-01-01 12:00')[0];
    $weekdayThursday = $portuguese->parseText('quinta', '2012-08-10')[0];
    $weekdayFriday = $portuguese->parseText('sexta', '2012-08-10')[0];
    $noon = $portuguese->parseText('ao meio-dia', '2020-09-01 11:00')[0];
    $midnight = $portuguese->parseText('a meia-noite', '2020-09-01 11:00')[0];

    expect($prefixedNow->index)->toBe(11)
        ->and($prefixedNow->text)->toBe('agora')
        ->and($prefixedNow->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($prefixedNow->start->tags())->toContain('parser/PTCasualDateParser')
        ->and($prefixedToday->index)->toBe(11)
        ->and($prefixedToday->text)->toBe('hoje')
        ->and($prefixedToday->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedTomorrow->text)->toBe('Amanhã')
        ->and($prefixedTomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($portuguese->parseText('O prazo é Amanhã', '2012-08-10 01:00')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-11 01:00:00')
        ->and($prefixedYesterday->index)->toBe(12)
        ->and($prefixedYesterday->text)->toBe('ontem')
        ->and($prefixedYesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($lastNight->text)->toBe('ontem à noite')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00')
        ->and($portuguese->parseDateText('hoje', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-10 09:30:00')
        ->and($portuguese->parseDateText('amanhã', '2012-08-10 01:00')?->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($portuguese->parseDateText('ontem', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 09:30:00')
        ->and($morning->text)->toBe('esta manhã')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($morning->start->tags())->toContain('parser/PTCasualTimeParser')
        ->and($afternoon->text)->toBe('esta tarde')
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($combined->text)->toBe('hoje às 5PM')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($tonightOnly->text)->toBe('esta noite')
        ->and($tonightOnly->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonightOnly->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($tonightWithMeridiem->text)->toBe('esta noite 8pm')
        ->and($tonightWithMeridiem->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($tonight->text)->toBe('esta noite às 8')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($tonight->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($weekdayThursday->text)->toBe('quinta')
        ->and($weekdayThursday->start->get('weekday'))->toBe(4)
        ->and($weekdayFriday->text)->toBe('sexta')
        ->and($weekdayFriday->start->get('weekday'))->toBe(5)
        ->and($noon->text)->toBe('meio-dia')
        ->and($noon->start->date()->toDateTimeString())->toBe('2020-09-01 12:00:00')
        ->and($midnight->text)->toBe('meia-noite')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2020-09-02 00:00:00')
        ->and($portuguese->parseText('naohoje', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('hyamanhã', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('xontem', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('porhora', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('agoraxsd', '2012-08-10'))->toBe([]);
});

it('parses portuguese month name dates and ranges', function () {
    $portuguese = Chrono::pt();
    $explicit = $portuguese->parseText('10 de agosto de 2012', '2012-08-10')[0];
    $beforeCommonEra = $portuguese->parseText('10 Agosto 234 AC', '2012-08-10')[0];
    $commonEra = $portuguese->parseText('10 Agosto 88 d. C.', '2012-08-10')[0];
    $weekdayMonth = $portuguese->parseText('Dom 15Set', '2013-08-10')[0];
    $upperWeekdayMonth = $portuguese->parseText('DOM 15SET', '2013-08-10')[0];
    $prefixedMonth = $portuguese->parseText('O prazo é 10 Agosto', '2012-08-10')[0];
    $weekdayPrefixedMonth = $portuguese->parseText('O prazo é terça-feira, 10 de janeiro', '2012-08-10')[0];
    $abbreviatedWeekdayPrefixedMonth = $portuguese->parseText('O prazo é Qua, 10 Janeiro', '2012-08-10')[0];
    $range = $portuguese->parseText('10-12 de agosto', '2012-08-10')[0];
    $dashRange = $portuguese->parseText('10 - 22 Agosto 2012', '2012-08-10')[0];
    $aRange = $portuguese->parseText('10 a 22 Agosto 2012', '2012-08-10')[0];
    $untilRange = $portuguese->parseText('15 até 16 agosto', '2012-08-10')[0];
    $crossMonthRange = $portuguese->parseText('10 Agosto - 12 Setembro', '2012-08-10')[0];
    $crossMonthWithYear = $portuguese->parseText('10 Agosto - 12 Setembro 2013', '2012-08-10')[0];
    $dateTime = $portuguese->parseText('12 de Julho às 19:00', '2012-08-10')[0];

    expect($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($explicit->start->tags())->toContain('parser/PTMonthNameLittleEndianParser')
        ->and($portuguese->parseText('10 Agosto 2012', '2012-08-10')[0]->text)->toBe('10 Agosto 2012')
        ->and($beforeCommonEra->text)->toBe('10 Agosto 234 AC')
        ->and($beforeCommonEra->start->get('year'))->toBe(-234)
        ->and($beforeCommonEra->start->date()->format('Y-m-d H:i:s'))->toBe('-0234-08-10 12:00:00')
        ->and($commonEra->text)->toBe('10 Agosto 88 d. C.')
        ->and($commonEra->start->get('year'))->toBe(88)
        ->and($commonEra->start->get('month'))->toBe(8)
        ->and($commonEra->start->get('day'))->toBe(10)
        ->and($weekdayMonth->index)->toBe(0)
        ->and($weekdayMonth->text)->toBe('Dom 15Set')
        ->and($weekdayMonth->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($upperWeekdayMonth->text)->toBe('DOM 15SET')
        ->and($upperWeekdayMonth->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($prefixedMonth->text)->toBe('10 Agosto')
        ->and($prefixedMonth->index)->toBe(11)
        ->and($prefixedMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($weekdayPrefixedMonth->text)->toBe('terça-feira, 10 de janeiro')
        ->and($weekdayPrefixedMonth->index)->toBe(11)
        ->and($weekdayPrefixedMonth->start->get('weekday'))->toBe(2)
        ->and($weekdayPrefixedMonth->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($abbreviatedWeekdayPrefixedMonth->text)->toBe('Qua, 10 Janeiro')
        ->and($abbreviatedWeekdayPrefixedMonth->start->get('weekday'))->toBe(3)
        ->and($abbreviatedWeekdayPrefixedMonth->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->end?->tags())->toContain('parser/PTMonthNameLittleEndianParser')
        ->and($dashRange->text)->toBe('10 - 22 Agosto 2012')
        ->and($dashRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($aRange->text)->toBe('10 a 22 Agosto 2012')
        ->and($aRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($untilRange->text)->toBe('15 até 16 agosto')
        ->and($untilRange->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($untilRange->end?->date()->toDateTimeString())->toBe('2012-08-16 12:00:00')
        ->and($crossMonthRange->text)->toBe('10 Agosto - 12 Setembro')
        ->and($crossMonthRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($crossMonthRange->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossMonthWithYear->text)->toBe('10 Agosto - 12 Setembro 2013')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($dateTime->text)->toBe('12 de Julho às 19:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($portuguese->parseText('32 Agosto 2014', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('29 Fevereiro 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strictPortuguese()->parseText('32 Agosto', '2012-08-10'))->toBe([]);
});

it('parses portuguese weekday and time expressions', function () {
    $portuguese = Chrono::pt();
    $weekday = $portuguese->parseText('quarta-feira', '2012-08-10')[0];
    $weekdaySlashMonday = $portuguese->parseText('segunda 8/2/2016', '2012-08-10')[0];
    $time = $portuguese->parseText('às 6:30 - 8:45', '2012-08-10')[0];
    $dotTime = $portuguese->parseText('Ficaremos às 6.13 AM', '2012-08-10')[0];
    $dotRange = $portuguese->parseText('8:10 - 12.32', '2012-08-10')[0];
    $prefixedRange = $portuguese->parseText(' de 6:30pm a 11:00pm ', '2012-08-10')[0];
    $dateTime = $portuguese->parseText('Algo passou em 10 de Agosto de 2012 10:12:59 pm', '2012-08-10')[0];
    $impliedMeridiemRange = $portuguese->parseText('de 1pm a 3', '2012-08-10')[0];
    $weekdaySlash = $portuguese->parseText('Terça-feira 9/2/2016', '2012-08-10')[0];
    $compactRange = $portuguese->parseText('segunda 4/29/2013 630-930am', '2012-08-10')[0];
    $compactTime = $portuguese->parseText('terça 5/1/2013 1115am', '2012-08-10')[0];
    $compactAfternoon = $portuguese->parseText('quarta 5/3/2013 1230pm', '2012-08-10')[0];
    $compactWeekendRange = $portuguese->parseText('domingo 5/6/2013  750am-910am', '2012-08-10')[0];
    $hyphenatedWeekdayRange = $portuguese->parseText('segunda-feira 5/13/2013 630-930am', '2012-08-10')[0];
    $hyphenatedWeekdayTime = $portuguese->parseText('quarta-feira 5/15/2013 1030am', '2012-08-10')[0];
    $weekdayNoMeridiemTime = $portuguese->parseText('quinta 6/21/2013 2:30', '2012-08-10')[0];
    $compactMeridiemRange = $portuguese->parseText('terça-feira 7/2/2013 1-230 pm', '2012-08-10')[0];
    $punctuatedRange = $portuguese->parseText('Segunda-feira, 6/24/2013, 7:00pm - 8:30pm', '2012-08-10')[0];
    $monthNameDateTime = $portuguese->parseText('Quarta, 3 Julho de 2013 às 2pm', '2012-08-10')[0];
    $shortMeridiem = $portuguese->parseText('6pm', '2012-08-10')[0];
    $spacedMeridiem = $portuguese->parseText('6 pm', '2012-08-10')[0];
    $shortRange = $portuguese->parseText('7-10pm', '2012-08-10')[0];
    $shortDotTime = $portuguese->parseText('11.1pm', '2012-08-10')[0];
    $atNoon = $portuguese->parseText('às 12', '2012-08-10')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/PTWeekdayParser')
        ->and($portuguese->parseDateText('próximo segunda', '2012-08-10')?->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($weekdaySlashMonday->index)->toBe(0)
        ->and($weekdaySlashMonday->text)->toBe('segunda 8/2/2016')
        ->and($weekdaySlashMonday->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($time->start->tags())->toContain('parser/PTTimeExpressionParser')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dotTime->index)->toBe(10)
        ->and($dotTime->text)->toBe('às 6.13 AM')
        ->and($dotTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($dotRange->text)->toBe('8:10 - 12.32')
        ->and($dotRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($dotRange->start->isCertain('year'))->toBeFalse()
        ->and($dotRange->start->isCertain('month'))->toBeFalse()
        ->and($dotRange->start->isCertain('day'))->toBeFalse()
        ->and($dotRange->start->isCertain('hour'))->toBeTrue()
        ->and($dotRange->start->isCertain('minute'))->toBeTrue()
        ->and($dotRange->start->isCertain('second'))->toBeFalse()
        ->and($dotRange->start->isCertain('millisecond'))->toBeFalse()
        ->and($dotRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($dotRange->end?->isCertain('year'))->toBeFalse()
        ->and($dotRange->end?->isCertain('month'))->toBeFalse()
        ->and($dotRange->end?->isCertain('day'))->toBeFalse()
        ->and($dotRange->end?->isCertain('hour'))->toBeTrue()
        ->and($dotRange->end?->isCertain('minute'))->toBeTrue()
        ->and($dotRange->end?->isCertain('second'))->toBeFalse()
        ->and($dotRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($prefixedRange->index)->toBe(1)
        ->and($prefixedRange->text)->toBe('de 6:30pm a 11:00pm')
        ->and($prefixedRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($prefixedRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($prefixedRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($prefixedRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($dateTime->index)->toBe(15)
        ->and($dateTime->text)->toBe('10 de Agosto de 2012 10:12:59 pm')
        ->and($dateTime->start->get('year'))->toBe(2012)
        ->and($dateTime->start->get('month'))->toBe(8)
        ->and($dateTime->start->get('day'))->toBe(10)
        ->and($dateTime->start->get('hour'))->toBe(22)
        ->and($dateTime->start->get('minute'))->toBe(12)
        ->and($dateTime->start->get('second'))->toBe(59)
        ->and($dateTime->start->get('millisecond'))->toBe(0)
        ->and($dateTime->start->isCertain('millisecond'))->toBeFalse()
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 22:12:59')
        ->and($impliedMeridiemRange->text)->toBe('de 1pm a 3')
        ->and($impliedMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($impliedMeridiemRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($impliedMeridiemRange->start->isCertain('meridiem'))->toBeTrue()
        ->and($impliedMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($impliedMeridiemRange->end?->isCertain('meridiem'))->toBeTrue()
        ->and($weekdaySlash->text)->toBe('Terça-feira 9/2/2016')
        ->and($weekdaySlash->start->date()->toDateTimeString())->toBe('2016-02-09 12:00:00')
        ->and($weekdaySlash->start->tags())->toContain('parser/PTWeekdayParser')
        ->and($compactRange->text)->toBe('segunda 4/29/2013 630-930am')
        ->and($compactRange->start->date()->toDateTimeString())->toBe('2013-04-29 06:30:00')
        ->and($compactRange->end?->date()->toDateTimeString())->toBe('2013-04-29 09:30:00')
        ->and($compactTime->text)->toBe('terça 5/1/2013 1115am')
        ->and($compactTime->start->date()->toDateTimeString())->toBe('2013-01-05 11:15:00')
        ->and($compactAfternoon->text)->toBe('quarta 5/3/2013 1230pm')
        ->and($compactWeekendRange->text)->toBe('domingo 5/6/2013  750am-910am')
        ->and($compactWeekendRange->start->date()->toDateTimeString())->toBe('2013-06-05 07:50:00')
        ->and($compactWeekendRange->end?->date()->toDateTimeString())->toBe('2013-06-05 09:10:00')
        ->and($hyphenatedWeekdayRange->text)->toBe('segunda-feira 5/13/2013 630-930am')
        ->and($hyphenatedWeekdayTime->text)->toBe('quarta-feira 5/15/2013 1030am')
        ->and($weekdayNoMeridiemTime->text)->toBe('quinta 6/21/2013 2:30')
        ->and($compactMeridiemRange->text)->toBe('terça-feira 7/2/2013 1-230 pm')
        ->and($punctuatedRange->text)->toBe('Segunda-feira, 6/24/2013, 7:00pm - 8:30pm')
        ->and($punctuatedRange->start->date()->toDateTimeString())->toBe('2013-06-24 19:00:00')
        ->and($punctuatedRange->end?->date()->toDateTimeString())->toBe('2013-06-24 20:30:00')
        ->and($monthNameDateTime->text)->toBe('Quarta, 3 Julho de 2013 às 2pm')
        ->and($monthNameDateTime->start->date()->toDateTimeString())->toBe('2013-07-03 14:00:00')
        ->and($shortMeridiem->text)->toBe('6pm')
        ->and($shortMeridiem->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($spacedMeridiem->text)->toBe('6 pm')
        ->and($spacedMeridiem->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($shortRange->text)->toBe('7-10pm')
        ->and($shortRange->start->date()->toDateTimeString())->toBe('2012-08-10 19:00:00')
        ->and($shortRange->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($shortDotTime->text)->toBe('11.1pm')
        ->and($shortDotTime->start->date()->toDateTimeString())->toBe('2012-08-10 23:01:00')
        ->and($atNoon->text)->toBe('às 12')
        ->and($atNoon->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('merges portuguese dates with times and date ranges', function () {
    $portuguese = Chrono::pt();
    $dateTime = $portuguese->parseText('10 de agosto de 2012 às 6:30', '2012-08-10')[0];
    $dateRange = $portuguese->parseText('10 de agosto - 12 de agosto', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 de agosto de 2012 às 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($dateRange->text)->toBe('10 de agosto - 12 de agosto')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange');
});

it('parses japanese casual date references', function () {
    $japanese = Chrono::ja();
    $today = $japanese->parseText('今日', '2012-08-10 09:30')[0];
    $tonight = $japanese->parseText('今夜', '2012-08-10 09:30')[0];

    expect($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($today->start->tags())->toContain('parser/JPCasualDateParser')
        ->and($japanese->parseText('今日感じたことを忘れずに', '2012-08-10 12:00')[0]->text)->toBe('今日')
        ->and($japanese->parseText('きょう感じたことを忘れずに', '2012-08-10 12:00')[0]->text)->toBe('きょう')
        ->and($japanese->parseText('本日はお日柄もよく', '2012-08-10 12:00')[0]->text)->toBe('本日')
        ->and($japanese->parseText('ほんじつはお日柄もよく', '2012-08-10 12:00')[0]->text)->toBe('ほんじつ')
        ->and($japanese->parseDateText('昨日', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($japanese->parseText('昨日の全国観測値ランキング', '2012-08-10 12:00')[0]->text)->toBe('昨日')
        ->and($japanese->parseText('きのうの全国観測値ランキング', '2012-08-10 12:00')[0]->text)->toBe('きのう')
        ->and($japanese->parseDateText('明日', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($japanese->parseText('明日の天気は晴れです', '2012-08-10 12:00')[0]->text)->toBe('明日')
        ->and($japanese->parseText('あしたの天気は晴れです', '2012-08-10 12:00')[0]->text)->toBe('あした')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($japanese->parseText('こんやには雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('こんや')
        ->and($japanese->parseDateText('今夕には雨が降るでしょう', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($japanese->parseText('こんゆうには雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('こんゆう')
        ->and($japanese->parseText('今晩には雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('今晩')
        ->and($japanese->parseText('こんばんには雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('こんばん')
        ->and($japanese->parseText('今朝食べたパンは美味しかった', '2012-08-10 12:00')[0]->text)->toBe('今朝')
        ->and($japanese->parseDateText('けさ食べたパンは美味しかった', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 06:00:00');
});

it('parses japanese standard and slash dates', function () {
    $japanese = Chrono::ja();
    $standard = $japanese->parseText('2014年7月12日', '2012-08-10')[0];
    $prefixedStandard = $japanese->parseText('主な株主（2012年3月31日現在）', '2012-08-10')[0];
    $fullWidthMonthStandard = $japanese->parseText('主な株主（2012年９月3日現在）', '2012-08-10')[0];
    $leapDay = $japanese->parseText('主な株主（2020年2月29日現在）', '2019-08-10')[0];
    $missingYear = $japanese->parseText('主な株主（９月3日現在）', '2012-08-10')[0];
    $era = $japanese->parseText('令和6年7月12日', '2012-08-10')[0];
    $heiseiEra = $japanese->parseText('主な株主（平成26年12月29日）', '2012-08-10')[0];
    $showaEra = $japanese->parseText('主な株主（昭和６４年１月７日）', '2012-08-10')[0];
    $eraSecondYear = $japanese->parseText('主な株主（令和2年5月1日）', '2012-08-10')[0];
    $sameYear = $japanese->parseText('主な株主（同年7月27日）', '2012-08-10')[0];
    $slash = $japanese->parseText('2020/7/12', '2012-08-10')[0];
    $fullSlash = $japanese->parseText('2012/3/31', '2012-08-10')[0];
    $monthDaySlash = $japanese->parseText('12/31', '2012-08-10')[0];
    $earlyMonthDaySlash = $japanese->parseText('8/5', '2012-08-10')[0];
    $slashDateTime = $japanese->parseText('12/9の16:00', '2025-12-10 12:00')[0];
    $fullWidth = $japanese->parseText('２０２０／７／１２', '2012-08-10')[0];
    $eraFirstYear = $japanese->parseText('主な株主（令和元年5月1日）', '2012-08-10')[0];
    $currentYear = $japanese->parseText('主な株主（本年7月27日）', '2012-08-10')[0];
    $currentYearAlternative = $japanese->parseText('主な株主（今年7月27日）', '2012-08-10')[0];
    $currentYearLateMonth = $japanese->parseText('主な株主（今年11月27日）', '2012-01-10')[0];
    $yearlessPast = $japanese->parseText('7月27日', '2012-08-10')[0];
    $yearlessClosest = $japanese->parseText('11月27日', '2012-01-10')[0];
    $standardRange = $japanese->parseText('2013年12月26日-2014年1月7日', '2012-08-10')[0];
    $fullWidthRange = $japanese->parseText('２０１３年１２月２６日ー2014年1月7日', '2012-08-10')[0];
    $spacedRange = $japanese->parseText('2013年12月26日 ～ ２０１４年１月７日', '2012-08-10')[0];
    $slashRange = $japanese->parseText('2013/12/26~2014/1/7', '2012-08-10')[0];

    expect($standard->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($standard->start->tags())->toContain('parser/JPStandardParser')
        ->and($prefixedStandard->index)->toBe(15)
        ->and($prefixedStandard->text)->toBe('2012年3月31日')
        ->and($prefixedStandard->start->date()->toDateTimeString())->toBe('2012-03-31 12:00:00')
        ->and($fullWidthMonthStandard->text)->toBe('2012年９月3日')
        ->and($fullWidthMonthStandard->start->date()->toDateTimeString())->toBe('2012-09-03 12:00:00')
        ->and($leapDay->start->date()->toDateTimeString())->toBe('2020-02-29 12:00:00')
        ->and($missingYear->text)->toBe('９月3日')
        ->and($missingYear->start->date()->toDateTimeString())->toBe('2012-09-03 12:00:00')
        ->and($era->start->date()->toDateTimeString())->toBe('2024-07-12 12:00:00')
        ->and($heiseiEra->text)->toBe('平成26年12月29日')
        ->and($heiseiEra->start->date()->toDateTimeString())->toBe('2014-12-29 12:00:00')
        ->and($showaEra->text)->toBe('昭和６４年１月７日')
        ->and($showaEra->start->date()->toDateTimeString())->toBe('1989-01-07 12:00:00')
        ->and($eraFirstYear->text)->toBe('令和元年5月1日')
        ->and($eraFirstYear->start->date()->toDateTimeString())->toBe('2019-05-01 12:00:00')
        ->and($eraSecondYear->text)->toBe('令和2年5月1日')
        ->and($eraSecondYear->start->date()->toDateTimeString())->toBe('2020-05-01 12:00:00')
        ->and($sameYear->text)->toBe('同年7月27日')
        ->and($sameYear->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($currentYear->text)->toBe('本年7月27日')
        ->and($currentYear->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($currentYearAlternative->text)->toBe('今年7月27日')
        ->and($currentYearAlternative->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($currentYearLateMonth->text)->toBe('今年11月27日')
        ->and($currentYearLateMonth->start->date()->toDateTimeString())->toBe('2012-11-27 12:00:00')
        ->and($yearlessPast->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($yearlessPast->start->isCertain('year'))->toBeFalse()
        ->and($yearlessClosest->start->date()->toDateTimeString())->toBe('2011-11-27 12:00:00')
        ->and($slash->start->date()->toDateTimeString())->toBe('2020-07-12 12:00:00')
        ->and($slash->start->tags())->toContain('parser/JPSlashDateFormatParser')
        ->and($fullSlash->text)->toBe('2012/3/31')
        ->and($fullSlash->start->date()->toDateTimeString())->toBe('2012-03-31 12:00:00')
        ->and($monthDaySlash->start->date()->toDateTimeString())->toBe('2012-12-31 12:00:00')
        ->and($earlyMonthDaySlash->start->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($slashDateTime->text)->toBe('12/9の16:00')
        ->and($slashDateTime->start->date()->toDateTimeString())->toBe('2025-12-09 16:00:00')
        ->and($slashDateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($fullWidth->start->date()->toDateTimeString())->toBe('2020-07-12 12:00:00')
        ->and($standardRange->start->date()->toDateTimeString())->toBe('2013-12-26 12:00:00')
        ->and($standardRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00')
        ->and($fullWidthRange->text)->toBe('２０１３年１２月２６日ー2014年1月7日')
        ->and($fullWidthRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00')
        ->and($spacedRange->text)->toBe('2013年12月26日 ～ ２０１４年１月７日')
        ->and($spacedRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00')
        ->and($slashRange->text)->toBe('2013/12/26~2014/1/7')
        ->and($slashRange->start->date()->toDateTimeString())->toBe('2013-12-26 12:00:00')
        ->and($slashRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00');
});

it('parses japanese weekdays and parenthesized weekdays', function () {
    $japanese = Chrono::ja();
    $weekday = $japanese->parseText('水曜日', '2012-08-10')[0];
    $parenthesized = $japanese->parseText('（土）', '2012-08-10')[0];
    $forwardRange = $japanese->parseText('土曜日～月曜日', '2016-09-02', ['forwardDate' => true])[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/JPWeekdayParser')
        ->and($japanese->parseDateText('次の月曜日', '2012-08-10')?->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($parenthesized->start->date()->toDateTimeString())->toBe('2012-08-11 00:00:00')
        ->and($parenthesized->start->tags())->toContain('parser/JPWeekdayWithParenthesesParser')
        ->and($forwardRange->text)->toBe('土曜日～月曜日')
        ->and($forwardRange->start->date()->toDateTimeString())->toBe('2016-09-03 00:00:00')
        ->and($forwardRange->end?->date()->toDateTimeString())->toBe('2016-09-05 00:00:00');
});

it('parses japanese time expressions and ranges', function () {
    $japanese = Chrono::ja();
    $prefixedMinuteTime = $japanese->parseText('私は午前6時13分に起きた', '2012-08-10')[0];
    $prefixedHourTime = $japanese->parseText('私は午前8時に起きる', '2012-08-10 12:00')[0];
    $time = $japanese->parseText('午後3時半', '2012-08-10')[0];
    $fullWidthDateTime = $japanese->parseText('１２月９日の１６：３０', '2025-12-10 12:00')[0];
    $range = $japanese->parseText('午後10時から1時', '2012-08-10')[0];
    $japaneseNumeralRange = $japanese->parseText('私は本日午前八時十分から午後11時32分までゲームをした', '2012-08-10')[0];
    $asciiMeridiemRange = $japanese->parseText('6時30分PM-11時PM', '2012-08-10')[0];
    $dateTimeWithSeconds = $japanese->parseText('僕は2018年11月26日午後三時半五十九秒にゲームを始めた', '2012-08-10')[0];
    $impliedMeridiemRange = $japanese->parseText('午後1時30分から3時10分', '2012-08-10')[0];
    $dottedMeridiemRange = $japanese->parseText('1時20分P.M.から3時', '2012-08-10')[0];
    $fullWidthMeridiemRange = $japanese->parseText('午後６時半－１１時', '2012-08-10')[0];
    $overnightMeridiemRange = $japanese->parseText('午後１１時半－１時', '2012-08-10')[0];
    $overnightTwentyFourHourRange = $japanese->parseText('23時20分から2時', '2012-08-10')[0];
    $randomDateRange = $japanese->parseText('2014年3月5日午前 6 時から 7 時', '2012-08-10')[0];
    $randomWeekdayTime = $japanese->parseText('次の土曜日1時30分二十九秒', '2012-08-10')[0];
    $randomCasualTime = $japanese->parseText('昨日午前六時', '2012-08-10')[0];
    $randomMonthTime = $japanese->parseText('６月４日3:00am', '2012-08-10')[0];
    $randomPreviousWeekdayTime = $japanese->parseText('前の金曜日16時', '2012-08-10')[0];
    $randomStandardTime = $japanese->parseText('3月17日 20時15', '2012-08-10')[0];
    $weekdayTime = $japanese->parseText('水曜日 22時', '2012-08-10')[0];

    expect($prefixedMinuteTime->index)->toBe(6)
        ->and($prefixedMinuteTime->text)->toBe('午前6時13分')
        ->and($prefixedMinuteTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($prefixedHourTime->index)->toBe(6)
        ->and($prefixedHourTime->text)->toBe('午前8時')
        ->and($prefixedHourTime->start->date()->toDateTimeString())->toBe('2012-08-10 08:00:00')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($time->start->tags())->toContain('parser/JPTimeExpressionParser')
        ->and($fullWidthDateTime->text)->toBe('１２月９日の１６：３０')
        ->and($fullWidthDateTime->start->date()->toDateTimeString())->toBe('2025-12-09 16:30:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($range->end?->tags())->toContain('parser/JPTimeExpressionParser')
        ->and($japaneseNumeralRange->text)->toBe('本日午前八時十分から午後11時32分')
        ->and($japaneseNumeralRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($japaneseNumeralRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:32:00')
        ->and($asciiMeridiemRange->text)->toBe('6時30分PM-11時PM')
        ->and($asciiMeridiemRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($asciiMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($asciiMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($dateTimeWithSeconds->text)->toBe('2018年11月26日午後三時半五十九秒')
        ->and($dateTimeWithSeconds->start->date()->toDateTimeString())->toBe('2018-11-26 15:30:59')
        ->and($dateTimeWithSeconds->start->isCertain('millisecond'))->toBeFalse()
        ->and($impliedMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:30:00')
        ->and($impliedMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:10:00')
        ->and($impliedMeridiemRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($dottedMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:20:00')
        ->and($dottedMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($fullWidthMeridiemRange->text)->toBe('午後６時半－１１時')
        ->and($fullWidthMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($overnightMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 23:30:00')
        ->and($overnightMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($overnightMeridiemRange->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($overnightTwentyFourHourRange->start->date()->toDateTimeString())->toBe('2012-08-10 23:20:00')
        ->and($overnightTwentyFourHourRange->end?->date()->toDateTimeString())->toBe('2012-08-11 02:00:00')
        ->and($randomDateRange->text)->toBe('2014年3月5日午前 6 時から 7 時')
        ->and($randomWeekdayTime->text)->toBe('次の土曜日1時30分二十九秒')
        ->and($randomCasualTime->text)->toBe('昨日午前六時')
        ->and($randomMonthTime->text)->toBe('６月４日3:00am')
        ->and($randomPreviousWeekdayTime->text)->toBe('前の金曜日16時')
        ->and($randomStandardTime->text)->toBe('3月17日 20時15')
        ->and($weekdayTime->text)->toBe('水曜日 22時')
        ->and($weekdayTime->start->date()->toDateTimeString())->toBe('2012-08-08 22:00:00')
        ->and($weekdayTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($japanese->parseText('10時', '2012-08-10')[0]->text)->toBe('10時')
        ->and($japanese->parseText('12時', '2012-08-10')[0]->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($japanese->parseText('午後１3時', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('25時', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('5時70分', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('5時30分65秒', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('23時-25時', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('3時-5時70分', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('3時-5時30分65秒', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('1', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('12', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('12a', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('1時間', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('25時間', '2012-08-10'))->toBe([]);
});

it('merges japanese dates with weekdays, times, and date ranges', function () {
    $japanese = Chrono::ja();
    $weekday = $japanese->parseText('2014年7月12日（土）', '2012-08-10')[0];
    $dateTime = $japanese->parseText('2014年7月12日の午後3時', '2012-08-10')[0];
    $dateRange = $japanese->parseText('2月11日から2月13日', '2012-08-10')[0];
    $fullWidthDateTimeRange = $japanese->parseText('１月３０日（木）１２：００－１月３１日（金）１６：００', '2025-02-10')[0];

    expect($weekday->text)->toBe('2014年7月12日（土）')
        ->and($weekday->start->isCertain('weekday'))->toBeTrue()
        ->and($weekday->tags())->toContain('refiner/mergeWeekdayComponent')
        ->and($dateTime->text)->toBe('2014年7月12日の午後3時')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2014-07-12 15:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($dateRange->text)->toBe('2月11日から2月13日')
        ->and($dateRange->end?->date()->format('m-d H:i:s'))->toBe('02-13 12:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange')
        ->and($fullWidthDateTimeRange->text)->toBe('１月３０日（木）１２：００－１月３１日（金）１６：００')
        ->and($fullWidthDateTimeRange->start->date()->toDateTimeString())->toBe('2025-01-30 12:00:00')
        ->and($fullWidthDateTimeRange->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($fullWidthDateTimeRange->end?->date()->toDateTimeString())->toBe('2025-01-31 16:00:00')
        ->and($fullWidthDateTimeRange->end?->get('weekday'))->toBe(Weekday::FRIDAY->value);
});

it('parses vietnamese casual date and time references', function () {
    $vietnamese = Chrono::vi();
    $now = $vietnamese->parseText('bây giờ', '2012-08-10 09:30:45.123')[0];
    $nowAlternative = $vietnamese->parseText('lúc này', '2012-08-10 08:09:10.011')[0];
    $today = $vietnamese->parseText('hôm nay', '2012-08-10 09:30')[0];
    $prefixedToday = $vietnamese->parseText('Cuộc họp hôm nay.', '2012-08-10 12:00')[0];
    $prefixedYesterday = $vietnamese->parseText('Hội nghị hôm qua.', '2012-08-10 12:00')[0];
    $tomorrow = $vietnamese->parseText('Lịch ngày mai.', '2012-08-10 12:00')[0];
    $dayBeforeYesterday = $vietnamese->parseText('hôm kia', '2012-08-10 12:00')[0];
    $morning = $vietnamese->parseText('buổi sáng', '2012-08-10 09:30')[0];
    $dateMorning = $vietnamese->parseText('hôm nay buổi sáng', '2012-08-10 06:00')[0];
    $midnight = $vietnamese->parseText('nửa đêm', '2012-08-10 12:00')[0];
    $dawn = $vietnamese->parseText('bình minh', '2012-08-10 12:00')[0];

    expect($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 09:30:45.123')
        ->and($now->start->tags())->toContain('parser/VICasualDateParser')
        ->and($nowAlternative->text)->toBe('lúc này')
        ->and($nowAlternative->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 09:30:00')
        ->and($today->start->isCertain('year'))->toBeTrue()
        ->and($today->start->isCertain('month'))->toBeTrue()
        ->and($today->start->isCertain('day'))->toBeTrue()
        ->and($today->start->isCertain('hour'))->toBeFalse()
        ->and($prefixedToday->index)->toBe(13)
        ->and($prefixedToday->text)->toBe('hôm nay')
        ->and($prefixedToday->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedYesterday->index)->toBe(13)
        ->and($prefixedYesterday->text)->toBe('hôm qua')
        ->and($prefixedYesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($vietnamese->parseDateText('hôm qua', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 09:30:00')
        ->and($vietnamese->parseDateText('hôm qua', '2012-08-01 12:00')?->toDateTimeString())->toBe('2012-07-31 12:00:00')
        ->and($tomorrow->index)->toBe(7)
        ->and($tomorrow->text)->toBe('ngày mai')
        ->and($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($vietnamese->parseDateText('ngày mai', '2012-08-31 12:00')?->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($vietnamese->parseDateText('ngày kia', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-12 09:30:00')
        ->and($dayBeforeYesterday->text)->toBe('hôm kia')
        ->and($dayBeforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($morning->start->tags())->toContain('parser/VICasualTimeParser')
        ->and($dateMorning->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($dateMorning->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($dawn->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00');
});

it('parses vietnamese standard month year and year expressions', function () {
    $vietnamese = Chrono::vi();
    $standard = $vietnamese->parseText('ngày 15 tháng 3 năm 1975', '2012-08-10')[0];
    $prefixedStandard = $vietnamese->parseText('Ngày 30 tháng 4 năm 1975 là ngày giải phóng.', '2012-08-10 12:00')[0];
    $embeddedStandard = $vietnamese->parseText('Hiệp định được ký ngày 27 tháng 1 năm 1973.', '2012-08-10 12:00')[0];
    $noPrefixStandard = $vietnamese->parseText('7 tháng 5 năm 1954 là ngày chấm dứt trận Điện Biên Phủ.', '2012-08-10 12:00')[0];
    $impliedYear = $vietnamese->parseText('ngày 15 tháng 3', '2012-08-10 12:00')[0];
    $positionedStandard = $vietnamese->parseText('Sự kiện ngày 30 tháng 4 năm 1975 quan trọng.', '2012-08-10 12:00')[0];
    $bcStandard = $vietnamese->parseText('ngày 1 tháng 1 năm 300 TCN', '2012-08-10')[0];
    $month = $vietnamese->parseText('tháng chạp năm 1975', '2012-08-10')[0];
    $numberedMonth = $vietnamese->parseText('tháng 4 năm 1975', '2012-08-10')[0];
    $slashMonth = $vietnamese->parseText('tháng 3/1975', '2012-08-10')[0];
    $impliedYearMonth = $vietnamese->parseText('tháng 3', '2012-08-10')[0];
    $year = $vietnamese->parseText('năm 1975', '2012-08-10')[0];
    $embeddedYear = $vietnamese->parseText('Việt Nam thống nhất vào năm 1976.', '2012-08-10')[0];
    $accentedEmbeddedYear = $vietnamese->parseText('Cách mạng năm 1789.', '2012-08-10')[0];
    $bcYear = $vietnamese->parseText('Năm 179 TCN, triều Điệt bị diệt.', '2012-08-10')[0];
    $largeBcYear = $vietnamese->parseText('Văn minh có từ năm 3000 TCN.', '2012-08-10')[0];
    $threeDigitYear = $vietnamese->parseText('năm 938 là năm độc lập.', '2012-08-10')[0];
    $slash = $vietnamese->parseText('Ngày 30/04/1975.', '2012-08-10')[0];
    $embeddedSlash = $vietnamese->parseText('Hội nghị 01/01/1954', '2012-08-10')[0];
    $shortSlash = $vietnamese->parseText('3/5/1968', '2012-08-10')[0];
    $iso = $vietnamese->parseText('Ngày 2024-03-15 là quan trọng.', '2012-08-10')[0];

    expect($standard->start->date()->toDateTimeString())->toBe('1975-03-15 12:00:00')
        ->and($standard->start->tags())->toContain('parser/VIStandardParser')
        ->and($prefixedStandard->text)->toBe('Ngày 30 tháng 4 năm 1975')
        ->and($prefixedStandard->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($embeddedStandard->index)->toBe(28)
        ->and($embeddedStandard->text)->toBe('ngày 27 tháng 1 năm 1973')
        ->and($embeddedStandard->start->date()->toDateTimeString())->toBe('1973-01-27 12:00:00')
        ->and($noPrefixStandard->text)->toBe('7 tháng 5 năm 1954')
        ->and($noPrefixStandard->start->date()->toDateTimeString())->toBe('1954-05-07 12:00:00')
        ->and($impliedYear->start->date()->toDateTimeString())->toBe('2012-03-15 12:00:00')
        ->and($impliedYear->start->isCertain('year'))->toBeFalse()
        ->and($positionedStandard->index)->toBe(12)
        ->and($positionedStandard->text)->toBe('ngày 30 tháng 4 năm 1975')
        ->and($bcStandard->start->get('year'))->toBe(-300)
        ->and($bcStandard->start->date()->format('Y-m-d H:i:s'))->toBe('-0300-01-01 12:00:00')
        ->and($month->start->date()->toDateTimeString())->toBe('1975-12-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/VIMonthYearParser')
        ->and($numberedMonth->text)->toBe('tháng 4 năm 1975')
        ->and($numberedMonth->start->date()->toDateTimeString())->toBe('1975-04-01 12:00:00')
        ->and($numberedMonth->start->isCertain('day'))->toBeFalse()
        ->and($slashMonth->text)->toBe('tháng 3/1975')
        ->and($slashMonth->start->date()->toDateTimeString())->toBe('1975-03-01 12:00:00')
        ->and($impliedYearMonth->start->date()->toDateTimeString())->toBe('2012-03-01 12:00:00')
        ->and($impliedYearMonth->start->isCertain('year'))->toBeFalse()
        ->and($year->start->date()->toDateTimeString())->toBe('1975-01-01 12:00:00')
        ->and($year->start->tags())->toContain('parser/VIYearParser')
        ->and($embeddedYear->text)->toBe('năm 1976')
        ->and($embeddedYear->start->date()->toDateTimeString())->toBe('1976-01-01 12:00:00')
        ->and($accentedEmbeddedYear->start->date()->toDateTimeString())->toBe('1789-01-01 12:00:00')
        ->and($bcYear->text)->toBe('Năm 179 TCN')
        ->and($bcYear->start->get('year'))->toBe(-179)
        ->and($bcYear->start->tags())->toContain('parser/VIYearParser')
        ->and($largeBcYear->text)->toBe('năm 3000 TCN')
        ->and($largeBcYear->start->get('year'))->toBe(-3000)
        ->and($threeDigitYear->text)->toBe('năm 938')
        ->and($threeDigitYear->start->date()->format('Y-m-d H:i:s'))->toBe('0938-01-01 12:00:00')
        ->and($slash->text)->toBe('30/04/1975')
        ->and($slash->index)->toBe(6)
        ->and($slash->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($slash->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($embeddedSlash->index)->toBe(13)
        ->and($embeddedSlash->start->date()->toDateTimeString())->toBe('1954-01-01 12:00:00')
        ->and($shortSlash->start->date()->toDateTimeString())->toBe('1968-05-03 12:00:00')
        ->and($iso->text)->toBe('2024-03-15')
        ->and($iso->start->date()->toDateTimeString())->toBe('2024-03-15 12:00:00')
        ->and($vietnamese->parseText('ngày 1 tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 0', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('Có 1975 người tham gia.', '2012-08-10'))->toBe([]);
});

it('parses vietnamese weekday time and relative expressions', function () {
    $vietnamese = Chrono::vi();
    $weekday = $vietnamese->parseText('thứ tư', '2012-08-10 09:30')[0];
    $prefixedWeekday = $vietnamese->parseText('Hẹn vào thứ hai', '2012-08-09')[0];
    $abbreviatedWeekday = $vietnamese->parseText('Hẹn t2', '2012-08-09')[0];
    $nextWeekday = $vietnamese->parseText('thứ hai tới', '2012-08-09')[0];
    $followingWeekday = $vietnamese->parseText('thứ hai sau', '2012-08-09')[0];
    $previousWeekday = $vietnamese->parseText('thứ hai qua', '2012-08-09')[0];
    $weekdayBeforeConjunction = $vietnamese->parseText('thứ hai sau khi chiến tranh kết thúc', '2012-08-10 12:00')[0];
    $sameWeekday = $vietnamese->parseText('thứ năm', '2012-08-09 12:00', ['forwardDate' => true])[0];
    $nextMonday = $vietnamese->parseText('thứ hai', '2012-08-14 12:00', ['forwardDate' => true])[0];
    $prefixedTime = $vietnamese->parseText('Cuộc họp lúc 7 giờ.', '2012-08-10 12:00')[0];
    $time = $vietnamese->parseText('lúc 7 giờ 30 phút chiều', '2012-08-10')[0];
    $plainTime = $vietnamese->parseText('lúc 7 giờ 30 phút', '2012-08-10 12:00')[0];
    $twentyFourHourTime = $vietnamese->parseText('vào 15 giờ 45 phút', '2012-08-10 12:00')[0];
    $colonTime = $vietnamese->parseText('Hẹn lúc 15:30.', '2012-08-10 12:00')[0];
    $morningTime = $vietnamese->parseText('9 giờ sáng', '2012-08-10 12:00')[0];
    $afternoonTime = $vietnamese->parseText('3 giờ chiều', '2012-08-10 12:00')[0];
    $nightTime = $vietnamese->parseText('10 giờ đêm', '2012-08-10 12:00')[0];
    $middayTime = $vietnamese->parseText('1 giờ trưa', '2012-08-10 12:00')[0];
    $lateMorningTime = $vietnamese->parseText('11 giờ trưa', '2012-08-10 12:00')[0];
    $noonTime = $vietnamese->parseText('12 giờ trưa', '2012-08-10 12:00')[0];
    $midnightTime = $vietnamese->parseText('12 giờ sáng', '2012-08-10 12:00')[0];
    $ago = $vietnamese->parseText('2 ngày trước', '2012-08-10 09:30')[0];
    $later = $vietnamese->parseText('3 tuần sau', '2012-08-10 09:30')[0];
    $within = $vietnamese->parseText('trong 1 tháng', '2012-08-10 09:30')[0];
    $casual = $vietnamese->parseText('tuần trước', '2012-08-10 09:30')[0];
    $bareNextYear = $vietnamese->parseText('năm sau', '2012-08-10 12:00')[0];
    $withinWeeks = $vietnamese->parseText('Hoàn thành trong 2 tuần.', '2012-08-10 12:00')[0];
    $withinMonths = $vietnamese->parseText('trong vòng 3 tháng', '2012-08-10 12:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/VIWeekdayParser')
        ->and($prefixedWeekday->index)->toBe(11)
        ->and($prefixedWeekday->text)->toBe('thứ hai')
        ->and($prefixedWeekday->start->get('weekday'))->toBe(1)
        ->and($abbreviatedWeekday->index)->toBe(6)
        ->and($abbreviatedWeekday->text)->toBe('t2')
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(1)
        ->and($vietnamese->parseText('t7', '2012-08-09')[0]->start->get('weekday'))->toBe(6)
        ->and($vietnamese->parseText('cn', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($nextWeekday->text)->toBe('thứ hai tới')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($nextWeekday->start->isCertain('day'))->toBeFalse()
        ->and($followingWeekday->text)->toBe('thứ hai sau')
        ->and($followingWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($previousWeekday->text)->toBe('thứ hai qua')
        ->and($previousWeekday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($weekdayBeforeConjunction->text)->toBe('thứ hai')
        ->and($weekdayBeforeConjunction->start->get('weekday'))->toBe(1)
        ->and($sameWeekday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($nextMonday->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($prefixedTime->index)->toBe(13)
        ->and($prefixedTime->text)->toBe('lúc 7 giờ')
        ->and($prefixedTime->start->date()->toDateTimeString())->toBe('2012-08-10 07:00:00')
        ->and($prefixedTime->start->isCertain('hour'))->toBeTrue()
        ->and($prefixedTime->start->isCertain('meridiem'))->toBeFalse()
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 19:30:00')
        ->and($time->start->tags())->toContain('parser/VITimeExpressionParser')
        ->and($plainTime->start->date()->toDateTimeString())->toBe('2012-08-10 07:30:00')
        ->and($twentyFourHourTime->start->date()->toDateTimeString())->toBe('2012-08-10 15:45:00')
        ->and($colonTime->text)->toBe('lúc 15:30')
        ->and($colonTime->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($morningTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($morningTime->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($afternoonTime->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($nightTime->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($middayTime->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($middayTime->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($lateMorningTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($lateMorningTime->start->date()->toDateTimeString())->toBe('2012-08-10 11:00:00')
        ->and($noonTime->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($noonTime->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($midnightTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($midnightTime->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/VITimeUnitCasualRelativeFormatParser')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-31 09:30:00')
        ->and($later->start->tags())->toContain('parser/VITimeUnitCasualRelativeFormatParser')
        ->and($within->start->tags())->toContain('parser/VITimeUnitWithinFormatParser')
        ->and($casual->start->date()->toDateTimeString())->toBe('2012-08-03 09:30:00')
        ->and($casual->start->tags())->toContain('parser/VITimeUnitCasualRelativeFormatParser')
        ->and($bareNextYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($withinWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($withinWeeks->start->tags())->toContain('parser/VITimeUnitWithinFormatParser')
        ->and($withinMonths->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00');
});

it('rejects unlikely vietnamese date and time false positives', function () {
    $vietnamese = Chrono::vi();

    expect($vietnamese->parseText('ngày 0 tháng 4 năm 2000', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('1-2', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('1-2-3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('%e7%b7%8a', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('7 giờ 61 phút', '2012-08-10'))->toBe([]);
});

it('merges vietnamese dates with times ranges and weekdays', function () {
    $vietnamese = Chrono::vi();
    $dateTime = $vietnamese->parseText('ngày 15 tháng 3 năm 1975 lúc 7 giờ', '2012-08-10')[0];
    $upstreamDateTime = $vietnamese->parseText('ngày 30 tháng 4 năm 1975 lúc 11 giờ', '2012-08-10')[0];
    $dateRange = $vietnamese->parseText('ngày 15 tháng 3 đến ngày 17 tháng 3', '2012-08-10')[0];
    $prefixedDateRange = $vietnamese->parseText('từ ngày 5 tháng 8 đến ngày 10 tháng 8 năm 2012', '2012-08-10')[0];
    $endYearRange = $vietnamese->parseText('ngày 1 tháng 4 – ngày 30 tháng 4 năm 2000', '2012-08-10')[0];
    $hyphenRange = $vietnamese->parseText('ngày 3 tháng 9 - ngày 5 tháng 9 năm 1945', '2012-08-10')[0];
    $monthRange = $vietnamese->parseText('tháng 3 tới tháng 5 năm 1975', '2012-08-10')[0];
    $yearRange = $vietnamese->parseText('ngày 1 tháng 1 đến ngày 31 tháng 12 năm 2020', '2012-08-10')[0];
    $weekday = $vietnamese->parseText('thứ tư ngày 15 tháng 3', '2012-08-10')[0];

    expect($dateTime->text)->toBe('ngày 15 tháng 3 năm 1975 lúc 7 giờ')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('1975-03-15 07:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($upstreamDateTime->start->date()->toDateTimeString())->toBe('1975-04-30 11:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange')
        ->and($prefixedDateRange->text)->toBe('ngày 5 tháng 8 đến ngày 10 tháng 8 năm 2012')
        ->and($prefixedDateRange->start->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($prefixedDateRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($endYearRange->start->date()->toDateTimeString())->toBe('2000-04-01 12:00:00')
        ->and($endYearRange->end?->date()->toDateTimeString())->toBe('2000-04-30 12:00:00')
        ->and($hyphenRange->start->date()->toDateTimeString())->toBe('1945-09-03 12:00:00')
        ->and($hyphenRange->end?->date()->toDateTimeString())->toBe('1945-09-05 12:00:00')
        ->and($monthRange->tags())->toContain('refiner/mergeDateRange')
        ->and($monthRange->start->date()->toDateTimeString())->toBe('1975-03-01 12:00:00')
        ->and($monthRange->end?->date()->toDateTimeString())->toBe('1975-05-01 12:00:00')
        ->and($yearRange->start->date()->toDateTimeString())->toBe('2020-01-01 12:00:00')
        ->and($yearRange->end?->date()->toDateTimeString())->toBe('2020-12-31 12:00:00')
        ->and($yearRange->start->date()->lt($yearRange->end?->date()))->toBeTrue()
        ->and($weekday->start->isCertain('weekday'))->toBeTrue();
});

it('parses simplified chinese casual dates dates weekdays and deadlines', function () {
    $chinese = Chrono::zhHans();
    $today = $chinese->parseText('我今天要打游戏', '2012-08-10 12:00')[0];
    $tomorrowLateNight = $chinese->parseText('我明天要打游戏', '2012-08-10 01:00')[0];
    $dayAfterTomorrow = $chinese->parseText('我后天凌晨要打游戏', '2012-08-10 00:00')[0];
    $threeDaysAgo = $chinese->parseText('我大前天凌晨要打游戏', '2012-08-10 00:00')[0];
    $lastNight = $chinese->parseText('我昨天晚上要打游戏', '2012-08-10 12:00')[0];
    $casual = $chinese->parseText('明天上午', '2012-08-10 09:30')[0];
    $combined = $chinese->parseText('我今天下午5点要打游戏', '2012-08-10 12:00')[0];
    $casualRange = $chinese->parseText('我今天 - 下周五要打游戏', '2012-08-04 12:00')[0];
    $night = $chinese->parseText('今日夜晚', '2012-01-01 12:00')[0];
    $date = $chinese->parseText('2014年7月12日', '2012-08-10')[0];
    $prefixedDate = $chinese->parseText('我2016年9月3号要打游戏', '2012-08-10')[0];
    $hanDate = $chinese->parseText('我二零一六年，九月三号要打游戏', '2012-08-10')[0];
    $yearlessDate = $chinese->parseText('我九月三号要打游戏', '2014-08-10')[0];
    $dateRange = $chinese->parseText('2016年9月3号-2017年10月24号', '2012-08-10')[0];
    $weekday = $chinese->parseText('下个星期一', '2012-08-10')[0];
    $lastWeekday = $chinese->parseText('我上个礼拜三在打游戏', '2016-09-02')[0];
    $nextSunday = $chinese->parseText('我下星期天打游戏', '2016-09-02')[0];
    $thisMonday = $chinese->parseText('我这个星期一要打游戏', '2012-08-10')[0];
    $weekdayRange = $chinese->parseText('星期六至星期一', '2016-09-02', ['forwardDate' => true])[0];
    $deadline = $chinese->parseText('3天后', '2012-08-10 09:30')[0];
    $daysWithin = $chinese->parseText('五日内我要通关游戏', '2012-08-10')[0];
    $minutesLater = $chinese->parseText('五分钟后', '2012-08-10 12:14')[0];
    $halfHour = $chinese->parseText('半小时之内', '2012-08-10 12:14')[0];
    $monthsWithin = $chinese->parseText('几个月之内答复我', '2012-08-10 12:14')[0];

    expect($today->index)->toBe(3)
        ->and($today->text)->toBe('今天')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($tomorrowLateNight->text)->toBe('明天')
        ->and($tomorrowLateNight->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dayAfterTomorrow->text)->toBe('后天凌晨')
        ->and($dayAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($threeDaysAgo->text)->toBe('大前天凌晨')
        ->and($threeDaysAgo->start->date()->toDateTimeString())->toBe('2012-08-07 00:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00')
        ->and($casual->start->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($casual->start->tags())->toContain('parser/ZHHansCasualDateParser')
        ->and($combined->text)->toBe('今天下午5点')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($casualRange->text)->toBe('今天 - 下周五')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($night->text)->toBe('今日夜晚')
        ->and($night->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($date->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHansDateParser')
        ->and($prefixedDate->text)->toBe('2016年9月3号')
        ->and($prefixedDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($hanDate->text)->toBe('二零一六年，九月三号')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($yearlessDate->text)->toBe('九月三号')
        ->and($yearlessDate->start->date()->toDateTimeString())->toBe('2014-09-03 12:00:00')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ZHHansRelationWeekdayParser')
        ->and($lastWeekday->text)->toBe('上个礼拜三')
        ->and($lastWeekday->start->date()->toDateTimeString())->toBe('2016-08-24 12:00:00')
        ->and($lastWeekday->start->isCertain('day'))->toBeTrue()
        ->and($nextSunday->text)->toBe('下星期天')
        ->and($nextSunday->start->date()->toDateTimeString())->toBe('2016-09-04 12:00:00')
        ->and($thisMonday->text)->toBe('这个星期一')
        ->and($thisMonday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($thisMonday->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->text)->toBe('星期六至星期一')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00')
        ->and($weekdayRange->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('weekday'))->toBeTrue()
        ->and($weekdayRange->end?->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($deadline->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($deadline->start->tags())->toContain('parser/ZHHansDeadlineFormatParser')
        ->and($daysWithin->text)->toBe('五日内')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($minutesLater->text)->toBe('五分钟后')
        ->and($minutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($halfHour->text)->toBe('半小时之内')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($monthsWithin->text)->toBe('几个月之内')
        ->and($monthsWithin->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00');
});

it('parses traditional chinese casual dates dates weekdays and deadlines', function () {
    $chinese = Chrono::zhHant();
    $now = $chinese->parseText('雞而家全部都係雞', '2012-08-10 08:09:10.011')[0];
    $today = $chinese->parseText('雞今日全部都係雞', '2012-08-10 12:00')[0];
    $tomorrowLateNight = $chinese->parseText('雞明天全部都係雞', '2012-08-10 01:00')[0];
    $dayAfterTomorrow = $chinese->parseText('雞後天凌晨全部都係雞', '2012-08-10 00:00')[0];
    $threeDaysAgo = $chinese->parseText('雞大前天凌晨全部都係雞', '2012-08-10 00:00')[0];
    $lastNight = $chinese->parseText('雞昨天晚上全部都係雞', '2012-08-10 12:00')[0];
    $casual = $chinese->parseText('聽日下午', '2012-08-10 09:30')[0];
    $combined = $chinese->parseText('雞今日晏晝5點全部都係雞', '2012-08-10 12:00')[0];
    $casualRange = $chinese->parseText('雞今日 - 下禮拜五全部都係雞', '2012-08-04 12:00')[0];
    $date = $chinese->parseText('二零一四年七月十二日', '2012-08-10')[0];
    $prefixedDate = $chinese->parseText('雞2016年9月3號全部都係雞', '2012-08-10')[0];
    $hanDate = $chinese->parseText('雞二零一六年，九月三號全部都係雞', '2012-08-10')[0];
    $yearlessDate = $chinese->parseText('雞九月三號全部都係雞', '2014-08-10')[0];
    $dateRange = $chinese->parseText('二零一六年九月三號ー2017年10月24號', '2012-08-10')[0];
    $weekday = $chinese->parseText('下個星期一', '2012-08-10')[0];
    $lastWeekday = $chinese->parseText('雞上個禮拜三全部都係雞', '2016-09-02')[0];
    $thisMonday = $chinese->parseText('我這個星期一要打遊戲', '2012-08-10')[0];
    $weekdayRange = $chinese->parseText('星期六-星期一', '2016-09-02', ['forwardDate' => true])[0];
    $deadline = $chinese->parseText('三天後', '2012-08-10 09:30')[0];
    $halfHour = $chinese->parseText('半小時之內', '2012-08-10 12:14')[0];
    $monthsWithin = $chinese->parseText('幾個月之內答覆我', '2012-08-10 12:14')[0];

    expect($now->index)->toBe(3)
        ->and($now->text)->toBe('而家')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($today->text)->toBe('今日')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($tomorrowLateNight->text)->toBe('明天')
        ->and($tomorrowLateNight->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dayAfterTomorrow->text)->toBe('後天凌晨')
        ->and($dayAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($threeDaysAgo->text)->toBe('大前天凌晨')
        ->and($threeDaysAgo->start->date()->toDateTimeString())->toBe('2012-08-07 00:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00')
        ->and($casual->start->date()->toDateTimeString())->toBe('2012-08-11 15:00:00')
        ->and($casual->start->tags())->toContain('parser/ZHHantCasualDateParser')
        ->and($combined->text)->toBe('今日晏晝5點')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($casualRange->text)->toBe('今日 - 下禮拜五')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHantDateParser')
        ->and($prefixedDate->text)->toBe('2016年9月3號')
        ->and($prefixedDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($hanDate->text)->toBe('二零一六年，九月三號')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($yearlessDate->text)->toBe('九月三號')
        ->and($yearlessDate->start->date()->toDateTimeString())->toBe('2014-09-03 12:00:00')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ZHHantRelationWeekdayParser')
        ->and($lastWeekday->text)->toBe('上個禮拜三')
        ->and($lastWeekday->start->date()->toDateTimeString())->toBe('2016-08-24 12:00:00')
        ->and($thisMonday->text)->toBe('這個星期一')
        ->and($thisMonday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($weekdayRange->text)->toBe('星期六-星期一')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00')
        ->and($weekdayRange->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('weekday'))->toBeTrue()
        ->and($weekdayRange->end?->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($deadline->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($deadline->start->tags())->toContain('parser/ZHHantDeadlineFormatParser')
        ->and($halfHour->text)->toBe('半小時之內')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($monthsWithin->text)->toBe('幾個月之內')
        ->and($monthsWithin->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00');
});

it('parses chinese time expressions and merges date ranges', function () {
    $chinese = Chrono::zh();
    $iso = $chinese->parseText('1994-11-05T08:15:30-05:30', '2012-08-08')[0];
    $simplifiedDateTime = $chinese->parseText('明天早上8点', '2012-08-08 12:00')[0];
    $traditionalDateTime = $chinese->parseText('明天早上8點', '2012-08-08 12:00')[0];
    $time = $chinese->parseText('下午3点半到5点', '2012-08-10')[0];
    $dateTime = $chinese->parseText('2014年7月12日下午3点', '2012-08-10')[0];
    $range = $chinese->parseText('7月12日到7月14日', '2012-08-10')[0];
    $endYearRange = $chinese->parseText('7月12日到2014年7月14日', '2012-08-10')[0];
    $explicitEndDayRange = Chrono::zhHans()->parseText('今晚10点 - 明天早上6点', '2012-08-10')[0];
    $explicitEndDayRangeWithShortDay = Chrono::zhHans()->parseText('今晚10点 - 明早6点', '2012-08-10 12:00')[0];
    $multiDayTimeRange = Chrono::zhHans()->parseText('今天早上9点 - 后天凌晨3点', '2012-08-10')[0];
    $hantCantoneseRange = Chrono::zhHant()->parseText('聽晚10點到聽晚11點', '2012-08-10 12:00')[0];
    $hantYesterdayMorning = Chrono::zhHant()->parseText('尋日朝早六點正', '2012-08-10')[0];

    expect($iso->text)->toBe('1994-11-05T08:15:30-05:30')
        ->and($iso->start->timezoneOffset())->toBe(-330)
        ->and($iso->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 -05:30')
        ->and($simplifiedDateTime->text)->toBe('明天早上8点')
        ->and($simplifiedDateTime->start->date()->toDateTimeString())->toBe('2012-08-09 08:00:00')
        ->and($traditionalDateTime->text)->toBe('明天早上8點')
        ->and($traditionalDateTime->start->date()->toDateTimeString())->toBe('2012-08-09 08:00:00')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($time->start->tags())->toContain('parser/ZHHansTimeExpressionParser')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2014-07-12 15:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($range->end?->date()->format('m-d H:i:s'))->toBe('07-14 12:00:00')
        ->and($endYearRange->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($endYearRange->end?->date()->toDateTimeString())->toBe('2014-07-14 12:00:00')
        ->and($explicitEndDayRange->text)->toBe('今晚10点 - 明天早上6点')
        ->and($explicitEndDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($explicitEndDayRange->end?->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($explicitEndDayRangeWithShortDay->text)->toBe('今晚10点 - 明早6点')
        ->and($explicitEndDayRangeWithShortDay->end?->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($multiDayTimeRange->text)->toBe('今天早上9点 - 后天凌晨3点')
        ->and($multiDayTimeRange->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($multiDayTimeRange->end?->date()->toDateTimeString())->toBe('2012-08-12 03:00:00')
        ->and($hantCantoneseRange->text)->toBe('聽晚10點到聽晚11點')
        ->and($hantCantoneseRange->start->date()->toDateTimeString())->toBe('2012-08-11 22:00:00')
        ->and($hantCantoneseRange->end?->date()->toDateTimeString())->toBe('2012-08-11 23:00:00')
        ->and($hantYesterdayMorning->text)->toBe('尋日朝早六點正')
        ->and($hantYesterdayMorning->start->date()->toDateTimeString())->toBe('2012-08-09 06:00:00')
        ->and($range->tags())->toContain('refiner/mergeDateRange');
});

it('parses dutch weekday references', function () {
    $dutch = Chrono::nl();

    $weekday = $dutch->parseText('Afspraak op woensdag', '2012-08-10')[0];
    $monday = $dutch->parseText('maandag', '2012-08-09')[0];
    $forwardMonday = $dutch->parseText('maandag', '2012-08-09', ['forwardDate' => true])[0];
    $thursday = $dutch->parseText('donderdag', '2012-08-09')[0];
    $sunday = $dutch->parseText('zondag', '2012-08-09')[0];
    $lastFriday = $dutch->parseText('De deadline is vorige vrijdag...', '2012-08-09')[0];
    $lastFridayFromSunday = $dutch->parseText('De deadline is vorige vrijdag...', '2012-08-12')[0];
    $nextFriday = $dutch->parseText('Laten we een meeting hebben op volgende week vrijdag', '2015-04-16')[0];
    $nextTuesday = $dutch->parseText('Ik plan een vrije dag op volgende week dinsdag', '2015-04-18')[0];
    $weekdayTime = $dutch->parseText('Laten we op dinsdag ochtend afspreken', '2015-04-18')[0];
    $monthOverlap = $dutch->parseText('zondag, 7 december 2014', '2012-08-09')[0];
    $slashOverlap = $dutch->parseText('zondag 7/12/2014', '2012-08-09')[0];
    $forwardRange = $dutch->parseText('deze vrijdag tot deze maandag', '2016-08-04', ['forwardDate' => true])[0];

    expect($weekday->text)
        ->toBe('op woensdag')
        ->and($weekday->start->tags())->toContain('parser/NLWeekdayParser')
        ->and($dutch->parseDateText('Afspraak op woensdag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-08 12:00:00')
        ->and($dutch->parseDateText('Afspraak volgende maandag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($dutch->parseDateText('Afspraak vorige maandag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-06 12:00:00')
        ->and($dutch->parseDateText('Afspraak deze vrijdag', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($dutch->parseDateText('Afspraak op zo.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and($monday->index)->toBe(0)
        ->and($monday->text)->toBe('maandag')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($forwardMonday->start->get('day'))->toBe(13)
        ->and($forwardMonday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($thursday->start->get('day'))->toBe(9)
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($sunday->start->get('day'))->toBe(12)
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($lastFriday->index)->toBe(15)
        ->and($lastFriday->text)->toBe('vorige vrijdag')
        ->and($lastFriday->start->get('day'))->toBe(3)
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($lastFriday->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($lastFridayFromSunday->start->get('day'))->toBe(10)
        ->and($lastFridayFromSunday->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($nextFriday->index)->toBe(28)
        ->and($nextFriday->text)->toBe('op volgende week vrijdag')
        ->and($nextFriday->start->date()->toDateTimeString())->toBe('2015-04-24 12:00:00')
        ->and($nextTuesday->index)->toBe(22)
        ->and($nextTuesday->text)->toBe('op volgende week dinsdag')
        ->and($nextTuesday->start->date()->toDateTimeString())->toBe('2015-04-21 12:00:00')
        ->and($weekdayTime->index)->toBe(9)
        ->and($weekdayTime->text)->toBe('op dinsdag ochtend')
        ->and($weekdayTime->start->date()->toDateTimeString())->toBe('2015-04-21 06:00:00')
        ->and($weekdayTime->start->get('weekday'))->toBe(2)
        ->and($monthOverlap->text)->toBe('zondag, 7 december 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($slashOverlap->text)->toBe('zondag 7/12/2014')
        ->and($slashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($slashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($forwardRange->text)->toBe('deze vrijdag tot deze maandag')
        ->and($forwardRange->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($forwardRange->start->get('weekday'))->toBe(5)
        ->and($forwardRange->start->isCertain('day'))->toBeFalse()
        ->and($forwardRange->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($forwardRange->end?->get('weekday'))->toBe(1)
        ->and($forwardRange->end?->isCertain('day'))->toBeFalse();
});

it('parses dutch numeric time expressions and ranges', function () {
    $dutch = Chrono::nl();
    $offset = $dutch->parseText('  11:00 ', '2016-10-01 08:00')[0];
    $second = $dutch->parseText('20:32:13', '2016-10-01 08:00')[0];
    $secondRange = $dutch->parseText('10:00:00 - 21:45:00', '2016-10-01 08:00')[0];
    $milliseconds = $dutch->parseText('20:32:13.123', '2016-10-01 08:00')[0];
    $range = $dutch->parseText('Afspraak om 6:30 - 8:45 uur', '2012-08-10')[0];
    $overnight = $dutch->parseText('Dienst om 23:30 - 1:15', '2012-08-10')[0];

    expect($offset->index)->toBe(2)
        ->and($offset->text)->toBe('11:00')
        ->and($offset->start->tags())->toContain('parser/NLTimeExpressionParser')
        ->and($second->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($secondRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2016-10-01 20:32:13.123')
        ->and($dutch->parseText('Afspraak om 6 uur', '2012-08-10')[0]->text)
        ->toBe('om 6 uur')
        ->and($dutch->parseDateText('Afspraak om 6 uur', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($dutch->parseDateText('Afspraak om 6:30 p.m.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:30:00')
        ->and($dutch->parseDateText('Afspraak om 1234 am', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 00:34:00')
        ->and($range->text)->toBe('om 6:30 - 8:45 uur')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2012-08-11 01:15:00')
        ->and($dutch->parseText('Gepubliceerd 2020', '2012-08-10'))
        ->toBe([]);
});

it('parses dutch time unit relative expressions', function () {
    $dutch = Chrono::nl();
    $strictDutch = Chrono::strictDutch();
    $withinDays = $dutch->parseText('we have to make something binnen 5 dagen.', '2012-08-10')[0];
    $withinMinutes = $dutch->parseText('binnen 2 minuten', '2016-10-01 14:52')[0];
    $withinHours = $dutch->parseText('binnen 2 uur', '2016-10-01 14:52')[0];
    $withinMonths = $dutch->parseText('binnen de 12 maand', '2016-10-01 14:52')[0];
    $withinThreeDays = $dutch->parseText('binnen de 3 dagen', '2016-10-01 14:52')[0];
    $halfHourAgo = $dutch->parseText('   half uur geleden', '2012-08-10 12:14')[0];
    $decimalHour = $dutch->parseText('over 1,5 uur', '2012-08-10 12:40')[0];
    $minusCompact = $dutch->parseText('-2u5min', '2016-10-01 12:00')[0];

    expect($dutch->parseText('Afspraak in 2 dagen', '2012-08-10 09:30')[0]->text)
        ->toBe('in 2 dagen')
        ->and($dutch->parseDateText('Afspraak in 2 dagen', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00')
        ->and($dutch->parseDateText('Afspraak binnen de 3 uur', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 12:30:00')
        ->and($dutch->parseDateText('Afspraak twee dagen geleden', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-08 09:30:00')
        ->and($dutch->parseDateText('Afspraak 4 uur later', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 13:30:00')
        ->and($dutch->parseDateText('Afspraak over 2 weken', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-24 09:30:00')
        ->and($dutch->parseDateText('Afspraak afgelopen 1 week', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-03 09:30:00')
        ->and($strictDutch->parseDateText('15 minuten vanaf nu', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:29:00')
        ->and($strictDutch->parseDateText('25 minuten later', '2012-08-10 12:40')?->toDateTimeString())
        ->toBe('2012-08-10 13:05:00')
        ->and(Chrono::parse('15 uur 29 min', '2012-08-10 12:14'))
        ->toBe([])
        ->and(Chrono::parse('een paar uur', '2012-08-10 12:14'))
        ->toBe([])
        ->and(Chrono::parse('5 dagen', '2012-08-10 12:14'))
        ->toBe([])
        ->and($withinDays->index)->toBe(26)
        ->and($withinDays->text)->toBe('binnen 5 dagen')
        ->and($withinDays->start->get('year'))->toBe(2012)
        ->and($withinDays->start->get('month'))->toBe(8)
        ->and($withinDays->start->get('day'))->toBe(15)
        ->and($withinMinutes->start->date()->toDateTimeString())->toBe('2016-10-01 14:54:00')
        ->and($withinMinutes->start->isCertain('year'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('month'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('day'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('hour'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('minute'))->toBeTrue()
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2016-10-01 16:52:00')
        ->and($withinHours->start->isCertain('year'))->toBeTrue()
        ->and($withinHours->start->isCertain('month'))->toBeTrue()
        ->and($withinHours->start->isCertain('day'))->toBeTrue()
        ->and($withinHours->start->isCertain('hour'))->toBeTrue()
        ->and($withinHours->start->isCertain('minute'))->toBeTrue()
        ->and($withinMonths->start->date()->toDateTimeString())->toBe('2017-10-01 14:52:00')
        ->and($withinMonths->start->isCertain('year'))->toBeTrue()
        ->and($withinMonths->start->isCertain('month'))->toBeTrue()
        ->and($withinMonths->start->isCertain('day'))->toBeFalse()
        ->and($withinMonths->start->isCertain('hour'))->toBeFalse()
        ->and($withinMonths->start->isCertain('minute'))->toBeFalse()
        ->and($withinThreeDays->start->date()->toDateTimeString())->toBe('2016-10-04 14:52:00')
        ->and($withinThreeDays->start->isCertain('year'))->toBeTrue()
        ->and($withinThreeDays->start->isCertain('month'))->toBeTrue()
        ->and($withinThreeDays->start->isCertain('day'))->toBeTrue()
        ->and($withinThreeDays->start->isCertain('hour'))->toBeFalse()
        ->and($withinThreeDays->start->isCertain('minute'))->toBeFalse()
        ->and($halfHourAgo->index)->toBe(3)
        ->and($halfHourAgo->text)->toBe('half uur geleden')
        ->and($halfHourAgo->start->get('hour'))->toBe(11)
        ->and($halfHourAgo->start->get('minute'))->toBe(44)
        ->and($halfHourAgo->start->date()->toDateTimeString())->toBe('2012-08-10 11:44:00')
        ->and($decimalHour->text)->toBe('over 1,5 uur')
        ->and($decimalHour->start->get('hour'))->toBe(14)
        ->and($decimalHour->start->get('minute'))->toBe(10)
        ->and($decimalHour->start->date()->toDateTimeString())->toBe('2012-08-10 14:10:00')
        ->and($minusCompact->text)->toBe('-2u5min')
        ->and($minusCompact->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00');
});

it('parses dutch relative date period expressions', function () {
    $dutch = Chrono::nl();

    expect($dutch->parseDateText('deze week', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-05 09:30:00')
        ->and($dutch->parseDateText('deze maand', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-01 09:30:00')
        ->and($dutch->parseDateText('dit jaar', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-01-01 09:30:00')
        ->and($dutch->parseDateText('volgende week', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-17 09:30:00')
        ->and($dutch->parseDateText('vorige maand', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-07-10 09:30:00');
});

it('merges dutch dates with times and date ranges', function () {
    $dutch = Chrono::nl();
    $dateTime = $dutch->parseText('Afspraak 10 augustus 2012 om 6:30', '2012-08-10')[0];
    $timeRange = $dutch->parseText('Afspraak 10 augustus 2012 om 6:30 - 8:45', '2012-08-10')[0];
    $dateRange = $dutch->parseText('Evenement 10 augustus 2012 tot 12 augustus 2012', '2012-08-10')[0];
    $dashRange = $dutch->parseText('Evenement woensdag - vrijdag', '2012-08-10')[0];
    $casualRange = $dutch->parseText('vandaag tot morgennamiddag', '2012-08-04 12:00')[0];

    expect($dateTime->text)->toBe('10 augustus 2012 om 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->start->isCertain('hour'))->toBeTrue()
        ->and($timeRange->text)->toBe('10 augustus 2012 om 6:30 - 8:45')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dateRange->text)->toBe('10 augustus 2012 tot 12 augustus 2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dashRange->text)->toBe('woensdag - vrijdag')
        ->and($dashRange->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($dashRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($casualRange->text)->toBe('vandaag tot morgennamiddag')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-05 15:00:00');
});

it('parses italian casual year month day dates', function () {
    $italian = Chrono::it();

    expect($italian->parseText('Pubblicato il 2026/06/23', '2012-08-10')[0]->text)
        ->toBe('2026/06/23')
        ->and($italian->parseText('Pubblicato il 2026/06/23', '2012-08-10')[0]->start->tags())->toContain('parser/ITCasualYearMonthDayParser')
        ->and($italian->parseDateText('Pubblicato il 2026/06/23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($italian->parseDateText('Pubblicato il 2026 giugno 23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($italian->parseDateText('Pubblicato il 2026 giu 23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($italian->parseText('Pubblicato il 2026/13/23', '2012-08-10'))
        ->toBe([]);
});

it('parses italian slash month year expressions', function () {
    $italian = Chrono::it();
    $strictItalian = Chrono::strictItalian();
    $slashDate = $strictItalian->parseText('Pubblicato il 10/08/2012', '2012-08-10')[0];

    expect($italian->parseText('Contratto valido da 06/2005', '2012-08-10')[0]->text)
        ->toBe('06/2005')
        ->and($italian->parseText('Contratto valido da 06/2005', '2012-08-10')[0]->start->tags())->toContain('parser/ITSlashMonthFormatParser')
        ->and($italian->parseDateText('Contratto valido da 06/2005', '2012-08-10')?->toDateTimeString())
        ->toBe('2005-06-01 12:00:00')
        ->and($italian->parseText('Contratto valido da 13/2005', '2012-08-10'))
        ->toBe([])
        ->and($slashDate->text)->toBe('10/08/2012')
        ->and($slashDate->start->date()->toDateTimeString())->toBe('2012-10-08 12:00:00')
        ->and($slashDate->start->tags())->toContain('parser/SlashDateFormatParser');
});

it('parses italian month name expressions', function () {
    $italian = Chrono::it();
    $prefixed = $italian->parseText('Ci vediamo ad Agosto 2017.', '2012-08-10')[0];

    expect($italian->parseText('Partiremo in giugno', '2012-08-10')[0]->text)
        ->toBe('giugno')
        ->and($italian->parseText('Partiremo in giugno', '2012-08-10')[0]->start->tags())->toContain('parser/ITMonthNameParser')
        ->and($italian->parseDateText('Partiremo in giugno', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-06-01 12:00:00')
        ->and($prefixed->index)->toBe(14)
        ->and($prefixed->text)->toBe('Agosto 2017')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2017-08-01 12:00:00')
        ->and($italian->parseText('Partiremo giugno 2026', '2012-08-10')[0]->text)
        ->toBe('giugno 2026')
        ->and($italian->parseDateText('Partiremo giugno 2026', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-01 12:00:00')
        ->and($italian->parseDateText('Partiremo giu 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-06-01 12:00:00')
        ->and($italian->parseText('Partiremo giu', '2012-08-10'))
        ->toBe([]);
});

it('parses italian little endian month name dates and ranges', function () {
    $italian = Chrono::it();
    $range = $italian->parseText('Evento dal 10 al 12 agosto 2012', '2012-08-10')[0];
    $explicitYear = $italian->parseText('10 Agosto 2012', '2012-08-10')[0];
    $prefixed = $italian->parseText('La scadenza è il 10 Agosto', '2012-08-10')[0];

    expect($italian->parseText('Evento il 10 agosto', '2012-08-10')[0]->text)
        ->toBe('10 agosto')
        ->and($italian->parseText('Evento il 10 agosto', '2012-08-10')[0]->start->tags())->toContain('parser/ITMonthNameLittleEndianParser')
        ->and($italian->parseDateText('Evento il 10 agosto', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($explicitYear->index)->toBe(0)
        ->and($explicitYear->text)->toBe('10 Agosto 2012')
        ->and($explicitYear->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixed->index)->toBe(18)
        ->and($prefixed->text)->toBe('10 Agosto')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($italian->parseDateText('Evento il decimo agosto', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($italian->parseDateText('Evento il 10 agosto 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-08-10 12:00:00')
        ->and($range->text)->toBe('10 al 12 agosto 2012')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});

it('parses italian middle endian month name dates and ranges', function () {
    $italian = Chrono::it();
    $range = $italian->parseText('Evento agosto 10-12 2012', '2012-08-10')[0];
    $explicitYear = $italian->parseText('Agosto 10, 2012', '2012-08-10')[0];
    $prefixed = $italian->parseText('La scadenza è Agosto 10', '2012-08-10')[0];

    expect($italian->parseText('Evento agosto 10', '2012-08-10')[0]->text)
        ->toBe('agosto 10')
        ->and($italian->parseText('Evento agosto 10', '2012-08-10')[0]->start->tags())->toContain('parser/ITMonthNameMiddleEndianParser')
        ->and($italian->parseDateText('Evento agosto 10', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($explicitYear->index)->toBe(0)
        ->and($explicitYear->text)->toBe('Agosto 10, 2012')
        ->and($explicitYear->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixed->text)->toBe('Agosto 10')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($italian->parseDateText('Evento agosto decimo', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($italian->parseDateText('Evento agosto 10 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-08-10 12:00:00')
        ->and($range->text)->toBe('agosto 10-12 2012')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($italian->parseText('Evento agosto 12:00', '2012-08-10')[0]->text)
        ->toBe('12:00');
});

it('parses italian weekdays', function () {
    $italian = Chrono::it();
    $merged = $italian->parseText('lunedì, 10 agosto 2012', '2012-08-10')[0];

    expect($italian->parseText('Ci vediamo lunedì', '2012-08-10')[0]->text)
        ->toBe('lunedì')
        ->and($italian->parseText('Ci vediamo lunedì', '2012-08-10')[0]->start->tags())->toContain('parser/ITWeekdayParser')
        ->and($italian->parseDateText('Ci vediamo lunedì', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($italian->parseDateText('Ci vediamo prossimo lunedì', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($italian->parseDateText('Ci vediamo scorsa domenica', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 12:00:00')
        ->and($italian->parseDateText('Ci vediamo lunedì questa settimana', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($merged->text)->toBe('lunedì, 10 agosto 2012')
        ->and($merged->start->isCertain('weekday'))->toBeTrue()
        ->and($merged->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('parses italian casual times', function () {
    $italian = Chrono::it();

    expect($italian->parseDateText('Ci vediamo questa mattina', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($italian->parseText('Ci vediamo questa mattina', '2012-08-10 09:30')[0]->start->tags())->toContain('parser/ITCasualTimeParser')
        ->and($italian->parseDateText('Ci vediamo pomeriggio', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($italian->parseDateText('Ci vediamo sera', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($italian->parseDateText('Ci vediamo mezzogiorno', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($italian->parseDateText('Ci vediamo mezzanotte', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00');
});

it('parses italian time expressions and ranges', function () {
    $italian = Chrono::it();
    $eighteenTen = $italian->parseText('Alle 18:10', '2012-08-10')[0];
    $sixTen = $italian->parseText('Alle 6:10', '2012-08-10')[0];
    $afternoon = $italian->parseText('6 del pomeriggio', '2012-08-10')[0];
    $tonight = $italian->parseText('Stasera', '2012-08-10')[0];
    $plainRange = $italian->parseText('10:00 - 12:00', '2012-08-10')[0];
    $range = $italian->parseText('dalle 6:30 - 8:45', '2012-08-10')[0];
    $milliseconds = $italian->parseText('8:10:30.123', '2012-08-10')[0];

    expect($italian->parseText('Ci vediamo alle 6:13', '2012-08-10')[0]->text)
        ->toBe('alle 6:13')
        ->and($italian->parseText('Ci vediamo alle 6:13', '2012-08-10')[0]->start->tags())->toContain('parser/ITTimeExpressionParser')
        ->and($italian->parseDateText('Ci vediamo alle 6:13', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:13:00')
        ->and($eighteenTen->index)->toBe(0)
        ->and($eighteenTen->text)->toBe('Alle 18:10')
        ->and($eighteenTen->start->date()->toDateTimeString())->toBe('2012-08-10 18:10:00')
        ->and($sixTen->text)->toBe('Alle 6:10')
        ->and($sixTen->start->date()->toDateTimeString())->toBe('2012-08-10 06:10:00')
        ->and($afternoon->text)->toBe('6 del pomeriggio')
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($afternoon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonight->text)->toBe('Stasera')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($plainRange->text)->toBe('10:00 - 12:00')
        ->and($plainRange->start->date()->toDateTimeString())->toBe('2012-08-10 10:00:00')
        ->and($plainRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($italian->parseText('ore 6 in punto', '2012-08-10')[0]->text)
        ->toBe('6 in punto')
        ->and($italian->parseDateText('6 della mattina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($italian->parseDateText('Ci vediamo alle 6 di sera', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($italian->parseText('Alle 20 sera', '2012-08-10')[0]->text)
        ->toBe('Alle 20 sera')
        ->and($italian->parseDateText('Alle 20 sera', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($italian->parseDateText('Ci vediamo alle 6 di pomeriggio', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($range->text)->toBe('dalle 6:30 - 8:45')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($italian->parseText('Ho 123 cose da fare', '2012-08-10'))
        ->toBe([]);
});

it('parses italian time unit relative expressions', function () {
    $italian = Chrono::it();

    expect($italian->parseText('Ci vediamo in 2 giorni', '2012-08-10 09:30')[0]->text)
        ->toBe('in 2 giorni')
        ->and($italian->parseDateText('Ci vediamo in 2 giorni', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00')
        ->and($italian->parseDateText('Ci siamo visti 3 giorni fa', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-07 09:30:00')
        ->and($italian->parseDateText('Ci vediamo 4 ore dopo', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 13:30:00')
        ->and($italian->parseDateText('Ci vediamo prossima 1 settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-17 09:30:00')
        ->and($italian->parseDateText('Ci siamo visti ultima 1 settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-03 09:30:00')
        ->and($italian->parseDateText('Ci vediamo 2 giorni', '2012-08-10 09:30', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00');
});

it('merges italian dates with times and date ranges', function () {
    $italian = Chrono::it();
    $dateTime = $italian->parseText('Ci vediamo 10 agosto 2012 alle 6:30', '2012-08-10')[0];
    $timeRange = $italian->parseText('Ci vediamo 10 agosto 2012 dalle 6:30 - 8:45', '2012-08-10')[0];
    $dateRange = $italian->parseText('Evento 10 agosto 2012 - 12 agosto 2012', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 agosto 2012 alle 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->start->isCertain('hour'))->toBeTrue()
        ->and($timeRange->text)->toBe('10 agosto 2012 dalle 6:30 - 8:45')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dateRange->text)->toBe('10 agosto 2012 - 12 agosto 2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});

it('parses and merges italian relative date references', function () {
    $italian = Chrono::it();
    $before = $italian->parseText('2 giorni prima 10 agosto 2012', '2012-08-01')[0];
    $after = $italian->parseText('2 giorni dopo 10 agosto 2012', '2012-08-01')[0];

    expect($italian->parseDateText('questa settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-05 09:30:00')
        ->and($italian->parseDateText('questo mese', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-01 09:30:00')
        ->and($italian->parseDateText('questo anno', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-01-01 09:30:00')
        ->and($italian->parseDateText('prossimo settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-17 09:30:00')
        ->and($before->text)->toBe('2 giorni prima 10 agosto 2012')
        ->and($before->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($after->text)->toBe('2 giorni dopo 10 agosto 2012')
        ->and($after->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});

it('parses spanish time expressions', function () {
    $spanish = Chrono::es();
    $single = $spanish->parseText('Estaremos a las 6.13 AM', '2012-08-10 00:00')[0];
    $range = $spanish->parseText(' de 6:30pm a 11:00pm ', '2012-08-10 00:00')[0];
    $alRange = $spanish->parseText('del 6:30pm al 11:00pm', '2012-08-10 00:00')[0];
    $implied = $spanish->parseText('de 1pm a 3', '2012-08-10 00:00')[0];
    $dotRange = $spanish->parseText('8:10 - 12.32', '2012-08-10 00:00')[0];
    $milliseconds = $spanish->parseText('8:10:30.123', '2012-08-10 00:00')[0];

    expect($single->text)->toBe('las 6.13 AM')
        ->and($single->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($single->start->tags())->toContain('parser/ESTimeExpressionParser')
        ->and($range->text)->toBe('de 6:30pm a 11:00pm')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($alRange->text)->toBe('del 6:30pm al 11:00pm')
        ->and($alRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($alRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($implied->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($implied->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($dotRange->text)->toBe('8:10 - 12.32')
        ->and($dotRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($spanish->parseText('6pm', '2012-08-10 00:00')[0]->text)->toBe('6pm')
        ->and($spanish->parseDateText('6pm', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($spanish->parseText('6 pm', '2012-08-10 00:00')[0]->text)->toBe('6 pm')
        ->and($spanish->parseDateText('7-10pm', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 19:00:00')
        ->and($spanish->parseText('7-10pm', '2012-08-10 00:00')[0]->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($spanish->parseText('11.1pm', '2012-08-10 00:00')[0]->text)->toBe('11.1pm')
        ->and($spanish->parseDateText('11.1pm', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 23:01:00')
        ->and($spanish->parseDateText('Algo pasó el 10 de Agosto de 2012 10:12:59 pm', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 22:12:59');
});

it('parses spanish schedule-style slash date times', function () {
    $spanish = Chrono::es();
    $range = $spanish->parseText('lunes 4/29/2013 630-930am', '2012-08-10')[0];
    $single = $spanish->parseText('martes 5/1/2013 1115am', '2012-08-10')[0];
    $pm = $spanish->parseText('miércoles 5/3/2013 1230pm', '2012-08-10')[0];
    $sunday = $spanish->parseText('domingo 5/6/2013  750am-910am', '2012-08-10')[0];
    $laterMonday = $spanish->parseText('lunes 5/13/2013 630-930am', '2012-08-10')[0];
    $laterWednesday = $spanish->parseText('miércoles 5/15/2013 1030am', '2012-08-10')[0];
    $colon = $spanish->parseText('jueves 6/21/2013 2:30', '2012-08-10')[0];
    $spaced = $spanish->parseText('martes 7/2/2013 1-230 pm', '2012-08-10')[0];
    $commaRange = $spanish->parseText('Lunes, 6/24/2013, 7:00pm - 8:30pm', '2012-08-10')[0];
    $monthName = $spanish->parseText('Miércoles, 3 Julio de 2013 a las 2pm', '2012-08-10')[0];

    expect($range->text)->toBe('lunes 4/29/2013 630-930am')
        ->and($range->start->date()->toDateTimeString())->toBe('2013-04-29 06:30:00')
        ->and($range->start->tags())->toContain('parser/ESScheduleDateTimeParser')
        ->and($range->end?->date()->toDateTimeString())->toBe('2013-04-29 09:30:00')
        ->and($range->end?->tags())->toContain('parser/ESScheduleDateTimeParser')
        ->and($range->start->isCertain('weekday'))->toBeTrue()
        ->and($single->text)->toBe('martes 5/1/2013 1115am')
        ->and($single->start->date()->toDateTimeString())->toBe('2013-05-01 11:15:00')
        ->and($pm->text)->toBe('miércoles 5/3/2013 1230pm')
        ->and($pm->start->date()->toDateTimeString())->toBe('2013-05-03 12:30:00')
        ->and($sunday->text)->toBe('domingo 5/6/2013  750am-910am')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2013-05-06 07:50:00')
        ->and($sunday->end?->date()->toDateTimeString())->toBe('2013-05-06 09:10:00')
        ->and($laterMonday->text)->toBe('lunes 5/13/2013 630-930am')
        ->and($laterMonday->start->date()->toDateTimeString())->toBe('2013-05-13 06:30:00')
        ->and($laterMonday->end?->date()->toDateTimeString())->toBe('2013-05-13 09:30:00')
        ->and($laterWednesday->text)->toBe('miércoles 5/15/2013 1030am')
        ->and($laterWednesday->start->date()->toDateTimeString())->toBe('2013-05-15 10:30:00')
        ->and($colon->text)->toBe('jueves 6/21/2013 2:30')
        ->and($colon->start->date()->toDateTimeString())->toBe('2013-06-21 02:30:00')
        ->and($spaced->text)->toBe('martes 7/2/2013 1-230 pm')
        ->and($spaced->start->date()->toDateTimeString())->toBe('2013-07-02 13:00:00')
        ->and($spaced->end?->date()->toDateTimeString())->toBe('2013-07-02 14:30:00')
        ->and($commaRange->text)->toBe('Lunes, 6/24/2013, 7:00pm - 8:30pm')
        ->and($commaRange->start->date()->toDateTimeString())->toBe('2013-06-24 19:00:00')
        ->and($commaRange->end?->date()->toDateTimeString())->toBe('2013-06-24 20:30:00')
        ->and($monthName->text)->toBe('Miércoles, 3 Julio de 2013 a las 2pm')
        ->and($monthName->start->date()->toDateTimeString())->toBe('2013-07-03 14:00:00');
});

it('parses spanish slash dates', function () {
    $spanish = Chrono::es();
    $monday = $spanish->parseText('lunes 8/2/2016', '2012-08-10')[0];
    $tuesday = $spanish->parseText('Martes 9/2/2016', '2012-08-10')[0];

    expect($monday->text)->toBe('lunes 8/2/2016')
        ->and($monday->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->tags())->toContain('parser/ESSlashDateParser')
        ->and($tuesday->text)->toBe('Martes 9/2/2016')
        ->and($tuesday->start->date()->toDateTimeString())->toBe('2016-02-09 12:00:00')
        ->and($spanish->parseDateText('8/2', '2012-08-10', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2013-02-08 12:00:00');
});

it('parses spanish month name dates', function () {
    $spanish = Chrono::es();
    $explicit = $spanish->parseText('10 Agosto 2012', '2012-08-10')[0];
    $bc = $spanish->parseText('10 Agosto 234 AC', '2012-08-10')[0];
    $ad = $spanish->parseText('10 Agosto 88 d. C.', '2012-08-10')[0];
    $compact = $spanish->parseText('Dom 15Sep', '2013-08-10')[0];
    $inferred = $spanish->parseText('La fecha limite es el martes, 10 de enero', '2012-08-10')[0];
    $withTime = $spanish->parseText('12 de julio a las 19:00', '2012-08-10')[0];

    expect($explicit->text)->toBe('10 Agosto 2012')
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($bc->start->get('year'))->toBe(-234)
        ->and($bc->start->date()->month)->toBe(8)
        ->and($bc->start->date()->day)->toBe(10)
        ->and($ad->start->get('year'))->toBe(88)
        ->and($compact->text)->toBe('Dom 15Sep')
        ->and($compact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($compact->start->isCertain('weekday'))->toBeTrue()
        ->and($inferred->text)->toBe('martes, 10 de enero')
        ->and($inferred->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($inferred->start->isCertain('weekday'))->toBeTrue()
        ->and($withTime->text)->toBe('12 de julio a las 19:00')
        ->and($withTime->start->date()->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($withTime->start->tags())->toContain('parser/ESMonthNameParser');
});

it('parses spanish month name ranges', function () {
    $spanish = Chrono::es();
    $sameDash = $spanish->parseText('10 - 22 Agosto 2012', '2012-08-10')[0];
    $sameWord = $spanish->parseText('10 a 22 Agosto 2012', '2012-08-10')[0];
    $sameDesde = $spanish->parseText('10º desde 22ª Agosto 2012', '2012-08-10')[0];
    $cross = $spanish->parseText('10 Agosto - 12 Septiembre', '2012-08-10')[0];
    $crossYear = $spanish->parseText('10 Agosto - 12 Septiembre 2013', '2012-08-10')[0];

    expect($sameDash->text)->toBe('10 - 22 Agosto 2012')
        ->and($sameDash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDash->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameWord->text)->toBe('10 a 22 Agosto 2012')
        ->and($sameWord->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameWord->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameDesde->text)->toBe('10º desde 22ª Agosto 2012')
        ->and($sameDesde->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDesde->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($cross->text)->toBe('10 Agosto - 12 Septiembre')
        ->and($cross->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($cross->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossYear->text)->toBe('10 Agosto - 12 Septiembre 2013')
        ->and($crossYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00');
});

it('merges spanish dates with times and date ranges', function () {
    $spanish = Chrono::es();
    $dateTime = $spanish->parseText('Evento 10 Agosto 2012, 6pm', '2012-08-10')[0];
    $dateTimeWithA = $spanish->parseText('Evento 10 Agosto 2012 a 6pm', '2012-08-10')[0];
    $dateRange = $spanish->parseText('Evento 10/08/2012 - 12/08/2012', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 Agosto 2012, 6pm')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($dateTimeWithA->text)->toBe('10 Agosto 2012 a 6pm')
        ->and($dateTimeWithA->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($dateRange->text)->toBe('10/08/2012 - 12/08/2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange');
});

it('parses spanish relative durations', function () {
    $spanish = Chrono::es();
    $timer = $spanish->parseText('establecer un temporizador de 5 minutos', '2012-08-10 12:14')[0];
    $movingCar = $spanish->parseText('En 5 segundos un auto se moverá', '2012-08-10 12:14')[0];
    $uppercaseMinutes = $spanish->parseText('En 5 Minutos hay que mover un coche', '2012-08-10 12:14')[0];

    expect($spanish->parseText('Tenemos que hacer algo en 5 días.', '2012-08-10 00:00')[0]->text)
        ->toBe('en 5 días')
        ->and($spanish->parseText('Tenemos que hacer algo en 5 días.', '2012-08-10 00:00')[0]->index)
        ->toBe(23)
        ->and($spanish->parseDateText('Tenemos que hacer algo en cinco días.', '2012-08-10 11:12')?->toDateTimeString())
        ->toBe('2012-08-15 11:12:00')
        ->and($spanish->parseDateText('en 5 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseDateText('por 5 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseDateText('en 1 hora', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($spanish->parseDateText('durante dos horas y tres minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 14:17:00')
        ->and($spanish->parseDateText('de 3 días', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-13 12:14:00')
        ->and($timer->index)
        ->toBe(27)
        ->and($timer->text)
        ->toBe('de 5 minutos')
        ->and($spanish->parseDateText('establecer un temporizador de 5 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseText('En 5 minutos me voy a casa', '2012-08-10 12:14')[0]->text)
        ->toBe('En 5 minutos')
        ->and($movingCar->text)
        ->toBe('En 5 segundos')
        ->and($movingCar->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:14:05')
        ->and($spanish->parseDateText('en dos semanas', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-24 12:14:00')
        ->and($spanish->parseDateText('dentro de un mes', '2012-08-10 07:14')?->toDateTimeString())
        ->toBe('2012-09-10 07:14:00')
        ->and($spanish->parseDateText('en algunos meses', '2012-07-10 22:14')?->toDateTimeString())
        ->toBe('2012-10-10 22:14:00')
        ->and($spanish->parseDateText('en un año', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($spanish->parseDateText('dentro de un año', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($uppercaseMinutes->text)
        ->toBe('En 5 Minutos')
        ->and($uppercaseMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($spanish->parseText('durante dos horas', '2012-08-10 12:14')[0]->tags())
        ->toContain('result/relativeDate');
});

it('parses spanish past relative durations', function () {
    $spanish = Chrono::es();

    expect($spanish->parseText('hace 5 días, hicimos algo', '2012-08-10')[0]->text)
        ->toBe('hace 5 días')
        ->and($spanish->parseText('hace 5 días, hicimos algo', '2012-08-10')[0]->tags())
        ->toContain('parser/ESTimeUnitAgoFormatParser')
        ->and($spanish->parseDateText('hace 5 días, hicimos algo', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 00:00:00')
        ->and($spanish->parseDateText('hace 15 minutos', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 11:59:00')
        ->and($spanish->parseDateText('hace 12 horas', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 00:14:00')
        ->and($spanish->parseDateText('hace 5 meses, hicimos algo', '2012-10-10')?->toDateTimeString())
        ->toBe('2012-05-10 00:00:00')
        ->and($spanish->parseDateText('hace 5 años, hicimos algo', '2012-08-10 22:22')?->toDateTimeString())
        ->toBe('2007-08-10 22:22:00')
        ->and($spanish->parseDateText('hace una semana, hicimos algo', '2012-08-03 08:34')?->toDateTimeString())
        ->toBe('2012-07-27 08:34:00');
});

it('parses spanish weekdays', function () {
    $spanish = Chrono::es();
    $thursday = $spanish->parseText('jueves', '2012-08-10 12:00')[0];
    $friday = $spanish->parseText('viernes', '2012-08-10 12:00')[0];

    expect($thursday->text)->toBe('jueves')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($thursday->start->tags())->toContain('parser/ESWeekdayParser')
        ->and($friday->text)->toBe('viernes')
        ->and($friday->start->get('weekday'))->toBe(5)
        ->and($friday->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($spanish->parseDateText('viernes', '2012-08-10 12:00', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2012-08-17 00:00:00')
        ->and($spanish->parseDateText('próximo viernes', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-17 00:00:00');
});

it('parses french casual dates and times', function () {
    $french = Chrono::fr();
    $now = $french->parseText('La deadline est maintenant', '2012-08-10 08:09:10.011')[0];
    $noon = $french->parseText('a midi', '2012-08-10 09:00')[0];
    $midnight = $french->parseText('à minuit', '2012-08-10 09:00')[0];

    expect($now->text)->toBe('maintenant')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/FRCasualDateParser')
        ->and($french->parseDateText("La deadline est aujourd'hui", '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($french->parseDateText('La deadline est demain', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00')
        ->and($french->parseDateText('La deadline était hier', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($french->parseDateText('La deadline était la veille', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and($french->parseDateText('La deadline est ce matin', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 08:00:00')
        ->and($french->parseDateText('La deadline est cet après-midi', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 14:00:00')
        ->and($french->parseDateText('La deadline est cet aprem', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 14:00:00')
        ->and($french->parseDateText('La deadline est ce soir', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($french->parseDateText('soir', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($french->parseDateText('a midi', '2012-08-10 09:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($noon->text)->toBe('a midi')
        ->and($noon->start->isCertain('hour'))->toBeTrue()
        ->and($noon->start->tags())->toContain('parser/FRCasualTimeParser')
        ->and($noon->start->isCertain('day'))->toBeFalse()
        ->and($french->parseDateText('à minuit', '2012-08-10 09:00')?->toDateTimeString())
        ->toBe('2012-08-10 00:00:00')
        ->and($midnight->text)->toBe('à minuit')
        ->and($midnight->start->isCertain('hour'))->toBeTrue()
        ->and($midnight->start->isCertain('day'))->toBeFalse()
        ->and($french->parseDateText("La deadline est aujourd'hui 17:00", '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00')
        ->and($french->parseDateText('La deadline est demain matin 11h', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 11:00:00');
});

it('parses french time expressions', function () {
    $french = Chrono::fr();
    $hourMinute = $french->parseText('8h10', '2012-08-10 00:00')[0];
    $hourMinuteSuffix = $french->parseText('8h10m', '2012-08-10 00:00')[0];
    $withZeroSeconds = $french->parseText('8h10m00', '2012-08-10 00:00')[0];
    $withSeconds = $french->parseText('8h10m00s', '2012-08-10 00:00')[0];
    $withMilliseconds = $french->parseText('8:10:30.123', '2012-08-10 00:00')[0];
    $prefixed = $french->parseText('RDV à 6.13 AM', '2012-08-10 00:00')[0];

    expect($hourMinute->text)->toBe('8h10')
        ->and($hourMinute->index)->toBe(0)
        ->and($hourMinute->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($hourMinute->start->tags())->toContain('parser/FRSpecificTimeExpressionParser')
        ->and($hourMinute->start->isCertain('day'))->toBeFalse()
        ->and($hourMinute->start->isCertain('month'))->toBeFalse()
        ->and($hourMinute->start->isCertain('year'))->toBeFalse()
        ->and($hourMinute->start->isCertain('hour'))->toBeTrue()
        ->and($hourMinute->start->isCertain('minute'))->toBeTrue()
        ->and($hourMinute->start->isCertain('second'))->toBeFalse()
        ->and($hourMinuteSuffix->text)->toBe('8h10m')
        ->and($hourMinuteSuffix->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($hourMinuteSuffix->start->isCertain('second'))->toBeFalse()
        ->and($withZeroSeconds->text)->toBe('8h10m00')
        ->and($withZeroSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($withZeroSeconds->start->isCertain('second'))->toBeTrue()
        ->and($french->parseDateText('8:10 PM', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 20:10:00')
        ->and($french->parseDateText('8h10 PM', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 20:10:00')
        ->and($french->parseDateText('1230pm', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:30:00')
        ->and($french->parseDateText('5:16p', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5h16p', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5h16mp', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5:16 p.m.', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5h16 p.m.', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($prefixed->index)
        ->toBe(4)
        ->and($prefixed->text)
        ->toBe('à 6.13 AM')
        ->and($prefixed->start->date()->toDateTimeString())
        ->toBe('2012-08-10 06:13:00')
        ->and($prefixed->start->tags())
        ->toContain('parser/FRTimeExpressionParser')
        ->and($withSeconds->text)->toBe('8h10m00s')
        ->and($withSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($withSeconds->start->isCertain('second'))->toBeTrue()
        ->and($withMilliseconds->text)->toBe('8:10:30.123')
        ->and($withMilliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($withMilliseconds->start->isCertain('millisecond'))->toBeTrue()
        ->and($french->parseText('8:62', '2012-08-10'))->toBe([])
        ->and($french->parseText('25:12', '2012-08-10'))->toBe([])
        ->and($french->parseText('12h12:99s', '2012-08-10'))->toBe([])
        ->and($french->parseText('13.12 PM', '2012-08-10'))->toBe([]);
});

it('parses french time ranges', function () {
    $french = Chrono::fr();
    $hourRange = $french->parseText('13h-15h', '2012-08-10 00:00')[0];
    $impliedHourRange = $french->parseText('13-15h', '2012-08-10 00:00')[0];
    $pmRange = $french->parseText('1-3pm', '2012-08-10 00:00')[0];
    $overnight = $french->parseText('11pm-2', '2012-08-10 00:00')[0];
    $minuteRange = $french->parseText('8:10 - 12.32', '2012-08-10 00:00')[0];
    $mixedRange = $french->parseText('8:10 - 12h32', '2012-08-10 00:00')[0];
    $tildeRange = $french->parseText('8:10 ~ 12h32', '2012-08-10 00:00')[0];
    $prefixedRange = $french->parseText(' de 6:30pm à 11:00pm ', '2012-08-10 00:00')[0];

    expect($hourRange->text)->toBe('13h-15h')
        ->and($hourRange->index)->toBe(0)
        ->and($hourRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($hourRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($hourRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($hourRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($impliedHourRange->text)->toBe('13-15h')
        ->and($impliedHourRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($impliedHourRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($pmRange->text)->toBe('1-3pm')
        ->and($pmRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($pmRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($pmRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($pmRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->text)->toBe('11pm-2')
        ->and($overnight->start->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($overnight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2012-08-11 02:00:00')
        ->and($overnight->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($minuteRange->text)->toBe('8:10 - 12.32')
        ->and($minuteRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($minuteRange->start->isCertain('second'))->toBeFalse()
        ->and($minuteRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($minuteRange->end?->isCertain('second'))->toBeFalse()
        ->and($mixedRange->text)->toBe('8:10 - 12h32')
        ->and($mixedRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($mixedRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($tildeRange->text)->toBe('8:10 ~ 12h32')
        ->and($tildeRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($tildeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($prefixedRange->text)->toBe('de 6:30pm à 11:00pm')
        ->and($prefixedRange->index)->toBe(1)
        ->and($prefixedRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($prefixedRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($french->parseText(' 2012 à 10:12:59', '2012-08-10 00:00')[0]->index)->toBe(6)
        ->and($french->parseText(' 2012 à 10:12:59', '2012-08-10 00:00')[0]->text)->toBe('à 10:12:59')
        ->and($french->parseDateText(' 2012 à 10:12:59', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 10:12:59');
});

it('merges french dates followed by time expressions', function () {
    $french = Chrono::fr();
    $iso = $french->parseText('Quelque chose se passe le 2014-04-18 à 3h00', '2012-08-10')[0];
    $isoRange = $french->parseText('Quelque chose se passe le 2014-04-18 7:00 - 8h00 ...', '2012-08-10')[0];
    $isoDeRange = $french->parseText('Quelque chose se passe le 2014-04-18 de 7:00 à 20:00 ...', '2012-08-10')[0];
    $month = $french->parseText('Quelque chose se passe le 10 Août 2012 à 10:12:59', '2012-08-10')[0];
    $compactMonth = $french->parseText('Quelque chose se passe le 15juin 2016 20h', '2016-07-10')[0];
    $attachedWeekday = $french->parseText('Jeudi6/5/2013 de 7h à 10h')[0];

    expect($iso->text)->toBe('2014-04-18 à 3h00')
        ->and($iso->index)->toBe(26)
        ->and($iso->start->date()->toDateTimeString())->toBe('2014-04-18 03:00:00')
        ->and($iso->start->isCertain('millisecond'))->toBeFalse()
        ->and($iso->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($isoRange->text)->toBe('2014-04-18 7:00 - 8h00')
        ->and($isoRange->index)->toBe(26)
        ->and($isoRange->start->date()->toDateTimeString())->toBe('2014-04-18 07:00:00')
        ->and($isoRange->start->isCertain('meridiem'))->toBeFalse()
        ->and($isoRange->start->isCertain('millisecond'))->toBeFalse()
        ->and($isoRange->start->tags())->toContain('parser/FRIsoDateTimeRangeParser')
        ->and($isoRange->end?->date()->toDateTimeString())->toBe('2014-04-18 08:00:00')
        ->and($isoRange->end?->isCertain('meridiem'))->toBeFalse()
        ->and($isoRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($isoRange->end?->tags())->toContain('parser/FRIsoDateTimeRangeParser')
        ->and($isoDeRange->text)->toBe('2014-04-18 de 7:00 à 20:00')
        ->and($isoDeRange->start->date()->toDateTimeString())->toBe('2014-04-18 07:00:00')
        ->and($isoDeRange->start->isCertain('meridiem'))->toBeFalse()
        ->and($isoDeRange->end?->date()->toDateTimeString())->toBe('2014-04-18 20:00:00')
        ->and($isoDeRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($month->text)->toBe('10 Août 2012 à 10:12:59')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-10 10:12:59')
        ->and($month->start->isCertain('millisecond'))->toBeFalse()
        ->and($compactMonth->text)->toBe('15juin 2016 20h')
        ->and($compactMonth->index)->toBe(26)
        ->and($compactMonth->start->date()->toDateTimeString())->toBe('2016-06-15 20:00:00')
        ->and($attachedWeekday->text)->toBe('Jeudi6/5/2013 de 7h à 10h')
        ->and($attachedWeekday->start->get('weekday'))->toBe(4)
        ->and($attachedWeekday->start->date()->toDateTimeString())->toBe('2013-05-06 07:00:00')
        ->and($attachedWeekday->end?->date()->toDateTimeString())->toBe('2013-05-06 10:00:00');
});

it('merges french slash date ranges', function () {
    $french = Chrono::fr();
    $dash = $french->parseText('Evénement 10/08/2012 - 12/08/2012', '2012-08-10')[0];
    $au = $french->parseText('Evénement 10/08/2012 au 12/08/2012', '2012-08-10')[0];

    expect($dash->text)->toBe('10/08/2012 - 12/08/2012')
        ->and($dash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dash->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dash->tags())->toContain('refiner/mergeDateRange')
        ->and($au->text)->toBe('10/08/2012 au 12/08/2012')
        ->and($au->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($au->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($au->tags())->toContain('refiner/mergeDateRange');
});

it('extracts french timezones from weekday time expressions', function () {
    $french = Chrono::fr();
    $plain = $french->parseText('Vendredi à 2 pm', '2016-04-28')[0];
    $est = $french->parseText('vendredi 2 pm EST', '2016-04-28')[0];
    $cet = $french->parseText('vendredi 15h CET', '2016-02-28')[0];
    $cest = $french->parseText('vendredi 15h cest', '2016-02-28')[0];
    $lowerEst = $french->parseText('Vendredi à 2 pm est', '2016-04-28')[0];
    $sentence = $french->parseText("Vendredi à 2 pm j'ai rdv...", '2016-04-28')[0];
    $sentenceWords = $french->parseText('Vendredi à 2 pm je vais faire quelque chose', '2016-04-28')[0];

    expect($plain->text)->toBe('Vendredi à 2 pm')
        ->and($plain->start->timezoneOffset())->toBeNull()
        ->and($plain->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($est->text)->toBe('vendredi 2 pm EST')
        ->and($est->start->date()->toDateTimeString())->toBe('2016-04-29 14:00:00')
        ->and($est->start->timezoneOffset())->toBe(-300)
        ->and($est->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cet->text)->toBe('vendredi 15h CET')
        ->and($cet->start->timezoneOffset())->toBe(60)
        ->and($cet->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cest->text)->toBe('vendredi 15h cest')
        ->and($cest->start->timezoneOffset())->toBe(120)
        ->and($lowerEst->text)->toBe('Vendredi à 2 pm est')
        ->and($lowerEst->start->timezoneOffset())->toBe(-300)
        ->and($sentence->text)->toBe('Vendredi à 2 pm')
        ->and($sentence->start->timezoneOffset())->toBeNull()
        ->and($sentence->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($sentenceWords->text)->toBe('Vendredi à 2 pm')
        ->and($sentenceWords->start->timezoneOffset())->toBeNull()
        ->and($sentenceWords->start->isCertain('timezoneOffset'))->toBeFalse();
});

it('parses french random date and time expressions', function () {
    $french = Chrono::fr();

    expect($french->parseText('lundi 29/4/2013 630-930am')[0]->text)
        ->toBe('lundi 29/4/2013 630-930am')
        ->and($french->parseText('mercredi 1/5/2013 1115am')[0]->text)
        ->toBe('mercredi 1/5/2013 1115am')
        ->and($french->parseText('vendredi 3/5/2013 1230pm')[0]->text)
        ->toBe('vendredi 3/5/2013 1230pm')
        ->and($french->parseText('dimanche 6/5/2013  750am-910am')[0]->text)
        ->toBe('dimanche 6/5/2013  750am-910am')
        ->and($french->parseText('lundi 13/5/2013 630-930am')[0]->text)
        ->toBe('lundi 13/5/2013 630-930am')
        ->and($french->parseText('Vendredi 21/6/2013 2:30')[0]->text)
        ->toBe('Vendredi 21/6/2013 2:30')
        ->and($french->parseText('mardi 7/2/2013 1-230 pm')[0]->text)
        ->toBe('mardi 7/2/2013 1-230 pm')
        ->and($french->parseText('mardi 7/2/2013 1-23h0')[0]->text)
        ->toBe('mardi 7/2/2013 1-23h0')
        ->and($french->parseText('mardi 7/2/2013 1h-23h0m')[0]->text)
        ->toBe('mardi 7/2/2013 1h-23h0m')
        ->and($french->parseText('Lundi, 24/6/2013, 7:00pm - 8:30pm')[0]->text)
        ->toBe('Lundi, 24/6/2013, 7:00pm - 8:30pm')
        ->and($french->parseText('Jeudi6/5/2013 de 7h à 10h')[0]->text)
        ->toBe('Jeudi6/5/2013 de 7h à 10h')
        ->and($french->parseText('18h')[0]->text)
        ->toBe('18h')
        ->and($french->parseText('18-22h')[0]->text)
        ->toBe('18-22h')
        ->and($french->parseText('11h-13')[0]->text)
        ->toBe('11h-13')
        ->and($french->parseText('à 12h')[0]->text)
        ->toBe('à 12h')
        ->and($french->parseText('Mercredi, 3 juil 2013 14h')[0]->text)
        ->toBe('Mercredi, 3 juil 2013 14h')
        ->and($french->parseText('that I need to know or am I covered?'))
        ->toBe([]);
});

it('parses french slash dates', function () {
    $french = Chrono::fr();
    $explicit = $french->parseText('8/2/2016', '2012-08-10')[0];
    $withArticle = $french->parseText('le 8/2/2016', '2012-08-10')[0];
    $inferredYear = $french->parseText('le 8/2', '2012-08-10')[0];
    $weekday = $french->parseText('lundi 8/2/2016', '2012-08-10')[0];
    $twoDigitYear = $french->parseText('samedi 9/2/20 ', '2012-08-10')[0];

    expect($explicit->text)->toBe('8/2/2016')
        ->and($explicit->index)->toBe(0)
        ->and($explicit->start->get('year'))->toBe(2016)
        ->and($explicit->start->get('month'))->toBe(2)
        ->and($explicit->start->get('day'))->toBe(8)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($explicit->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($withArticle->text)->toBe('8/2/2016')
        ->and($withArticle->index)->toBe(3)
        ->and($withArticle->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($inferredYear->text)->toBe('8/2')
        ->and($inferredYear->index)->toBe(3)
        ->and($inferredYear->start->isCertain('year'))->toBeFalse()
        ->and($inferredYear->start->date()->toDateTimeString())->toBe('2013-02-08 12:00:00')
        ->and($weekday->text)->toBe('lundi 8/2/2016')
        ->and($weekday->index)->toBe(0)
        ->and($weekday->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($weekday->start->isCertain('weekday'))->toBeTrue()
        ->and($weekday->start->tags())->toContain('parser/FRSlashDateParser')
        ->and($twoDigitYear->text)->toBe('samedi 9/2/20')
        ->and($twoDigitYear->index)->toBe(0)
        ->and($twoDigitYear->start->get('year'))->toBe(2020)
        ->and($twoDigitYear->start->get('weekday'))->toBe(6)
        ->and($twoDigitYear->start->date()->toDateTimeString())->toBe('2020-02-09 12:00:00')
        ->and($twoDigitYear->start->tags())->toContain('parser/FRSlashDateParser');
});

it('parses french month name dates', function () {
    $french = Chrono::fr();
    $explicit = $french->parseText('10 Août 2012', '2012-08-10')[0];
    $inferred = $french->parseText('8 Février', '2012-08-10')[0];
    $ordinal = $french->parseText('1er Août 2012', '2012-08-01')[0];
    $bc = $french->parseText('10 Août 234 AC', '2012-08-10')[0];
    $ad = $french->parseText('10 Août 88 p. Chr. n.', '2012-08-10')[0];
    $compact = $french->parseText('Dim 15 Sept', '2013-08-10')[0];
    $attached = $french->parseText('DIM 15SEPT', '2013-08-10')[0];
    $prefixed = $french->parseText('La date limite est le Mardi 10 janvier', '2012-08-10')[0];
    $abbreviatedWeekday = $french->parseText('La date limite est Mar 10 Jan', '2012-08-10')[0];

    expect($explicit->text)->toBe('10 Août 2012')
        ->and($explicit->index)->toBe(0)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($inferred->text)->toBe('8 Février')
        ->and($inferred->index)->toBe(0)
        ->and($inferred->start->date()->toDateTimeString())->toBe('2013-02-08 12:00:00')
        ->and($ordinal->text)->toBe('1er Août 2012')
        ->and($ordinal->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($bc->start->get('year'))->toBe(-234)
        ->and($bc->start->get('month'))->toBe(8)
        ->and($bc->start->get('day'))->toBe(10)
        ->and($ad->start->get('year'))->toBe(88)
        ->and($compact->text)->toBe('Dim 15 Sept')
        ->and($compact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($compact->start->isCertain('weekday'))->toBeTrue()
        ->and($attached->text)->toBe('DIM 15SEPT')
        ->and($attached->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($prefixed->text)->toBe('Mardi 10 janvier')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($prefixed->start->tags())->toContain('parser/FRMonthNameParser')
        ->and($prefixed->start->get('weekday'))->toBe(2)
        ->and($abbreviatedWeekday->text)->toBe('Mar 10 Jan')
        ->and($abbreviatedWeekday->index)->toBe(19)
        ->and($abbreviatedWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(2)
        ->and($french->parseDateText('31 mars 2016', '2012-08-10')?->toDateTimeString())->toBe('2016-03-31 12:00:00')
        ->and($french->parseDateText('10 Aout 2012', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($french->parseDateText('10 Fevrier 2012', '2012-08-10')?->toDateTimeString())->toBe('2012-02-10 12:00:00')
        ->and($french->parseDateText('10 Decembre 2012', '2012-08-10')?->toDateTimeString())->toBe('2012-12-10 12:00:00')
        ->and($french->parseText('32 Août 2014', '2012-08-10'))->toBe([])
        ->and($french->parseText('29 Février 2014', '2012-08-10'))->toBe([])
        ->and($french->parseText('32 Aout', '2012-08-10'))->toBe([])
        ->and($french->parseText('29 Fevrier', '2013-08-10'))->toBe([]);
});

it('parses french month name ranges and date times', function () {
    $french = Chrono::fr();
    $sameMonth = $french->parseText('10 - 22 août 2012', '2012-08-10')[0];
    $sameMonthAu = $french->parseText('10 au 22 août 2012', '2012-08-10')[0];
    $sameMonthUntil = $french->parseText("10 jusqu'au 22 août 2012", '2012-08-10')[0];
    $crossMonth = $french->parseText('10 août - 12 septembre', '2012-08-10')[0];
    $crossMonthYear = $french->parseText('10 août - 12 septembre 2013', '2012-08-10')[0];
    $repeatedMonth = $french->parseText('Du 24 août 2023 au 26 août 2023', '2012-08-10')[0];
    $crossYear = $french->parseText('24 décembre au 2 janvier', '2023-12-01')[0];

    expect($sameMonth->text)->toBe('10 - 22 août 2012')
        ->and($sameMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonth->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameMonthAu->text)->toBe('10 au 22 août 2012')
        ->and($sameMonthAu->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthAu->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameMonthUntil->text)->toBe("10 jusqu'au 22 août 2012")
        ->and($sameMonthUntil->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthUntil->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($crossMonth->text)->toBe('10 août - 12 septembre')
        ->and($crossMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($crossMonth->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossMonthYear->text)->toBe('10 août - 12 septembre 2013')
        ->and($crossMonthYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($repeatedMonth->text)->toBe('24 août 2023 au 26 août 2023')
        ->and($repeatedMonth->start->date()->toDateTimeString())->toBe('2023-08-24 12:00:00')
        ->and($repeatedMonth->end?->date()->toDateTimeString())->toBe('2023-08-26 12:00:00')
        ->and($crossYear->text)->toBe('24 décembre au 2 janvier')
        ->and($crossYear->start->date()->toDateTimeString())->toBe('2023-12-24 12:00:00')
        ->and($crossYear->end?->date()->toDateTimeString())->toBe('2024-01-02 12:00:00')
        ->and($french->parseDateText('12 juillet à 19:00', '2012-08-10')?->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($french->parseDateText('5 mai 12:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($french->parseDateText('7 Mai 11:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-07 11:00:00');
});

it('parses french weekdays', function () {
    $french = Chrono::fr();
    $monday = $french->parseText('Lundi', '2012-08-09')[0];
    $forwardMonday = $french->parseText('Lundi', '2012-08-09', ['forwardDate' => true])[0];
    $thursday = $french->parseText('Jeudi', '2012-08-09')[0];
    $sunday = $french->parseText('Dimanche', '2012-08-09')[0];
    $lastFriday = $french->parseText('la deadline était vendredi dernier...', '2012-08-09')[0];
    $nextFriday = $french->parseText('Planifions une réuinion vendredi prochain', '2015-04-18')[0];
    $monthOverlap = $french->parseText('Dimanche 7 décembre 2014', '2012-08-09')[0];
    $slashOverlap = $french->parseText('Dimanche 7/12/2014', '2012-08-09')[0];

    expect($monday->text)->toBe('Lundi')
        ->and($monday->index)->toBe(0)
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->tags())->toContain('parser/FRWeekdayParser')
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($forwardMonday->index)->toBe(0)
        ->and($forwardMonday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($thursday->text)->toBe('Jeudi')
        ->and($thursday->index)->toBe(0)
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($sunday->text)->toBe('Dimanche')
        ->and($sunday->index)->toBe(0)
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($lastFriday->text)->toBe('vendredi dernier')
        ->and($lastFriday->index)->toBe(19)
        ->and($lastFriday->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($nextFriday->text)->toBe('vendredi prochain')
        ->and($nextFriday->index)->toBe(25)
        ->and($nextFriday->start->date()->toDateTimeString())->toBe('2015-04-24 12:00:00')
        ->and($nextFriday->start->get('weekday'))->toBe(5)
        ->and($monthOverlap->text)->toBe('Dimanche 7 décembre 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($slashOverlap->text)->toBe('Dimanche 7/12/2014')
        ->and($slashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($slashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($slashOverlap->start->isCertain('weekday'))->toBeTrue();
});

it('parses french relative durations', function () {
    $french = Chrono::fr();
    $timer = $french->parseText('régler une minuterie de 5 minutes', '2012-08-10 12:14')[0];
    $movingCar = $french->parseText('Dans 5 secondes une voiture va bouger', '2012-08-10 12:14')[0];
    $uppercaseMinutes = $french->parseText('Dans 5 Minutes une voiture doit être bougée', '2012-08-10 12:14')[0];
    $abbreviatedMinutes = $french->parseText('Dans 5 mins une voiture doit être bougée', '2012-08-10 12:14')[0];

    expect($french->parseText('On doit faire quelque chose dans 5 jours.', '2012-08-10')[0]->text)
        ->toBe('dans 5 jours')
        ->and($french->parseText('On doit faire quelque chose dans 5 jours.', '2012-08-10')[0]->index)
        ->toBe(28)
        ->and($french->parseDateText('On doit faire quelque chose dans 5 jours.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-15 00:00:00')
        ->and($french->parseText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')[0]->text)
        ->toBe('dans cinq jours')
        ->and($french->parseText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')[0]->index)
        ->toBe(28)
        ->and($french->parseDateText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')?->toDateTimeString())
        ->toBe('2012-08-15 11:12:00')
        ->and($french->parseDateText('dans 5 minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseDateText('pour 5 minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseDateText('en 1 heure', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($french->parseDateText('pendant deux heures et trois minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 14:17:00')
        ->and($timer->index)
        ->toBe(22)
        ->and($timer->text)
        ->toBe('de 5 minutes')
        ->and($french->parseText('Dans 5 minutes je vais rentrer chez moi', '2012-08-10 12:14')[0]->text)
        ->toBe('Dans 5 minutes')
        ->and($movingCar->text)
        ->toBe('Dans 5 secondes')
        ->and($movingCar->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:14:05')
        ->and($french->parseDateText('dans deux semaines', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-24 12:14:00')
        ->and($french->parseDateText('dans un mois', '2012-08-10 07:14')?->toDateTimeString())
        ->toBe('2012-09-10 07:14:00')
        ->and($french->parseDateText('dans quelques mois', '2012-07-10 22:14')?->toDateTimeString())
        ->toBe('2012-10-10 22:14:00')
        ->and($french->parseDateText('en une année', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($french->parseDateText('dans une Année', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($uppercaseMinutes->text)
        ->toBe('Dans 5 Minutes')
        ->and($uppercaseMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($abbreviatedMinutes->text)
        ->toBe('Dans 5 mins')
        ->and($abbreviatedMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseText('pendant deux heures', '2012-08-10 12:14')[0]->tags())
        ->toContain('result/relativeDate')
        ->and($french->parseText('pendant deux heures', '2012-08-10 12:14')[0]->tags())
        ->toContain('parser/FRTimeUnitWithinFormatParser');
});

it('parses french past relative durations', function () {
    $french = Chrono::fr();
    $tenDays = $french->parseText('il y a 10 jours, on a fait quelque chose', '2012-08-10 13:30')[0];
    $fifteenMinutes = $french->parseText('il y a 15 minutes', '2012-08-10 12:14')[0];
    $spacedHours = $french->parseText('   il y a    12 heures', '2012-08-10 12:14')[0];
    $sentenceHours = $french->parseText("il y a 12 heures il s'est passé quelque chose", '2012-08-10 12:14')[0];
    $oneWeek = $french->parseText('il y a une semaine, on a fait quelque chose', '2012-08-03 08:34')[0];

    expect($french->parseText('il y a 5 jours, on a fait quelque chose', '2012-08-10')[0]->text)
        ->toBe('il y a 5 jours')
        ->and($french->parseText('il y a 5 jours, on a fait quelque chose', '2012-08-10')[0]->index)
        ->toBe(0)
        ->and($french->parseText('il y a 5 jours, on a fait quelque chose', '2012-08-10')[0]->tags())
        ->toContain('parser/FRTimeUnitAgoFormatParser')
        ->and($french->parseDateText('il y a 5 jours, on a fait quelque chose', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 00:00:00')
        ->and($tenDays->text)
        ->toBe('il y a 10 jours')
        ->and($tenDays->index)
        ->toBe(0)
        ->and($tenDays->start->date()->toDateTimeString())
        ->toBe('2012-07-31 13:30:00')
        ->and($fifteenMinutes->text)
        ->toBe('il y a 15 minutes')
        ->and($fifteenMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 11:59:00')
        ->and($spacedHours->index)
        ->toBe(3)
        ->and($spacedHours->text)
        ->toBe('il y a    12 heures')
        ->and($spacedHours->start->date()->toDateTimeString())
        ->toBe('2012-08-10 00:14:00')
        ->and($sentenceHours->text)
        ->toBe('il y a 12 heures')
        ->and($sentenceHours->start->date()->toDateTimeString())
        ->toBe('2012-08-10 00:14:00')
        ->and($french->parseDateText('il y a 5 mois, on a fait quelque chose', '2012-10-10')?->toDateTimeString())
        ->toBe('2012-05-10 00:00:00')
        ->and($french->parseDateText('il y a 5 ans, on a fait quelque chose', '2012-08-10 22:22')?->toDateTimeString())
        ->toBe('2007-08-10 22:22:00')
        ->and($oneWeek->text)
        ->toBe('il y a une semaine')
        ->and($oneWeek->start->date()->toDateTimeString())
        ->toBe('2012-07-27 08:34:00');
});

it('parses french casual relative units', function () {
    $french = Chrono::fr();
    $nextWeek = $french->parseText('la semaine prochaine', '2017-05-12')[0];
    $twoWeeks = $french->parseText('les 2 prochaines semaines', '2017-05-12 18:11')[0];
    $previousDays = $french->parseText('les 30 jours précédents', '2017-05-12')[0];
    $pastHours = $french->parseText('les 24 heures passées', '2017-05-12 11:27')[0];
    $nextSeconds = $french->parseText('les 90 secondes suivantes', '2017-05-12 11:27:03')[0];
    $lastMinutes = $french->parseText('les huit dernieres minutes', '2017-05-12 11:27')[0];
    $quarter = $french->parseText('le dernier trimestre', '2017-05-12 11:27')[0];
    $year = $french->parseText("l'année prochaine", '2017-05-12 11:27')[0];

    expect($french->parseText("le mois d'avril"))
        ->toBe([])
        ->and($french->parseText("le mois d'avril prochain"))
        ->toBe([])
        ->and($nextWeek->text)
        ->toBe('la semaine prochaine')
        ->and($nextWeek->start->date()->toDateTimeString())
        ->toBe('2017-05-19 00:00:00')
        ->and($quarter->tags())->toContain('parser/FRTimeUnitRelativeFormatParser')
        ->and($twoWeeks->text)
        ->toBe('les 2 prochaines semaines')
        ->and($twoWeeks->start->date()->toDateTimeString())
        ->toBe('2017-05-26 18:11:00')
        ->and($french->parseDateText('les trois prochaines semaines', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-06-02 00:00:00')
        ->and($french->parseDateText('le mois dernier', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-04-12 00:00:00')
        ->and($previousDays->text)
        ->toBe('les 30 jours précédents')
        ->and($previousDays->start->date()->toDateTimeString())
        ->toBe('2017-04-12 00:00:00')
        ->and($pastHours->text)
        ->toBe('les 24 heures passées')
        ->and($pastHours->start->date()->toDateTimeString())
        ->toBe('2017-05-11 11:27:00')
        ->and($nextSeconds->text)
        ->toBe('les 90 secondes suivantes')
        ->and($nextSeconds->start->date()->toDateTimeString())
        ->toBe('2017-05-12 11:28:33')
        ->and($lastMinutes->text)
        ->toBe('les huit dernieres minutes')
        ->and($lastMinutes->start->date()->toDateTimeString())
        ->toBe('2017-05-12 11:19:00')
        ->and($quarter->text)->toBe('le dernier trimestre')
        ->and($quarter->start->date()->toDateTimeString())->toBe('2017-02-12 11:27:00')
        ->and($quarter->start->isCertain('month'))->toBeFalse()
        ->and($quarter->start->isCertain('day'))->toBeFalse()
        ->and($quarter->start->isCertain('hour'))->toBeFalse()
        ->and($quarter->start->isCertain('minute'))->toBeFalse()
        ->and($quarter->start->isCertain('second'))->toBeFalse()
        ->and($year->text)->toBe("l'année prochaine")
        ->and($year->start->date()->toDateTimeString())->toBe('2018-05-12 11:27:00')
        ->and($year->start->isCertain('month'))->toBeFalse()
        ->and($year->start->isCertain('day'))->toBeFalse()
        ->and($year->start->isCertain('hour'))->toBeFalse()
        ->and($year->start->isCertain('minute'))->toBeFalse()
        ->and($year->start->isCertain('second'))->toBeFalse();
});

it('parses last night relative to the reference time', function () {
    expect(Chrono::parseDate('last night', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and(Chrono::parseDate('last night', '2012-08-10 01:00')?->toDateTimeString())
        ->toBe('2012-08-10 00:00:00');
});

it('parses casual times', function () {
    $morning = Chrono::parse('this morning', '2026-06-23 12:00')[0];

    expect($morning->start->date()->toDateTimeString())
        ->toBe('2026-06-23 06:00:00')
        ->and($morning->start->tags())->toContain('parser/ENCasualTimeParser')
        ->and(Chrono::parseDate('this afternoon', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-23 15:00:00')
        ->and(Chrono::parse('this afternoon at 3', '2016-10-01 08:00')[0]->text)
        ->toBe('this afternoon at 3')
        ->and(Chrono::parseDate('this afternoon at 3', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-10-01 15:00:00')
        ->and(Chrono::parseDate('this evening', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-23 20:00:00')
        ->and(Chrono::parseDate('noon', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and(Chrono::parse('at 12', '2012-08-10')[0]->text)->toBe('at 12')
        ->and(Chrono::parse('at 12', '2012-08-10')[0]->start->get('hour'))->toBe(12)
        ->and(Chrono::parse('at 12.30', '2012-08-10')[0]->text)->toBe('at 12.30')
        ->and(Chrono::parse('at 12.30', '2012-08-10')[0]->start->get('hour'))->toBe(12)
        ->and(Chrono::parse('at 12.30', '2012-08-10')[0]->start->get('minute'))->toBe(30);
});

it('defaults explicit casual time milliseconds to zero', function () {
    $morning = Chrono::parse('morning', '2012-08-10 08:09:10.011')[0];
    $midnight = Chrono::parse('midnight', '2012-08-10 08:09:10.011')[0];

    expect($morning->start->get('millisecond'))->toBe(0)
        ->and($morning->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 06:00:00.000')
        ->and($midnight->start->get('millisecond'))->toBe(0)
        ->and($midnight->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-11 00:00:00.000');
});

it('parses midnight relative to the reference time', function () {
    expect(Chrono::parseDate('midnight', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-24 00:00:00')
        ->and(Chrono::parseDate('midnight', '2026-06-23 01:00')?->toDateTimeString())
        ->toBe('2026-06-23 00:00:00');
});

it('merges casual dates followed by casual times', function () {
    $result = Chrono::parse('tomorrow morning', '2026-06-23 08:00')[0];
    $tonight = Chrono::parse('tonight at 8', '2012-01-01 12:00')[0];
    $midnight = Chrono::parse('at midnight on 12th August', '2012-08-10 15:00')[0];

    expect($result->text)->toBe('tomorrow morning')
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-24 06:00:00')
        ->and($result->start->isCertain('hour'))->toBeFalse()
        ->and($result->start->tags())->toContain('parser/ENCasualDateParser')
        ->and($result->start->tags())->toContain('parser/ENCasualTimeParser')
        ->and($result->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($tonight->text)->toBe('tonight at 8')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($midnight->text)->toBe('midnight on 12th August')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($midnight->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('merges dates followed by time ranges', function () {
    $result = Chrono::parse('Something happen on 2014-04-18 13:00 - 16:00 as')[0];

    expect($result->text)->toBe('2014-04-18 13:00 - 16:00')
        ->and($result->start->date()->toDateTimeString())->toBe('2014-04-18 13:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2014-04-18 16:00:00')
        ->and($result->tags())->toContain('refiner/mergeTrailingTimeRange');
});

it('merges time ranges followed by dates', function () {
    $result = Chrono::parse('9:00 AM to 5:00 PM, Tuesday, 20 May 2013', '2013-05-01')[0];

    expect($result->text)->toBe('9:00 AM to 5:00 PM, Tuesday, 20 May 2013')
        ->and($result->start->date()->toDateTimeString())->toBe('2013-05-20 09:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2013-05-20 17:00:00')
        ->and($result->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('parses compact month date time ranges', function () {
    $sameDay = Chrono::parse('SUN 15SEP 11:05 AM - 12:50 PM', '2013-08-01')[0];
    $crossDay = Chrono::parse('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM', '2013-08-01')[0];

    expect($sameDay->text)->toBe('SUN 15SEP 11:05 AM - 12:50 PM')
        ->and($sameDay->start->date()->toDateTimeString())->toBe('2013-09-15 11:05:00')
        ->and($sameDay->end?->date()->toDateTimeString())->toBe('2013-09-15 12:50:00')
        ->and($crossDay->text)->toBe('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM')
        ->and($crossDay->start->date()->toDateTimeString())->toBe('2013-09-13 13:29:00')
        ->and($crossDay->end?->date()->toDateTimeString())->toBe('2013-09-13 15:29:00');
});

it('merges casual date ranges with implied times', function () {
    $morningRange = Chrono::parse('annual leave from today morning to tomorrow', '2012-08-04 12:00')[0];
    $afternoonRange = Chrono::parse('annual leave from today to tomorrow afternoon', '2012-08-04 12:00')[0];

    expect($morningRange->text)->toBe('today morning to tomorrow')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2012-08-04 06:00:00')
        ->and($morningRange->start->isCertain('hour'))->toBeFalse()
        ->and($morningRange->start->tags())->toContain('parser/ENCasualTimeParser')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($morningRange->end?->isCertain('hour'))->toBeFalse()
        ->and($morningRange->tags())->toContain('refiner/mergeDateRange')
        ->and($afternoonRange->text)->toBe('today to tomorrow afternoon')
        ->and($afternoonRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($afternoonRange->start->isCertain('hour'))->toBeFalse()
        ->and($afternoonRange->end?->date()->toDateTimeString())->toBe('2012-08-05 15:00:00')
        ->and($afternoonRange->end?->isCertain('hour'))->toBeFalse()
        ->and($afternoonRange->end?->tags())->toContain('parser/ENCasualTimeParser')
        ->and($afternoonRange->tags())->toContain('refiner/mergeDateRange');
});

it('parses weekdays', function () {
    $date = Chrono::parseDate('next Friday at 4pm', '2026-06-23 09:00');

    expect($date?->toDateTimeString())->toBe('2026-07-03 16:00:00');
});

it('parses past and postfix week weekday modifiers', function () {
    expect(Chrono::parseDate('past Friday', '2012-08-09')?->toDateTimeString())
        ->toBe('2012-08-03 12:00:00')
        ->and(Chrono::parseDate('Friday next week', '2015-04-18')?->toDateTimeString())
        ->toBe('2015-04-24 12:00:00');
});

it('uses chrono weekday modifier semantics', function () {
    $monday = Chrono::parse('Monday', '2012-08-09')[0];
    $abbreviated = Chrono::parse('Mon.', '2012-08-09')[0];

    expect($monday->start->date()->toDateTimeString())
        ->toBe('2012-08-06 12:00:00')
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->tags())->toContain('parser/ENWeekdayParser')
        ->and($abbreviated->text)->toBe('Mon.')
        ->and($abbreviated->start->get('weekday'))->toBe(1)
        ->and(Chrono::parseDate('This Saturday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-06 12:00:00')
        ->and(Chrono::parseDate('Last Saturday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-07-30 12:00:00')
        ->and(Chrono::parseDate('Next Saturday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-13 12:00:00');
});

it('parses weekend and weekday mentions', function () {
    expect(Chrono::parseDate('last weekend', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-13 12:00:00')
        ->and(Chrono::parseDate('this weekend', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-19 12:00:00')
        ->and(Chrono::parseDate('next weekday', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-21 12:00:00');
});

it('merges weekdays followed by casual times', function () {
    $result = Chrono::parse('Tuesday morning', '2026-06-23 08:00')[0];

    expect($result->text)->toBe('Tuesday morning')
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-23 06:00:00');
});

it('parses weekday ranges', function () {
    $result = Chrono::parse('Friday to Monday', '2023-04-09')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2023-04-07 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2023-04-10 12:00:00');
});

it('parses forward weekday ranges', function () {
    $result = Chrono::parse('vacation monday - friday', '2019-06-13 12:00', ['forwardDate' => true])[0];
    $thisRange = Chrono::parse('this Friday to this Monday', '2016-08-04', ['forwardDate' => true])[0];

    expect($result->text)->toBe('monday - friday')
        ->and($result->start->date()->toDateTimeString())->toBe('2019-06-17 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2019-06-21 12:00:00')
        ->and($thisRange->text)->toBe('this Friday to this Monday')
        ->and($thisRange->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($thisRange->start->isCertain('weekday'))->toBeTrue()
        ->and($thisRange->start->isCertain('day'))->toBeFalse()
        ->and($thisRange->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($thisRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($thisRange->end?->isCertain('day'))->toBeFalse();
});

it('parses weekday time ranges', function () {
    $result = Chrono::parse('timeoff monday 7 to 9am', '2019-06-13 12:00', ['forwardDate' => true])[0];

    expect($result->text)->toBe('monday 7 to 9am')
        ->and($result->start->date()->toDateTimeString())->toBe('2019-06-17 07:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2019-06-17 09:00:00');
});

it('moves weekday range starts back when the end is earlier', function () {
    $result = Chrono::parse('Monday afternoon to last night', '2017-07-07 00:00')[0];

    expect($result->text)->toBe('Monday afternoon to last night')
        ->and($result->start->date()->toDateTimeString())->toBe('2017-07-03 15:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2017-07-07 00:00:00');
});

it('parses relative dates', function () {
    $result = Chrono::parse('5 days ago', '2026-06-23 09:15:30')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2026-06-18 09:15:30')
        ->and($result->start->get('day'))->toBe(18)
        ->and($result->start->isCertain('day'))->toBeTrue()
        ->and($result->tags())->toContain('result/relativeDate');
});

it('parses relative dates with abbreviated units', function () {
    $date = Chrono::parseDate('3w later', '2026-06-23 09:15:30');

    expect($date?->toDateTimeString())->toBe('2026-07-14 09:15:30');
});

it('parses within and in relative expressions', function () {
    expect(Chrono::parseDate('we have to make something in five days.', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-06-28 09:15:30')
        ->and(Chrono::parseDate('within half an hour', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-06-23 09:45:30');
});

it('does not parse for the unit phases as relative dates', function () {
    expect(Chrono::parse('for the year', '2026-06-23 09:15:30'))
        ->toBe([]);
});

it('parses relative duration aliases and decimal amounts', function () {
    expect(Chrono::parse('5 days from now, we did something', '2012-08-10 00:00')[0]->text)
        ->toBe('5 days from now')
        ->and(Chrono::parseDate('15 minutes earlier', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 11:59:00')
        ->and(Chrono::parseDate('15 minute out', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:29:00')
        ->and(Chrono::parseDate('3 quarters ago', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2011-11-10 12:14:00')
        ->and(Chrono::parseDate('2 qtrs later', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-02-10 12:14:00')
        ->and(Chrono::parseDate('in 1.5 hours', '2012-08-10 12:40')?->toDateTimeString())
        ->toBe('2012-08-10 14:10:00');
});

it('parses casual relative duration prefixes', function () {
    expect(Chrono::parseDate('next 2 weeks 3 days', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-18 12:00:00')
        ->and(Chrono::parseDate('after a year', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-10-01 12:00:00')
        ->and(Chrono::parseDate('next two quarters', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-04-01 12:00:00')
        ->and(Chrono::parseDate('after an hour', '2016-10-01 15:00')?->toDateTimeString())
        ->toBe('2016-10-01 16:00:00')
        ->and(Chrono::parseDate('last 2 weeks', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-17 12:00:00')
        ->and(Chrono::parseDate('past 2 days', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-29 12:00:00');
});

it('parses signed relative durations', function () {
    expect(Chrono::parseDate('+15min', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and(Chrono::parseDate('+1 day 2 hour', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-11 14:14:00')
        ->and(Chrono::parseDate('-3y', '2015-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:14:00')
        ->and(Chrono::parseDate('+1qtr', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-01-01 12:00:00')
        ->and(Chrono::parseDate('-2hr5min', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-01 09:55:00')
        ->and(Chrono::parseDate('-5d 00', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-26 00:00:00');
});

it('parses fuzzy within amount phrases', function () {
    expect(Chrono::parseDate('within a few months', '2012-08-10 12:49:00')?->toDateTimeString())
        ->toBe('2012-11-10 12:49:00')
        ->and(Chrono::parseDate('In several hours', '2012-08-10 12:49:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:49:00')
        ->and(Chrono::parseDate('In a couple of days', '2012-08-10 12:49:00')?->toDateTimeString())
        ->toBe('2012-08-12 12:49:00');
});

it('rejects abbreviated relative units in strict mode', function () {
    $strict = Chrono::strict();

    expect($strict->parseDateText('in 2hour', '2016-10-01 14:52')?->toDateTimeString())
        ->toBe('2016-10-01 16:52:00')
        ->and($strict->parseText('in 15m', '2016-10-01 14:52'))->toBe([])
        ->and($strict->parseText('within 5hr', '2016-10-01 14:52'))->toBe([])
        ->and($strict->parseText('5m ago', '2016-10-01 14:52'))->toBe([]);
});

it('parses multiple relative time units', function () {
    $date = Chrono::parseDate('set a timer for 1 hour, 5 minutes, and 30 seconds', '2026-06-23 09:15:30');

    expect($date?->toDateTimeString())->toBe('2026-06-23 10:21:00');
});

it('parses bare relative durations with the forward date option', function () {
    $month = Chrono::parse('give it 2 months', '2016-10-01 14:52:00', ['forwardDate' => true])[0];
    $hour = Chrono::parse('1 hour', '2012-08-10 12:14:00', ['forwardDate' => true])[0];

    expect($month->text)->toBe('2 months')
        ->and($month->start->date()->toDateTimeString())->toBe('2016-12-01 14:52:00')
        ->and($hour->text)->toBe('1 hour')
        ->and($hour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and(Chrono::parse('15 hours 29 min', '2012-08-10 22:30:00'))->toBe([])
        ->and(Chrono::parse('the second half', '2012-08-10 22:30:00', ['forwardDate' => true]))->toBe([]);
});

it('parses this last and next unit expressions', function () {
    expect(Chrono::parseDate('this month', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-06-01 09:15:30')
        ->and(Chrono::parseDate('this year', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-01-01 09:15:30')
        ->and(Chrono::parseDate('lastmonth', '2026-06-01 09:15:30')?->toDateTimeString())
        ->toBe('2026-05-01 09:15:30')
        ->and(Chrono::parseDate('last week', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-06-16 09:15:30')
        ->and(Chrono::parseDate('next month', '2026-06-23 09:15:30')?->toDateTimeString())
        ->toBe('2026-07-23 09:15:30');
});

it('marks relative unit certainty like chrono', function () {
    $nextHour = Chrono::parse('next hour', '2016-10-07 12:00:00')[0];
    $nextMonth = Chrono::parse('next month', '2016-10-07 12:00:00')[0];
    $nextYear = Chrono::parse('next year', '2020-11-22 12:11:32.006')[0];
    $nextQuarter = Chrono::parse('next quarter', '2021-01-22 12:00:00')[0];

    expect($nextHour->start->date()->toDateTimeString())->toBe('2016-10-07 13:00:00')
        ->and($nextHour->start->isCertain('hour'))->toBeTrue()
        ->and($nextMonth->start->date()->toDateTimeString())->toBe('2016-11-07 12:00:00')
        ->and($nextMonth->start->isCertain('year'))->toBeTrue()
        ->and($nextMonth->start->isCertain('month'))->toBeTrue()
        ->and($nextMonth->start->isCertain('day'))->toBeFalse()
        ->and($nextYear->start->date()->format('Y-m-d H:i:s.v'))->toBe('2021-11-22 12:11:32.006')
        ->and($nextYear->start->isCertain('year'))->toBeTrue()
        ->and($nextYear->start->isCertain('month'))->toBeFalse()
        ->and($nextYear->start->isCertain('day'))->toBeFalse()
        ->and($nextYear->start->get('millisecond'))->toBe(6)
        ->and($nextYear->start->isCertain('millisecond'))->toBeFalse()
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2021-04-22 12:00:00')
        ->and($nextQuarter->start->isCertain('year'))->toBeFalse()
        ->and($nextQuarter->start->isCertain('month'))->toBeFalse()
        ->and($nextQuarter->start->isCertain('day'))->toBeFalse();
});

it('merges relative durations before and after parsed dates', function () {
    $afterYesterday = Chrono::parse('2 weeks after yesterday', '2022-02-02 00:00')[0];
    $beforeSlashDate = Chrono::parse('2 months before 02/02', '2022-02-02 12:00')[0];
    $afterWeekday = Chrono::parse('2 days after next Friday', '2022-02-02 12:00')[0];

    expect($afterYesterday->text)->toBe('2 weeks after yesterday')
        ->and($afterYesterday->start->date()->toDateTimeString())->toBe('2022-02-15 00:00:00')
        ->and($afterYesterday->tags())->toContain('refiner/mergeRelativeFollowByDate')
        ->and($beforeSlashDate->text)->toBe('2 months before 02/02')
        ->and($beforeSlashDate->start->date()->toDateTimeString())->toBe('2021-12-02 12:00:00')
        ->and($beforeSlashDate->tags())->toContain('refiner/mergeRelativeFollowByDate')
        ->and($afterWeekday->text)->toBe('2 days after next Friday')
        ->and($afterWeekday->start->date()->toDateTimeString())->toBe('2022-02-13 12:00:00')
        ->and($afterWeekday->tags())->toContain('refiner/mergeRelativeFollowByDate');
});

it('merges casual date references with before and after durations', function () {
    expect(Chrono::parseDate('2 day before today', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-08 00:00:00')
        ->and(Chrono::parseDate('the day before yesterday', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-08 00:00:00')
        ->and(Chrono::parseDate('2 day before yesterday', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-07 00:00:00')
        ->and(Chrono::parseDate('a week before yesterday', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-02 00:00:00')
        ->and(Chrono::parseDate('2 day after today', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-12 00:00:00')
        ->and(Chrono::parseDate('the day after tomorrow', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-12 00:00:00')
        ->and(Chrono::parseDate('2 day after tomorrow', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-13 00:00:00')
        ->and(Chrono::parseDate('a week after tomorrow', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-18 00:00:00');
});

it('merges postfix plus and minus duration offsets', function () {
    $weekday = Chrono::parse('next tuesday +10 days', '2023-12-29 00:00')[0];
    $isoDate = Chrono::parse('2023-12-29 -10days', '2023-12-29 00:00')[0];
    $now = Chrono::parse('now + 40minutes', '2023-12-29 08:30')[0];

    expect($weekday->text)->toBe('next tuesday +10 days')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2024-01-12 12:00:00')
        ->and($weekday->tags())->toContain('refiner/mergeRelativeAfterDate')
        ->and($isoDate->text)->toBe('2023-12-29 -10days')
        ->and($isoDate->start->date()->toDateTimeString())->toBe('2023-12-19 12:00:00')
        ->and($isoDate->tags())->toContain('refiner/mergeRelativeAfterDate')
        ->and($now->text)->toBe('now + 40minutes')
        ->and($now->start->date()->toDateTimeString())->toBe('2023-12-29 09:10:00')
        ->and($now->tags())->toContain('refiner/mergeRelativeAfterDate');
});

it('parses standalone time expressions', function () {
    $result = Chrono::parse('  11 AM ', '2026-06-23 08:00:00')[0];
    $prefixed = Chrono::parse('2020 at  11 AM ', '2016-10-01 08:00:00')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2026-06-23 11:00:00')
        ->and($result->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($prefixed->index)->toBe(5)
        ->and($prefixed->text)->toBe('at  11 AM');
});

it('parses standalone 24 hour time expressions with seconds', function () {
    $date = Chrono::parseDate('20:32:13', '2026-06-23 08:00:00');

    expect($date?->toDateTimeString())->toBe('2026-06-23 20:32:13');
});

it('parses standalone time ranges', function () {
    $result = Chrono::parse('10:00:00 until 21:45:00', '2026-06-23 08:00:00')[0];
    $till = Chrono::parse('10:00:00 till 21:45:00', '2016-10-01 11:00:00')[0];
    $through = Chrono::parse('10:00:00 through 21:45:00', '2016-10-01 11:00:00')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2026-06-23 10:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2026-06-23 21:45:00')
        ->and($till->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:00')
        ->and($through->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:00');
});

it('moves merged date time ranges to the next day when the end time is earlier', function () {
    $oneAm = Chrono::parse('December 31, 2022 10:00 pm - 1:00 am', '2017-07-07')[0];
    $midnight = Chrono::parse('December 31, 2022 10:00 pm - 12:00 am', '2017-07-07')[0];

    expect($oneAm->start->date()->toDateTimeString())->toBe('2022-12-31 22:00:00')
        ->and($oneAm->end?->date()->toDateTimeString())->toBe('2023-01-01 01:00:00')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2022-12-31 22:00:00')
        ->and($midnight->end?->date()->toDateTimeString())->toBe('2023-01-01 00:00:00');
});

it('does not treat invalid following date fragments as time ranges', function () {
    $result = Chrono::parse('10:00:00 - 15/15', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('10:00:00')
        ->and($result->end)->toBeNull();
});

it('rejects unlikely loose English time guesses', function () {
    expect(Chrono::parse("I'm at 101,194 points!", '2012-08-10 12:00'))->toBe([])
        ->and(Chrono::parse("I'm at 101 points!", '2012-08-10 12:00'))->toBe([])
        ->and(Chrono::parse("I'm at 10.1", '2012-08-10 12:00'))->toBe([])
        ->and(Chrono::parse("I'm at 10.1 - 10.12", '2012-08-10 12:00'))->toBe([])
        ->and(Chrono::parse("I'm at 10 - 10.1", '2012-08-10 12:00'))->toBe([])
        ->and(Chrono::parse('1a', '2012-08-10 12:00'))->toBe([])
        ->and(Chrono::parseDate('1am', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 01:00:00')
        ->and(Chrono::parse('8pm - 11', '2012-08-10 12:00')[0]->text)->toBe('8pm - 11');
});

it('rejects casual time guesses in strict mode', function () {
    $strict = Chrono::strict();

    expect($strict->parseText("I'm at 10", '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText("I'm at 10 - 20", '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('7-730', '2012-08-10 12:00'))->toBe([]);
});

it('infers meridiem in time ranges', function () {
    $night = Chrono::parse('10 - 11 at night', '2016-10-01 08:00')[0];
    $startPm = Chrono::parse('8pm - 11', '2016-10-01 08:00')[0];
    $endPm = Chrono::parse('8 - 11pm', '2016-10-01 08:00')[0];
    $overnight = Chrono::parse('11pm - 3', '2016-10-01 08:00')[0];
    $plain = Chrono::parse('7 - 8', '2016-10-01 08:00')[0];
    $compactPm = Chrono::parse('1pm-3', '2012-08-10')[0];
    $compactAm = Chrono::parse('1am-3', '2012-08-10')[0];
    $compactOvernight = Chrono::parse('11pm-3', '2012-08-10')[0];
    $compactSameExplicit = Chrono::parse('10pm-10pm', '2012-08-10')[0];
    $compactSameImplied = Chrono::parse('10pm-10', '2012-08-10')[0];
    $endAm = Chrono::parse('12-3am', '2012-08-10')[0];
    $endPmNoon = Chrono::parse('12-3pm', '2012-08-10')[0];

    expect($night->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($night->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($night->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($night->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($startPm->start->date()->toDateTimeString())->toBe('2016-10-01 20:00:00')
        ->and($startPm->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($endPm->start->date()->toDateTimeString())->toBe('2016-10-01 20:00:00')
        ->and($endPm->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($endPm->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($endPm->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2016-10-02 03:00:00')
        ->and($overnight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($plain->start->date()->toDateTimeString())->toBe('2016-10-01 07:00:00')
        ->and($plain->end?->date()->toDateTimeString())->toBe('2016-10-01 08:00:00')
        ->and($compactPm->text)->toBe('1pm-3')
        ->and($compactPm->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($compactPm->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($compactPm->start->isCertain('meridiem'))->toBeTrue()
        ->and($compactPm->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($compactPm->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($compactPm->end?->isCertain('meridiem'))->toBeTrue()
        ->and($compactAm->start->date()->toDateTimeString())->toBe('2012-08-10 01:00:00')
        ->and($compactAm->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($compactAm->end?->date()->toDateTimeString())->toBe('2012-08-10 03:00:00')
        ->and($compactAm->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($compactAm->end?->isCertain('meridiem'))->toBeFalse()
        ->and($compactOvernight->start->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($compactOvernight->end?->date()->toDateTimeString())->toBe('2012-08-11 03:00:00')
        ->and($compactOvernight->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($compactSameExplicit->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($compactSameImplied->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($endAm->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($endAm->end?->date()->toDateTimeString())->toBe('2012-08-10 03:00:00')
        ->and($endPmNoon->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($endPmNoon->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00');
});

it('moves standalone times forward when requested', function () {
    $earlyMorning = Chrono::parse('1am', '2022-05-26 01:57', ['forwardDate' => true])[0];
    $lateMorning = Chrono::parse('11am', '2016-10-01 12:00', ['forwardDate' => true])[0];
    $overnight = Chrono::parse('11am to 1am', '2016-10-01 12:00', ['forwardDate' => true])[0];
    $sameDayRange = Chrono::parse('10am to 12pm', '2016-10-01 11:00', ['forwardDate' => true])[0];

    expect($earlyMorning->start->date()->toDateTimeString())->toBe('2022-05-27 01:00:00')
        ->and($lateMorning->start->date()->toDateTimeString())->toBe('2016-10-02 11:00:00')
        ->and($overnight->start->date()->toDateTimeString())->toBe('2016-10-02 11:00:00')
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2016-10-03 01:00:00')
        ->and($sameDayRange->start->date()->toDateTimeString())->toBe('2016-10-02 10:00:00')
        ->and($sameDayRange->end?->date()->toDateTimeString())->toBe('2016-10-02 12:00:00');
});

it('merges slash dates followed by separated time expressions', function () {
    expect(Chrono::parseDate('05/31/2024 14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024.14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024:14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024-14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00');
});

it('parses slash date ranges with times', function () {
    $plain = Chrono::parse('from 01/21/2021 10:00 to 01/01/2023 07:00', '2012-08-10 12:00')[0];
    $meridiem = Chrono::parse('08/08/2023, 09:15 AM to 08/29/2023, 09:15 AM', '2012-08-10 12:00')[0];

    expect($plain->start->date()->toDateTimeString())->toBe('2021-01-21 10:00:00')
        ->and($plain->end?->date()->toDateTimeString())->toBe('2023-01-01 07:00:00')
        ->and($plain->tags())->toContain('parser/SlashDateFormatParser')
        ->and($plain->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($plain->end?->tags())->toContain('parser/SlashDateFormatParser')
        ->and($meridiem->start->date()->toDateTimeString())->toBe('2023-08-08 09:15:00')
        ->and($meridiem->end?->date()->toDateTimeString())->toBe('2023-08-29 09:15:00');
});

it('merges time expressions followed by dates', function () {
    $monthDay = Chrono::parse('8:23 AM, Jul 9', '2016-10-01 08:00')[0];

    expect(Chrono::parseDate('14:15 05/31/2024', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and($monthDay->start->date()->toDateTimeString())
        ->toBe('2016-07-09 08:23:00')
        ->and($monthDay->start->isCertain('year'))->toBeFalse()
        ->and($monthDay->start->isCertain('month'))->toBeTrue()
        ->and($monthDay->start->isCertain('day'))->toBeTrue()
        ->and($monthDay->start->isCertain('hour'))->toBeTrue()
        ->and($monthDay->tags())->toContain('parser/ENTimeExpressionParser')
        ->and(Chrono::parse('8:23 AM ∙ Jul 9', '2016-10-01 08:00')[0]->text)
        ->toBe('8:23 AM ∙ Jul 9')
        ->and(Chrono::parseDate('8:23 AM ∙ Jul 9', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-07-09 08:23:00');
});

it('parses time expressions with day period clues', function () {
    expect(Chrono::parseDate('1 at night', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 01:00:00')
        ->and(Chrono::parseDate('11 tonight', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 23:00:00')
        ->and(Chrono::parseDate('6 in the morning', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 06:00:00')
        ->and(Chrono::parseDate('1 in the afternoon', '2026-06-23 08:00:00')?->toDateTimeString())
        ->toBe('2026-06-23 13:00:00')
        ->and(Chrono::parseDate('6 in the afternoon', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 18:00:00');
});

it('parses utc and gmt timezone offsets', function () {
    $result = Chrono::parse('11 am utc+02:45', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('11 am utc+02:45')
        ->and($result->start->timezoneOffset())->toBe(165)
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2026-06-23 11:00:00 +02:45');
});

it('preserves timezone offsets when merging dates and times', function () {
    $compact = Chrono::parse('wednesday, september 16, 2020 at 11 am utc+0245')[0];
    $hourOnly = Chrono::parse('wednesday, september 16, 2020 at 11 am utc+02')[0];
    $gmt = Chrono::parse('wednesday, september 16, 2020 at 11 am GMT -08:45')[0];
    $named = Chrono::parse('wednesday, september 16, 2020 at 11 am GMT+0900 (JST)')[0];

    expect($compact->start->timezoneOffset())->toBe(165)
        ->and($hourOnly->start->timezoneOffset())->toBe(120)
        ->and($gmt->start->timezoneOffset())->toBe(-525)
        ->and($named->start->timezoneOffset())->toBe(540)
        ->and($named->text)->toBe('wednesday, september 16, 2020 at 11 am GMT+0900 (JST)');
});

it('parses dotted time with numeric timezone offsets', function () {
    $valid = Chrono::parse('wednesday, september 16, 2020 at 23.00+1400')[0];
    $invalid = Chrono::parse('wednesday, september 16, 2020 at 23.00+15')[0];
    $trailingDigit = Chrono::parse('today at 10:00+09001', '2012-08-10 12:00')[0];

    expect($valid->text)->toBe('wednesday, september 16, 2020 at 23.00+1400')
        ->and($valid->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-09-16 23:00:00 +14:00')
        ->and($valid->start->timezoneOffset())->toBe(840)
        ->and($invalid->text)->toBe('wednesday, september 16, 2020 at 23.00')
        ->and($invalid->start->timezoneOffset())->toBeNull()
        ->and($trailingDigit->text)->toBe('today at 10:00+0900')
        ->and($trailingDigit->start->timezoneOffset())->toBe(540);
});

it('does not treat postfix duration offsets as timezones', function () {
    $result = Chrono::parse('today +10 days', '2026-06-23 08:00:00')[0];

    expect($result->start->timezoneOffset())->toBeNull()
        ->and($result->start->date()->toDateTimeString())->toBe('2026-07-03 08:00:00');
});

it('parses timezone abbreviations', function () {
    $result = Chrono::parse('11 am JST', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('11 am JST')
        ->and($result->start->timezoneOffset())->toBe(540)
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2026-06-23 11:00:00 +09:00');
});

it('extracts timezone abbreviations onto range ends independently like upstream', function () {
    $start = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 10:00:00')))
        ->assign('hour', 10)
        ->assign('timezoneOffset', 540);

    $end = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 11:00:00')))
        ->assign('hour', 11);

    $result = new ParsedResult(0, '10am-11am', $start, $end);
    $results = (new ExtractTimezoneAbbrRefiner)->refine(
        '10am-11am JST',
        [$result],
        Reference::make('2026-06-23 08:00:00'),
        new Options,
    );

    expect($results[0]->text)
        ->toBe('10am-11am JST')
        ->and($results[0]->start->timezoneOffset())->toBe(540)
        ->and($results[0]->end?->timezoneOffset())->toBe(540);
});

it('parses upstream timezone abbreviation map entries', function () {
    expect(Chrono::parse('11 am NPT', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(345)
        ->and(Chrono::parse('11 am ACST', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(570)
        ->and(Chrono::parse('11 am WET', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(0)
        ->and(Chrono::parse('11 am NOVT', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(360);
});

it('keeps wrapping punctuation outside timezone abbreviations', function () {
    $bare = Chrono::parse('Want to meet for dinner (5pm EST)?', '2020-09-01 12:00')[0];
    $wrapped = Chrono::parse('Want to meet for dinner 5pm (EST)?', '2020-09-01 12:00')[0];
    $open = Chrono::parse('today at 10:00 (JST', '2012-08-10 12:00')[0];
    $close = Chrono::parse('today at 10:00 JST)', '2012-08-10 12:00')[0];

    expect($bare->text)->toBe('5pm EST)')
        ->and($bare->start->timezoneOffset())->toBe(-300)
        ->and($wrapped->text)->toBe('5pm (EST)')
        ->and($wrapped->start->timezoneOffset())->toBe(-300)
        ->and($open->text)->toBe('today at 10:00 (JST')
        ->and($open->start->timezoneOffset())->toBe(540)
        ->and($close->text)->toBe('today at 10:00 JST)')
        ->and($close->start->timezoneOffset())->toBe(540);
});

it('parses custom timezone abbreviations from options', function () {
    $unknown = Chrono::parse('Jan 1st 2023 at 10:00 XYZ', '2023-01-01')[0];
    $custom = Chrono::parse('Jan 1st 2023 at 10:00 XYZ', '2023-01-01', [
        'timezones' => ['XYZ' => -180],
    ])[0];

    expect($unknown->start->timezoneOffset())->toBeNull()
        ->and($custom->text)->toBe('Jan 1st 2023 at 10:00 XYZ')
        ->and($custom->start->timezoneOffset())->toBe(-180)
        ->and($custom->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-01-01 10:00:00 -03:00');
});

it('parses custom ambiguous timezone abbreviations from options', function () {
    $timezone = [
        'timezoneOffsetDuringDst' => -120,
        'timezoneOffsetNonDst' => -180,
        'dstStart' => fn (int $year): CarbonInterface => CarbonImmutable::create($year, 3, 26, 2),
        'dstEnd' => fn (int $year): CarbonInterface => CarbonImmutable::create($year, 10, 29, 3),
    ];

    $standard = Chrono::parse('Jan 1st 2023 at 10:00 XYZ', '2023-01-01', [
        'timezones' => ['XYZ' => $timezone],
    ])[0];
    $daylight = Chrono::parse('Jun 1st 2023 at 10:00 XYZ', '2023-01-01', [
        'timezones' => ['XYZ' => $timezone],
    ])[0];

    expect($standard->start->timezoneOffset())->toBe(-180)
        ->and($standard->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-01-01 10:00:00 -03:00')
        ->and($daylight->start->timezoneOffset())->toBe(-120)
        ->and($daylight->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-06-01 10:00:00 -02:00');
});

it('parses timezone abbreviations on date-only and relative results', function () {
    $date = Chrono::parse('Wednesday, September 16, 2020, EST')[0];
    $lowercaseWord = Chrono::parse('in 1 day get eggs and milk', '2020-11-14 13:48:22')[0];
    $relativeDay = Chrono::parse('in 1 day GET', '2020-11-14 13:48:22')[0];
    $relativeWeek = Chrono::parse('next week EST', '2020-11-14 13:48:22')[0];

    expect($date->text)->toBe('Wednesday, September 16, 2020, EST')
        ->and($date->start->timezoneOffset())->toBe(-300)
        ->and($lowercaseWord->text)->toBe('in 1 day')
        ->and($lowercaseWord->start->timezoneOffset())->toBeNull()
        ->and($relativeDay->text)->toBe('in 1 day GET')
        ->and($relativeDay->start->timezoneOffset())->toBe(240)
        ->and($relativeWeek->text)->toBe('next week EST')
        ->and($relativeWeek->start->timezoneOffset())->toBe(-300);
});

it('parses date ranges with timezone abbreviations on both endpoints', function () {
    $result = Chrono::parse('10:30 JST today to 10:30 pst tomorrow', '2016-10-01 08:00')[0];

    expect($result->text)->toBe('10:30 JST today to 10:30 pst tomorrow')
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2016-10-01 10:30:00 +09:00')
        ->and($result->start->timezoneOffset())->toBe(540)
        ->and($result->end?->date()->format('Y-m-d H:i:s P'))->toBe('2016-10-02 10:30:00 -08:00')
        ->and($result->end?->timezoneOffset())->toBe(-480)
        ->and($result->tags())->toContain('refiner/mergeDateRange');
});

it('uses timezone-aware reference arrays', function () {
    $bst = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'BST',
    ]);

    $jst = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'JST',
    ]);

    $custom = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'BBB',
    ], [
        'timezones' => ['BBB' => 60],
    ]);

    $npt = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'NPT',
    ]);

    $ambiguous = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'XYZ',
    ], [
        'timezones' => [
            'XYZ' => [
                'timezoneOffsetDuringDst' => -120,
                'timezoneOffsetNonDst' => -180,
                'dstStart' => fn (int $year): CarbonImmutable => Timezone::getLastWeekdayOfMonth($year, Month::MARCH, Weekday::SUNDAY, 2),
                'dstEnd' => fn (int $year): CarbonImmutable => Timezone::getLastWeekdayOfMonth($year, Month::OCTOBER, Weekday::SUNDAY, 3),
            ],
        ],
    ]);

    $jsDateString = Chrono::parseDate('Friday at 4pm', [
        'instant' => 'Wed Jun 09 2021 07:00:00 GMT-0500 (CDT)',
        'timezone' => 'CDT',
    ]);

    $jsDateStringTime = Chrono::parseDate('1am', [
        'instant' => 'Wed May 26 2022 01:57:00 GMT-0500 (CDT)',
        'timezone' => 'CDT',
    ]);

    expect($bst?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +01:00')
        ->and($jst?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +09:00')
        ->and($custom?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +01:00')
        ->and($npt?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +05:45')
        ->and($ambiguous?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 -02:00')
        ->and($jsDateString?->format('Y-m-d H:i:s P'))->toBe('2021-06-11 16:00:00 -05:00')
        ->and($jsDateStringTime?->format('Y-m-d H:i:s P'))->toBe('2022-05-26 01:00:00 -05:00');
});

it('uses upstream reference timezone defaults and overrides', function () {
    expect(Chrono::parseDate('Friday at 4pm', '2021-06-09 07:00:00')?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00')
        ->and(Chrono::parseDate('Friday at 4pm', ['instant' => '2021-06-09 07:00:00'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00')
        ->and(Chrono::parseDate('Friday at 4pm', ['instant' => '2021-06-09 07:00:00', 'timezone' => null])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00')
        ->and(Chrono::parseDate('Friday at 4pm', ['instant' => '2021-06-09 07:00:00', 'timezone' => ''])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00');

    $jstInstant = 'Sun Jun 06 2021 19:00:00 GMT+0900 (JST)';

    expect(Chrono::parseDate('At 4pm tomorrow', ['instant' => $jstInstant, 'timezone' => 'BST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-07 16:00:00 +01:00')
        ->and(Chrono::parseDate('At 4pm tomorrow', ['instant' => $jstInstant, 'timezone' => 'JST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-07 16:00:00 +09:00')
        ->and(Chrono::parseDate('At 4pm tomorrow', ['instant' => $jstInstant, 'timezone' => 'BBB'], [
            'timezones' => ['BBB' => 60],
        ])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-07 16:00:00 +01:00');
});

it('uses reference timezone for written date times without embedded offsets like upstream', function () {
    expect(Chrono::parseDate('Sun Jun 06 2021 19:00:00', ['timezone' => 'JST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-06 19:00:00 +09:00')
        ->and(Chrono::parseDate('Sun Jun 06 2021 11:00:00', ['timezone' => 'BST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-06 11:00:00 +01:00')
        ->and(Chrono::parseDate('Sun Jun 06 2021 11:00:00', ['timezone' => 60])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-06 11:00:00 +01:00');
});

it('preserves precise now instants with reference timezone overrides', function () {
    $instant = 'Sat Mar 13 2021 14:22:14 GMT+0900 (Japan Standard Time)';

    $jst = Chrono::parseDate('now', $instant);
    $shifted = Chrono::parseDate('now', ['instant' => $instant, 'timezone' => -300]);

    expect($jst?->format('Y-m-d H:i:s P'))->toBe('2021-03-13 14:22:14 +09:00')
        ->and($shifted?->format('Y-m-d H:i:s P'))->toBe('2021-03-13 00:22:14 -05:00')
        ->and($shifted?->getTimestamp())->toBe($jst?->getTimestamp());
});

it('parses ambiguous timezone abbreviations using dst rules', function () {
    expect(Chrono::parse('2022-03-12 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-300)
        ->and(Chrono::parse('2022-03-13 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-240)
        ->and(Chrono::parse('2021-11-06 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-240)
        ->and(Chrono::parse('2021-11-07 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-300);
});

it('parses central european timezone abbreviation using dst rules', function () {
    expect(Chrono::parse('2022-03-26 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(60)
        ->and(Chrono::parse('2022-03-27 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(120)
        ->and(Chrono::parse('2022-10-29 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(120)
        ->and(Chrono::parse('2022-10-30 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(60);
});

it('merges time expressions followed by casual dates', function () {
    $result = Chrono::parse('10:30 PST today', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('10:30 PST today')
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2026-06-23 10:30:00 -08:00')
        ->and($result->start->timezoneOffset())->toBe(-480)
        ->and($result->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('returns all parsed results in document order', function () {
    $results = Chrono::parse('today and tomorrow', '2026-06-23 09:00');

    expect($results)->toHaveCount(2)
        ->and($results[0]->text)->toBe('today')
        ->and($results[1]->text)->toBe('tomorrow');
});

it('parses multiple dates from long document text', function () {
    $text = 'October 7, 2011, of which details were not revealed out of respect to Jobs\'s family.[239] '
        .'Apple announced on the same day that they had no plans for a public service, but were encouraging '
        .'"well-wishers" to send their remembrance messages to an email address created to receive such messages.[240] '
        .'Sunday, October 16, 2011';

    $results = Chrono::parse($text, '2012-08-10');

    expect($results)->toHaveCount(2)
        ->and($results[0]->index)->toBe(0)
        ->and($results[0]->text)->toBe('October 7, 2011')
        ->and($results[0]->start->date()->toDateTimeString())->toBe('2011-10-07 12:00:00')
        ->and($results[1]->index)->toBe(297)
        ->and($results[1]->text)->toBe('Sunday, October 16, 2011')
        ->and($results[1]->start->date()->toDateTimeString())->toBe('2011-10-16 12:00:00');
});

it('parses russian casual dates and times', function () {
    $tomorrow = Chrono::ru()->parseText('завтра', '2012-08-10 17:10')[0];
    $beforeYesterday = Chrono::ru()->parseText('позавчера', '2012-08-10 17:10')[0];
    $now = Chrono::ru()->parseText('сейчас', '2012-08-10 08:09:10.011')[0];
    $evening = Chrono::ru()->parseText('вечером', '2012-08-10 09:30')[0];
    $lastNight = Chrono::ru()->parseText('прошлой ночью', '2012-08-10 08:09:10.011')[0];
    $earlyLastNight = Chrono::ru()->parseText('прошлой ночью', '2012-08-10 02:09:10.011')[0];
    $tomorrowMorning = Chrono::ru()->parseText('Дедлайн завтра утром', '2012-08-10 17:10')[0];
    $casualRange = Chrono::ru()->parseText('Событие сегодня-завтра', '2012-08-10 12:00')[0];

    expect($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 17:10:00')
        ->and($tomorrow->start->tags())->toContain('parser/RUCasualDateParser')
        ->and($beforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 17:10:00')
        ->and($beforeYesterday->start->tags())->toContain('parser/RUCasualDateParser')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->isCertain('year'))->toBeTrue()
        ->and($now->start->isCertain('millisecond'))->toBeTrue()
        ->and($evening->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($evening->start->tags())->toContain('parser/RUCasualTimeParser')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($earlyLastNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($tomorrowMorning->text)->toBe('завтра утром')
        ->and($tomorrowMorning->start->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($casualRange->text)->toBe('сегодня-завтра')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-11 12:00:00');
});

it('parses russian month name dates and ranges', function () {
    $date = Chrono::ru()->parseText('10 августа 2012', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parseText('10-12 августа', '2012-08-10 09:30')[0];
    $crossMonthWithYear = Chrono::ru()->parseText('10 августа - 12 сентября 2013', '2012-08-10 09:30')[0];
    $month = Chrono::ru()->parseText('август 2012', '2012-08-10 09:30')[0];

    expect($date->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->tags())->toContain('parser/RUMonthNameLittleEndianParser')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->start->tags())->toContain('parser/RUMonthNameLittleEndianParser')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/RUMonthNameParser');
});

it('parses russian weekdays times and relative durations', function () {
    $weekday = Chrono::ru()->parseText('среда', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::ru()->parseText('следующий понедельник', '2012-08-10 09:30')[0];
    $timeWithSeconds = Chrono::ru()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $time = Chrono::ru()->parseText('в 6:30 вечера', '2012-08-10 09:30')[0];
    $timeRange = Chrono::ru()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morningTime = Chrono::ru()->parseText('в 11 утра', '2016-10-01 08:00')[0];
    $eveningTime = Chrono::ru()->parseText('в 11 вечера', '2016-10-01 08:00')[0];
    $morningRange = Chrono::ru()->parseText('с 10 до 11 утра', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::ru()->parseText('с 10 до 11 вечера', '2016-10-01 08:00')[0];
    $casualHour = Chrono::russian()->parseText('в 1', '2016-10-01 08:00')[0];
    $casualNoon = Chrono::russian()->parseText('в 12', '2016-10-01 08:00')[0];
    $casualDotted = Chrono::russian()->parseText('в 12.30', '2016-10-01 08:00')[0];
    $ago = Chrono::ru()->parseText('2 дня назад', '2012-08-10 09:30')[0];
    $later = Chrono::ru()->parseText('через 3 недели', '2012-08-10 09:30')[0];
    $within = Chrono::ru()->parseText('в течение 1 месяца', '2012-08-10 09:30')[0];
    $thisWeek = Chrono::ru()->parseText('на этой неделе', '2017-11-19 12:00')[0];
    $lastWeek = Chrono::ru()->parseText('на прошлой неделе', '2016-10-01 12:00')[0];
    $nextWeek = Chrono::ru()->parseText('на следующей неделе', '2016-10-01 12:00')[0];
    $nextQuarter = Chrono::ru()->parseText('в следующем квартале', '2016-10-01 12:00')[0];
    $lastYear = Chrono::ru()->parseText('в прошлом году', '2016-10-01 12:00')[0];
    $nextYear = Chrono::ru()->parseText('в следующем году', '2016-10-01 12:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/RUWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/RUWeekdayParser')
        ->and($timeWithSeconds->index)->toBe(0)
        ->and($timeWithSeconds->text)->toBe('20:32:13')
        ->and($timeWithSeconds->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/RUTimeExpressionParser')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morningTime->index)->toBe(0)
        ->and($morningTime->text)->toBe('в 11 утра')
        ->and($morningTime->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningTime->index)->toBe(0)
        ->and($eveningTime->text)->toBe('в 11 вечера')
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($casualHour->index)->toBe(0)
        ->and($casualHour->text)->toBe('в 1')
        ->and($casualHour->start->get('hour'))->toBe(1)
        ->and($casualNoon->index)->toBe(0)
        ->and($casualNoon->text)->toBe('в 12')
        ->and($casualNoon->start->get('hour'))->toBe(12)
        ->and($casualDotted->index)->toBe(0)
        ->and($casualDotted->text)->toBe('в 12.30')
        ->and($casualDotted->start->get('hour'))->toBe(12)
        ->and($casualDotted->start->get('minute'))->toBe(30)
        ->and($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/RUTimeUnitAgoFormatParser')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-31 09:30:00')
        ->and($later->start->tags())->toContain('parser/RUTimeUnitCasualRelativeFormatParser')
        ->and($within->start->date()->toDateTimeString())->toBe('2012-09-10 09:30:00')
        ->and($within->start->tags())->toContain('parser/RUTimeUnitWithinFormatParser')
        ->and($thisWeek->start->date()->toDateTimeString())->toBe('2017-11-19 12:00:00')
        ->and($thisWeek->start->tags())->toContain('parser/RURelativeDateFormatParser')
        ->and($lastWeek->start->date()->toDateTimeString())->toBe('2016-09-24 12:00:00')
        ->and($nextWeek->start->date()->toDateTimeString())->toBe('2016-10-08 12:00:00')
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00')
        ->and($lastYear->start->date()->toDateTimeString())->toBe('2015-10-01 12:00:00')
        ->and($nextYear->start->date()->toDateTimeString())->toBe('2017-10-01 12:00:00')
        ->and(Chrono::ru()->parseText('Температура 101 градусов!', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Это в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('Это в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::ru()->parseText('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 101 стул!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('Это в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('7-730', '2012-08-10'))->toBe([]);
});

it('merges russian dates with times and ranges', function () {
    $dateTime = Chrono::ru()->parseText('10 августа 2012 в 6:30 вечера', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parseText('10 августа - 12 августа', '2012-08-10 09:30')[0];

    expect($dateTime->text)->toBe('10 августа 2012 в 6:30 вечера')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->tags())->toContain('refiner/mergeDateRange');
});

it('parses ukrainian casual dates and times', function () {
    $tomorrow = Chrono::uk()->parseText('завтра', '2012-08-10 17:10')[0];
    $afterAfterTomorrow = Chrono::uk()->parseText('післяпіслязавтра', '2012-08-10 17:10')[0];
    $beforeYesterday = Chrono::uk()->parseText('позавчора', '2012-08-10 17:10')[0];
    $beforeBeforeYesterday = Chrono::uk()->parseText('позапозавчора', '2012-08-10 17:10')[0];
    $now = Chrono::uk()->parseText('зараз', '2012-08-10 08:09:10.011')[0];
    $previousNight = Chrono::uk()->parseText('минулої ночі', '2012-08-10 08:09:10')[0];
    $earlyPreviousNight = Chrono::uk()->parseText('минулої ночі', '2012-08-10 02:09:10')[0];
    $evening = Chrono::uk()->parseText('ввечері', '2012-08-10 09:30')[0];
    $casualRange = Chrono::uk()->parseText('Подія від сьогодні і до післязавтра', '2012-08-04 12:00')[0];
    $dashRange = Chrono::uk()->parseText('Подія сьогодні-завтра', '2012-08-10 12:00')[0];
    $tomorrowMorning = Chrono::uk()->parseText('Дедлайн завтра вранці', '2012-09-10 14:00')[0];

    expect($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 17:10:00')
        ->and($tomorrow->start->tags())->toContain('parser/UKCasualDateParser')
        ->and($afterAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-13 17:10:00')
        ->and($beforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 17:10:00')
        ->and($beforeYesterday->start->tags())->toContain('parser/UKCasualDateParser')
        ->and($beforeBeforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-07 17:10:00')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->isCertain('millisecond'))->toBeTrue()
        ->and($previousNight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($earlyPreviousNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($evening->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($evening->start->tags())->toContain('parser/UKCasualTimeParser')
        ->and($casualRange->text)->toBe('від сьогодні і до післязавтра')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($dashRange->text)->toBe('сьогодні-завтра')
        ->and($dashRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dashRange->end?->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($tomorrowMorning->text)->toBe('завтра вранці')
        ->and($tomorrowMorning->start->date()->toDateTimeString())->toBe('2012-09-11 06:00:00')
        ->and(Chrono::uk()->parseText('несьогодні', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('звтра', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('ввчора', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('січен', '2012-08-10'))->toBe([]);
});

it('parses ukrainian month name dates and ranges', function () {
    $date = Chrono::uk()->parseText('10 серпня 2012', '2012-08-10 09:30')[0];
    $abbreviatedYear = Chrono::uk()->parseText('сер 96', '2012-08-10 09:30')[0];
    $range = Chrono::uk()->parseText('10-12 серпня', '2012-08-10 09:30')[0];
    $month = Chrono::uk()->parseText('серпень 2012', '2012-08-10 09:30')[0];

    expect($date->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->tags())->toContain('parser/UKMonthNameLittleEndianParser')
        ->and($abbreviatedYear->text)->toBe('сер 96')
        ->and($abbreviatedYear->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->start->tags())->toContain('parser/UKMonthNameLittleEndianParser')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/UKMonthNameParser');
});

it('parses ukrainian weekdays times and relative durations', function () {
    $weekday = Chrono::uk()->parseText('середа', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::uk()->parseText('наступний понеділок', '2012-08-10 09:30')[0];
    $time = Chrono::uk()->parseText('о 6:30 вечора', '2012-08-10 09:30')[0];
    $fullTime = Chrono::uk()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $timeRange = Chrono::uk()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morning = Chrono::uk()->parseText('об 11 ранку', '2016-10-01 08:00')[0];
    $evening = Chrono::uk()->parseText('в 11 вечора', '2016-10-01 08:00')[0];
    $morningRange = Chrono::uk()->parseText('з 10 до 11 ранку', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::uk()->parseText('із 10 до 11 вечора', '2016-10-01 08:00')[0];
    $casualHour = Chrono::ukrainian()->parseText('в 1', '2016-10-01 08:00')[0];
    $casualNoon = Chrono::ukrainian()->parseText('о 12', '2016-10-01 08:00')[0];
    $casualDotted = Chrono::ukrainian()->parseText('в 12.30', '2016-10-01 08:00')[0];
    $thisWeek = Chrono::uk()->parseText('на цьому тижні', '2017-11-19 12:00')[0];
    $pastWeek = Chrono::uk()->parseText('на минулому тижні', '2016-10-01 12:00')[0];
    $nextYear = Chrono::uk()->parseText('наступного року', '2016-10-01 12:00')[0];
    $ago = Chrono::uk()->parseText('2 дні тому', '2012-08-10 09:30')[0];
    $halfHour = Chrono::uk()->parseText('через півгодини', '2016-10-01 12:00')[0];
    $later = Chrono::uk()->parseText('через 3 тижні', '2012-08-10 09:30')[0];
    $within = Chrono::uk()->parseText('протягом 1 місяця', '2012-08-10 09:30')[0];
    $withinMinute = Chrono::uk()->parseText('буде зроблено протягом хвилини', '2012-08-10 00:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/UKTimeExpressionParser')
        ->and($fullTime->text)->toBe('20:32:13')
        ->and($fullTime->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morning->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($evening->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($casualHour->index)->toBe(0)
        ->and($casualHour->text)->toBe('в 1')
        ->and($casualHour->start->get('hour'))->toBe(1)
        ->and($casualNoon->index)->toBe(0)
        ->and($casualNoon->text)->toBe('о 12')
        ->and($casualNoon->start->get('hour'))->toBe(12)
        ->and($casualDotted->index)->toBe(0)
        ->and($casualDotted->text)->toBe('в 12.30')
        ->and($casualDotted->start->get('hour'))->toBe(12)
        ->and($casualDotted->start->get('minute'))->toBe(30)
        ->and($thisWeek->start->date()->toDateTimeString())->toBe('2017-11-19 12:00:00')
        ->and($pastWeek->start->date()->toDateTimeString())->toBe('2016-09-24 12:00:00')
        ->and($nextYear->start->date()->toDateTimeString())->toBe('2017-10-01 12:00:00')
        ->and($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/UKTimeUnitAgoFormatParser')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2016-10-01 12:30:00')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-31 09:30:00')
        ->and($later->start->tags())->toContain('parser/UKTimeUnitCasualRelativeFormatParser')
        ->and($within->start->date()->toDateTimeString())->toBe('2012-09-10 09:30:00')
        ->and($within->start->tags())->toContain('parser/UKTimeUnitWithinFormatParser')
        ->and($withinMinute->start->date()->toDateTimeString())->toBe('2012-08-10 00:01:00')
        ->and(Chrono::uk()->parseText('Температура 101 градусів!', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Температура 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('2020  ', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 101,194 телефон!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 101 стіл!', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('2020', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10.1 - 10.12', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10 - 10.1', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('Це в 10 - 20', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('7-730', '2012-08-10'))->toBe([]);
});

it('merges ukrainian dates with times and ranges', function () {
    $dateTime = Chrono::uk()->parseText('10 серпня 2012 о 6:30 вечора', '2012-08-10 09:30')[0];
    $commaTime = Chrono::uk()->parseText('24го жовтня, 9:00', '2017-07-07 15:00')[0];
    $forwardRangeTime = Chrono::uk()->parseText('22-23 лют в 7', '2016-03-15', ['forwardDate' => true])[0];
    $range = Chrono::uk()->parseText('10 серпня - 12 серпня', '2012-08-10 09:30')[0];
    $crossMonthWithYear = Chrono::uk()->parseText('10 серпня - 12 вересня 2013', '2012-08-10 09:30')[0];

    expect($dateTime->text)->toBe('10 серпня 2012 о 6:30 вечора')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($commaTime->text)->toBe('24го жовтня, 9:00')
        ->and($commaTime->start->date()->toDateTimeString())->toBe('2017-10-24 09:00:00')
        ->and($forwardRangeTime->text)->toBe('22-23 лют в 7')
        ->and($forwardRangeTime->start->date()->toDateTimeString())->toBe('2017-02-22 07:00:00')
        ->and($forwardRangeTime->end?->date()->toDateTimeString())->toBe('2017-02-23 07:00:00')
        ->and($range->text)->toBe('10 серпня - 12 серпня')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->tags())->toContain('refiner/mergeDateRange')
        ->and($crossMonthWithYear->text)->toBe('10 серпня - 12 вересня 2013')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00');
});
