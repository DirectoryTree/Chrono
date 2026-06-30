<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\De\DeChrono;
use DirectoryTree\Chrono\Locales\De\Parsers\DeCasualDateParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeCasualTimeParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeMonthNameParser;
use DirectoryTree\Chrono\Locales\De\Parsers\DeTimeUnitRelativeFormatParser;
use DirectoryTree\Chrono\Locales\De\Refiners\DeMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\De\Refiners\DeMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\En\EnChrono;
use DirectoryTree\Chrono\Locales\En\Parsers\EnSlashDateParser;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\En\Refiners\EnExtractYearSuffixRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeRelativeAfterDateRefiner;
use DirectoryTree\Chrono\Locales\En\Refiners\EnMergeRelativeFollowByDateRefiner;
use DirectoryTree\Chrono\Locales\Es\EsChrono;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Es\Parsers\EsMonthNameParser;
use DirectoryTree\Chrono\Locales\Fi\FiChrono;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiCasualDateParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiCasualTimeParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Fi\Parsers\FiTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Fi\Refiners\FiMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Fi\Refiners\FiMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\Fr\FrChrono;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrCasualDateParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrCasualTimeParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrMonthNameParser;
use DirectoryTree\Chrono\Locales\Fr\Parsers\FrTimeUnitRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Fr\Refiners\FrMergeDateRangeRefiner;
use DirectoryTree\Chrono\Locales\Fr\Refiners\FrMergeDateTimeRefiner;
use DirectoryTree\Chrono\Locales\It\ItChrono;
use DirectoryTree\Chrono\Locales\It\Parsers\ItCasualDateParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItCasualTimeParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItMonthNameParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\It\Parsers\ItTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Ja\JaChrono;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaCasualDateParser;
use DirectoryTree\Chrono\Locales\Ja\Parsers\JaStandardParser;
use DirectoryTree\Chrono\Locales\Ja\Refiners\JaMergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Locales\Nl\NlChrono;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualDateParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualDateTimeParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlCasualTimeParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlMonthNameParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\Nl\Parsers\NlTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtCasualDateParser;
use DirectoryTree\Chrono\Locales\Pt\Parsers\PtMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Pt\PtChrono;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuCasualDateParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuCasualTimeParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuMonthNameParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\Ru\Parsers\RuTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Ru\RuChrono;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvCasualDateParser;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Sv\SvChrono;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkCasualDateParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkCasualTimeParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkMonthNameLittleEndianParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkMonthNameParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkRelativeDateFormatParser;
use DirectoryTree\Chrono\Locales\Uk\Parsers\UkTimeUnitCasualRelativeFormatParser;
use DirectoryTree\Chrono\Locales\Uk\UkChrono;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViCasualDateParser;
use DirectoryTree\Chrono\Locales\Vi\Parsers\ViStandardParser;
use DirectoryTree\Chrono\Locales\Vi\ViChrono;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansCasualDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hans\Parsers\ZhHansDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantCasualDateParser;
use DirectoryTree\Chrono\Locales\Zh\Hant\Parsers\ZhHantDateParser;
use DirectoryTree\Chrono\Locales\Zh\ZhChrono;
use DirectoryTree\Chrono\Locales\Zh\ZhHansChrono;
use DirectoryTree\Chrono\Locales\Zh\ZhHantChrono;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Month;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Parsers\IsoFormatParser;
use DirectoryTree\Chrono\Parsers\SlashDateFormatParser;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Refiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use DirectoryTree\Chrono\Refiners\ExtractTimezoneOffsetRefiner;
use DirectoryTree\Chrono\Refiners\ForwardDateRefiner;
use DirectoryTree\Chrono\Refiners\MergeWeekdayComponentRefiner;
use DirectoryTree\Chrono\Refiners\OverlapRemovalRefiner;
use DirectoryTree\Chrono\Weekday;

it('exposes upstream enum values', function () {
    expect(Meridiem::AM->value)->toBe(0)
        ->and(Meridiem::PM->value)->toBe(1)
        ->and(Weekday::SUNDAY->value)->toBe(0)
        ->and(Weekday::MONDAY->value)->toBe(1)
        ->and(Weekday::SATURDAY->value)->toBe(6)
        ->and(Month::JANUARY->value)->toBe(1)
        ->and(Month::DECEMBER->value)->toBe(12);
});

