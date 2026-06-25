<?php

use Chrono\Chrono;

it('parses middle endian cross month ranges', function () {
    $result = Chrono::parse('August 10 - November 12', '2012-08-10')[0];
    $toRange = Chrono::parse('Aug 10 to Nov 12', '2012-08-10')[0];
    $nextYear = Chrono::parse('Aug 10 - Nov 12, 2013', '2012-08-10')[0];
    $previousYear = Chrono::parse('Aug 10 - Nov 12, 2011', '2012-08-10')[0];

    expect($result->text)->toBe('August 10 - November 12')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-11-12 12:00:00')
        ->and($toRange->text)->toBe('Aug 10 to Nov 12')
        ->and($toRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($toRange->end?->date()->toDateTimeString())->toBe('2012-11-12 12:00:00')
        ->and($nextYear->text)->toBe('Aug 10 - Nov 12, 2013')
        ->and($nextYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($nextYear->end?->date()->toDateTimeString())->toBe('2013-11-12 12:00:00')
        ->and($previousYear->text)->toBe('Aug 10 - Nov 12, 2011')
        ->and($previousYear->start->date()->toDateTimeString())->toBe('2011-08-10 12:00:00')
        ->and($previousYear->end?->date()->toDateTimeString())->toBe('2011-11-12 12:00:00');
});

it('parses middle endian dates and ranges with compact comma years', function () {
    $monthYear = Chrono::parse('She is getting married soon (July 2017).', '2012-08-10')[0];
    $monthOnly = Chrono::parse('She is leaving in August.', '2012-08-10')[0];
    $monthYearComma = Chrono::parse('I am arriving sometime in August, 2012, probably.', '2012-08-10')[0];
    $explicit = Chrono::parse('August 10, 2012', '2012-08-10')[0];
    $date = Chrono::parse('Published November 1,2001', '2012-08-10')[0];
    $range = Chrono::parse('174 November 1,2001- March 31,2002', '2012-08-10')[0];
    $shortYearWithComma = Chrono::parse('Aug 9, 96', '2012-08-10')[0];
    $shortYear = Chrono::parse('Aug 9 96', '2012-08-10')[0];

    expect($monthYear->text)->toBe('July 2017')
        ->and($monthYear->index)->toBe(29)
        ->and($monthYear->start->date()->toDateTimeString())->toBe('2017-07-01 12:00:00')
        ->and($monthOnly->text)->toBe('August')
        ->and($monthOnly->index)->toBe(18)
        ->and($monthOnly->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($monthYearComma->text)->toBe('August, 2012')
        ->and($monthYearComma->index)->toBe(26)
        ->and($monthYearComma->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($explicit->text)->toBe('August 10, 2012')
        ->and($explicit->start->get('year'))->toBe(2012)
        ->and($explicit->start->get('month'))->toBe(8)
        ->and($explicit->start->get('day'))->toBe(10)
        ->and($date->text)->toBe('November 1,2001')
        ->and($date->start->date()->toDateTimeString())->toBe('2001-11-01 12:00:00')
        ->and($date->tags())->toContain('parser/ENMonthNameMiddleEndianParser')
        ->and($range->text)->toBe('November 1,2001- March 31,2002')
        ->and($range->start->date()->toDateTimeString())->toBe('2001-11-01 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2002-03-31 12:00:00')
        ->and($shortYearWithComma->text)->toBe('Aug 9, 96')
        ->and($shortYearWithComma->start->date()->toDateTimeString())->toBe('1996-08-09 12:00:00')
        ->and($shortYear->text)->toBe('Aug 9 96')
        ->and($shortYear->start->date()->toDateTimeString())->toBe('1996-08-09 12:00:00')
        ->and(Chrono::parse('Jan 1 3000, 9:30', '2012-08-10')[0]->text)->toBe('Jan 1 3000, 9:30')
        ->and(Chrono::parseDate('Jan 1 3000, 9:30', '2012-08-10')?->format('Y-m-d H:i:s'))->toBe('3000-01-01 09:30:00');
});

it('skips year-like middle endian month dates for british english', function () {
    $middleEndian = Chrono::casual()->parseText('Dec. 21', '2024-01-10')[0];
    $littleEndian = Chrono::gb()->parseText('Dec. 21', '2024-01-10')[0];

    expect($middleEndian->text)->toBe('Dec. 21')
        ->and($middleEndian->start->date()->toDateTimeString())->toBe('2023-12-21 12:00:00')
        ->and($littleEndian->text)->toBe('Dec. 21')
        ->and($littleEndian->start->date()->toDateTimeString())->toBe('2021-12-01 12:00:00');
});
