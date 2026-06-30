<?php

use DirectoryTree\Chrono\Chrono;

it('parses russian month name dates and ranges', function () {
    $numericDate = Chrono::ru()->parseText('10.08.2012', '2012-08-10 12:00')[0];
    $date = Chrono::ru()->parseText('10 августа 2012', '2012-08-10 09:30')[0];
    $ordinalAbbreviatedYear = Chrono::ru()->parseText('третье фев 82', '2012-08-10 09:30')[0];
    $prefixedDate = Chrono::ru()->parseText('Дедлайн 10 августа', '2012-08-10 09:30')[0];
    $weekdayDate = Chrono::ru()->parseText('Дедлайн Четверг, 10 января', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parseText('10-12 августа', '2012-08-10 09:30')[0];
    $spacedRange = Chrono::ru()->parseText('10 - 22 августа 2012', '2012-08-10 09:30')[0];
    $prepositionRange = Chrono::ru()->parseText('с 10 по 22 августа 2012', '2012-08-10 09:30')[0];
    $crossMonth = Chrono::ru()->parseText('10 августа - 12 сентября', '2012-08-10 09:30')[0];
    $crossMonthWithYear = Chrono::ru()->parseText('10 августа - 12 сентября 2013', '2012-08-10 09:30')[0];
    $dateTime = Chrono::ru()->parseText('5 мая 12:00', '2012-08-10 09:30')[0];
    $ordinalDate = Chrono::ru()->parseText('двадцать пятое мая', '2012-02-10 09:30')[0];
    $ordinalDateWithYear = Chrono::ru()->parseText('двадцать пятое мая 2020 года', '2012-02-10 09:30')[0];
    $dateFollowedByTime = Chrono::ru()->parseText('24го октября, 9:00', '2017-07-07 15:00')[0];
    $abbreviatedYear = Chrono::ru()->parseText('03 авг 96', '2012-08-10 09:30')[0];
    $month = Chrono::ru()->parseText('август 2012', '2012-08-10 09:30')[0];

    expect($numericDate->index)->toBe(0)
        ->and($numericDate->text)->toBe('10.08.2012')
        ->and($numericDate->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->index)->toBe(0)
        ->and($date->text)->toBe('10 августа 2012')
        ->and($date->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->tags())->toContain('parser/RUMonthNameLittleEndianParser')
        ->and($ordinalAbbreviatedYear->text)->toBe('третье фев 82')
        ->and($ordinalAbbreviatedYear->start->date()->toDateTimeString())->toBe('1982-02-03 12:00:00')
        ->and($prefixedDate->text)->toBe('10 августа')
        ->and($prefixedDate->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($weekdayDate->text)->toBe('Четверг, 10 января')
        ->and($weekdayDate->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->start->tags())->toContain('parser/RUMonthNameLittleEndianParser')
        ->and($spacedRange->text)->toBe('10 - 22 августа 2012')
        ->and($spacedRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($spacedRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($prepositionRange->text)->toBe('с 10 по 22 августа 2012')
        ->and($prepositionRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prepositionRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($crossMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($crossMonth->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($dateTime->text)->toBe('5 мая 12:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($ordinalDate->text)->toBe('двадцать пятое мая')
        ->and($ordinalDate->start->date()->toDateTimeString())->toBe('2012-05-25 12:00:00')
        ->and($ordinalDateWithYear->text)->toBe('двадцать пятое мая 2020 года')
        ->and($ordinalDateWithYear->start->date()->toDateTimeString())->toBe('2020-05-25 12:00:00')
        ->and($dateFollowedByTime->text)->toBe('24го октября, 9:00')
        ->and($dateFollowedByTime->start->date()->toDateTimeString())->toBe('2017-10-24 09:00:00')
        ->and($abbreviatedYear->text)->toBe('03 авг 96')
        ->and($abbreviatedYear->start->date()->toDateTimeString())->toBe('1996-08-03 12:00:00')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/RUMonthNameParser');
});

it('parses russian month name dates with separators', function () {
    $hyphenated = Chrono::ru()->parseText('10-августа 2012', '2012-08-08')[0];
    $hyphenatedYear = Chrono::ru()->parseText('10-августа-2012', '2012-08-08')[0];
    $slashed = Chrono::ru()->parseText('10/августа 2012', '2012-08-08')[0];
    $slashedYear = Chrono::ru()->parseText('10/августа/2012', '2012-08-08')[0];

    expect($hyphenated->text)->toBe('10-августа 2012')
        ->and($hyphenated->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($hyphenatedYear->text)->toBe('10-августа-2012')
        ->and($hyphenatedYear->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($slashed->text)->toBe('10/августа 2012')
        ->and($slashed->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($slashedYear->text)->toBe('10/августа/2012')
        ->and($slashedYear->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('honors russian forward dates for month name ranges', function () {
    $range = Chrono::russian()->parseText('22-23 фев в 7', '2016-03-15', ['forwardDate' => true])[0];

    expect($range->index)->toBe(0)
        ->and($range->text)->toBe('22-23 фев в 7')
        ->and($range->start->date()->toDateTimeString())->toBe('2017-02-22 07:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2017-02-23 07:00:00');
});

it('does not parse impossible russian month name dates in strict mode', function () {
    expect(Chrono::strictRussian()->parseText('32 августа 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('29 февраля 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('32 августа', '2012-08-10'))->toBe([])
        ->and(Chrono::strictRussian()->parseText('29 февраля', '2013-08-10'))->toBe([]);
});