it('exposes source-shaped public parsing entrypoints', function () {
    $default = Chrono::parseDate('7:00PM July 5th, 2020');
    $english = Chrono::en()->parseDateText('7:00PM July 5th, 2020');
    $strict = Chrono::strict()->parseDateText('7:00PM July 5th, 2020');
    $casual = Chrono::casual()->parseDateText('7:00PM July 5th, 2020');

    expect(new Chrono)->toBeInstanceOf(Chrono::class)
        ->and(Chrono::casual())->toBeInstanceOf(Chrono::class)
        ->and(Chrono::strict())->toBeInstanceOf(Chrono::class)
        ->and(Chrono::parse('7:00PM July 5th, 2020')[0])->toBeInstanceOf(ParsedResult::class)
        ->and($default)->toBeInstanceOf(CarbonImmutable::class)
        ->and($default?->toDateTimeString())->toBe('2020-07-05 19:00:00')
        ->and($english)->toBeInstanceOf(CarbonImmutable::class)
        ->and($english?->toDateTimeString())->toBe('2020-07-05 19:00:00')
        ->and($strict)->toBeInstanceOf(CarbonImmutable::class)
        ->and($strict?->toDateTimeString())->toBe('2020-07-05 19:00:00')
        ->and($casual)->toBeInstanceOf(CarbonImmutable::class)
        ->and($casual?->toDateTimeString())->toBe('2020-07-05 19:00:00');
});

it('exposes source-shaped strict locale configurations separately from PHP extensions', function () {
    $spanishParsers = array_map(fn (object $parser): string => $parser::class, EsChrono::createStrictConfiguration()->parsers());
    $spanishRefiners = array_map(fn (object $refiner): string => $refiner::class, EsChrono::createStrictConfiguration()->refiners());
    $germanParsers = array_map(fn (object $parser): string => $parser::class, DeChrono::createStrictConfiguration()->parsers());
    $germanRefiners = array_map(fn (object $refiner): string => $refiner::class, DeChrono::createStrictConfiguration()->refiners());
    $frenchParsers = array_map(fn (object $parser): string => $parser::class, FrChrono::createStrictConfiguration()->parsers());
    $frenchRefiners = array_map(fn (object $refiner): string => $refiner::class, FrChrono::createStrictConfiguration()->refiners());
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
    $refiners = array_map(fn (object $refiner): string => $refiner::class, EnChrono::createStrictConfiguration()->refiners());
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
    $finnishParsers = array_map(fn (object $parser): string => $parser::class, FiChrono::createStrictConfiguration()->parsers());
    $finnishRefiners = array_map(fn (object $refiner): string => $refiner::class, FiChrono::createStrictConfiguration()->refiners());
    $portugueseParsers = array_map(fn (object $parser): string => $parser::class, PtChrono::createStrictConfiguration()->parsers());
    $portugueseRefiners = array_map(fn (object $refiner): string => $refiner::class, PtChrono::createStrictConfiguration()->refiners());
    $swedishParsers = array_map(fn (object $parser): string => $parser::class, SvChrono::createStrictConfiguration()->parsers());
    $swedishRefiners = array_map(fn (object $refiner): string => $refiner::class, SvChrono::createStrictConfiguration()->refiners());

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
    $italianParsers = array_map(fn (object $parser): string => $parser::class, ItChrono::createStrictConfiguration()->parsers());
    $italianRefiners = array_map(fn (object $refiner): string => $refiner::class, ItChrono::createStrictConfiguration()->refiners());
    $dutchParsers = array_map(fn (object $parser): string => $parser::class, NlChrono::createStrictConfiguration()->parsers());
    $dutchRefiners = array_map(fn (object $refiner): string => $refiner::class, NlChrono::createStrictConfiguration()->refiners());
    $russianParsers = array_map(fn (object $parser): string => $parser::class, RuChrono::createStrictConfiguration()->parsers());
    $russianRefiners = array_map(fn (object $refiner): string => $refiner::class, RuChrono::createStrictConfiguration()->refiners());
    $ukrainianParsers = array_map(fn (object $parser): string => $parser::class, UkChrono::createStrictConfiguration()->parsers());
    $ukrainianRefiners = array_map(fn (object $refiner): string => $refiner::class, UkChrono::createStrictConfiguration()->refiners());
    $japaneseParsers = array_map(fn (object $parser): string => $parser::class, JaChrono::createStrictConfiguration()->parsers());
    $japaneseRefiners = array_map(fn (object $refiner): string => $refiner::class, JaChrono::createStrictConfiguration()->refiners());
    $vietnameseParsers = array_map(fn (object $parser): string => $parser::class, ViChrono::createStrictConfiguration()->parsers());
    $vietnameseRefiners = array_map(fn (object $refiner): string => $refiner::class, ViChrono::createStrictConfiguration()->refiners());

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

it('exposes source-shaped strict configurations for Chinese engines', function () {
    $chineseParsers = array_map(fn (object $parser): string => $parser::class, ZhChrono::createStrictConfiguration()->parsers());
    $chineseCasualParsers = array_map(fn (object $parser): string => $parser::class, ZhChrono::createCasualConfiguration()->parsers());
    $chineseRefiners = array_map(fn (object $refiner): string => $refiner::class, ZhChrono::createStrictConfiguration()->refiners());
    $hansParsers = array_map(fn (object $parser): string => $parser::class, ZhHansChrono::createStrictConfiguration()->parsers());
    $hansCasualParsers = array_map(fn (object $parser): string => $parser::class, ZhHansChrono::createCasualConfiguration()->parsers());
    $hansRefiners = array_map(fn (object $refiner): string => $refiner::class, ZhHansChrono::createStrictConfiguration()->refiners());
    $hantParsers = array_map(fn (object $parser): string => $parser::class, ZhHantChrono::createStrictConfiguration()->parsers());
    $hantCasualParsers = array_map(fn (object $parser): string => $parser::class, ZhHantChrono::createCasualConfiguration()->parsers());
    $hantRefiners = array_map(fn (object $refiner): string => $refiner::class, ZhHantChrono::createStrictConfiguration()->refiners());

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
    $germanParsers = array_map(fn (object $parser): string => $parser::class, DeChrono::createCasualConfiguration()->parsers());
    $germanRefiners = array_map(fn (object $refiner): string => $refiner::class, DeChrono::createCasualConfiguration()->refiners());
    $frenchParsers = array_map(fn (object $parser): string => $parser::class, FrChrono::createCasualConfiguration()->parsers());
    $frenchRefiners = array_map(fn (object $refiner): string => $refiner::class, FrChrono::createCasualConfiguration()->refiners());
    $finnishParsers = array_map(fn (object $parser): string => $parser::class, FiChrono::createCasualConfiguration()->parsers());
    $italianParsers = array_map(fn (object $parser): string => $parser::class, ItChrono::createCasualConfiguration()->parsers());
    $dutchParsers = array_map(fn (object $parser): string => $parser::class, NlChrono::createCasualConfiguration()->parsers());
    $russianParsers = array_map(fn (object $parser): string => $parser::class, RuChrono::createCasualConfiguration()->parsers());
    $ukrainianParsers = array_map(fn (object $parser): string => $parser::class, UkChrono::createCasualConfiguration()->parsers());

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

it('filters unlikely english second phrases', function () {
    expect(Chrono::parse('the second half', '2012-08-10'))
        ->toBe([]);
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
        ->and(Chrono::parse('02/29/2022', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('June 31, 2022', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('06/31/2022', '2012-08-10'))
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

it('parses upstream casual date references with source-shaped components', function () {
    $today = Chrono::parse('The Deadline is today', '2012-08-10 14:12')[0];
    $tomorrow = Chrono::parse('The Deadline is Tomorrow', '2012-08-10 17:10')[0];
    $tomorrowEarly = Chrono::parse('The Deadline is Tomorrow', '2012-08-10 01:00')[0];
    $yesterday = Chrono::parse('The Deadline was yesterday', '2012-08-10 12:00')[0];
    $lastNight = Chrono::parse('The Deadline was last night ', '2012-08-10 12:00')[0];
    $thisMorning = Chrono::parse('The Deadline was this morning ', '2012-08-10 12:00')[0];
    $thisAfternoon = Chrono::parse('The Deadline was this afternoon ', '2012-08-10 12:00')[0];
    $thisEvening = Chrono::parse('The Deadline was this evening ', '2012-08-10 12:00')[0];
    $middayMidnight = Chrono::parse('The Deadline is midnight ', '2012-08-10 12:00')[0];
    $pastMidnight = Chrono::parse('The Deadline was midnight ', '2012-08-10 01:00')[0];
    $forwardMidnight = Chrono::parse('The Deadline was midnight ', '2012-08-10 01:00', ['forwardDate' => true])[0];

    expect($today->index)->toBe(16)
        ->and($today->text)->toBe('today')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 14:12:00')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->index)->toBe(16)
        ->and($tomorrow->text)->toBe('Tomorrow')
        ->and($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 17:10:00')
        ->and($tomorrowEarly->start->date()->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($yesterday->index)->toBe(17)
        ->and($yesterday->text)->toBe('yesterday')
        ->and($yesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($lastNight->index)->toBe(17)
        ->and($lastNight->text)->toBe('last night')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($thisMorning->index)->toBe(17)
        ->and($thisMorning->text)->toBe('this morning')
        ->and($thisMorning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($thisAfternoon->index)->toBe(17)
        ->and($thisAfternoon->text)->toBe('this afternoon')
        ->and($thisAfternoon->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($thisEvening->index)->toBe(17)
        ->and($thisEvening->text)->toBe('this evening')
        ->and($thisEvening->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($middayMidnight->text)->toBe('midnight')
        ->and($middayMidnight->start->date()->toDateTimeString())->toBe('2012-08-11 00:00:00')
        ->and($pastMidnight->text)->toBe('midnight')
        ->and($pastMidnight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($pastMidnight->start->get('millisecond'))->toBe(0)
        ->and($forwardMidnight->text)->toBe('midnight')
        ->and($forwardMidnight->start->date()->toDateTimeString())->toBe('2012-08-11 00:00:00')
        ->and($forwardMidnight->start->get('millisecond'))->toBe(0);
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

it('builds parser and refiner configurations with a fluent pipeline API', function () {
    $slashDateParser = new EnSlashDateParser;
    $relativeParser = new EnTimeUnitCasualRelativeFormatParser;
    $overlapRemoval = new OverlapRemovalRefiner;
    $forwardDate = new ForwardDateRefiner;

    $configuration = Configuration::make()
        ->addParser($slashDateParser)
        ->prependParser($relativeParser)
        ->addRefiner($overlapRemoval)
        ->prependRefiner($forwardDate);

    expect($configuration->parsers())->toBe([$relativeParser, $slashDateParser])
        ->and($configuration->refiners())->toBe([$forwardDate, $overlapRemoval])
        ->and($configuration->hasParser(EnSlashDateParser::class))->toBeTrue()
        ->and($configuration->hasParser(EnTimeUnitCasualRelativeFormatParser::class))->toBeTrue()
        ->and($configuration->hasRefiner(OverlapRemovalRefiner::class))->toBeTrue()
        ->and($configuration->hasRefiner(ForwardDateRefiner::class))->toBeTrue();

    $withoutSlashDateParser = $configuration->removeParser(EnSlashDateParser::class);
    $withoutForwardDateRefiner = $configuration->removeRefiner(ForwardDateRefiner::class);

    expect($withoutSlashDateParser->parsers())->toBe([$relativeParser])
        ->and($withoutSlashDateParser->hasParser(EnSlashDateParser::class))->toBeFalse()
        ->and($withoutForwardDateRefiner->refiners())->toBe([$overlapRemoval])
        ->and($withoutForwardDateRefiner->hasRefiner(ForwardDateRefiner::class))->toBeFalse()
        ->and($configuration->parsers())->toBe([$relativeParser, $slashDateParser])
        ->and($configuration->refiners())->toBe([$forwardDate, $overlapRemoval]);
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
    $strictCustom = Chrono::strict()->withParser(new EnTimeUnitCasualRelativeFormatParser(allowAbbreviations: false));

    expect(Chrono::parseDate('next 5m', '2016-10-01 14:52')?->toDateTimeString())
        ->toBe('2016-10-01 14:57:00')
        ->and($custom->parseText('next 5m', '2016-10-01 14:52'))
        ->toBe([])
        ->and($custom->parseDateText('next 5 minutes', '2016-10-01 14:52')?->toDateTimeString())
        ->toBe('2016-10-01 14:57:00')
        ->and($strictCustom->parseText('-3y', '2016-10-01 12:00'))
        ->toBe([])
        ->and($strictCustom->parseText('last 2m', '2016-10-01 12:00'))
        ->toBe([])
        ->and($strictCustom->parseText('-2 hours 5 minutes', '2016-10-01 12:00')[0]->text)
        ->toBe('-2 hours 5 minutes')
        ->and($strictCustom->parseDateText('-2 hours 5 minutes', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-01 09:55:00');
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
