<?php

use DirectoryTree\Chrono\Chrono;

it('parses ukrainian month name dates and ranges', function () {
    $numericDate = Chrono::uk()->parseText('10.08.2012', '2012-08-10 12:00')[0];
    $date = Chrono::uk()->parseText('10 серпня 2012', '2012-08-10 09:30')[0];
    $abbreviatedOrdinalYear = Chrono::uk()->parseText('3 лют 82', '2012-08-10 09:30')[0];
    $prefixedDate = Chrono::uk()->parseText('Дедлайн 10 серпня', '2012-08-10 09:30')[0];
    $weekdayDate = Chrono::uk()->parseText('Дедлайн Четвер, 10 січня', '2012-08-10 09:30')[0];
    $abbreviatedMonthYear = Chrono::uk()->parseText('сер 96', '2012-08-10 09:30')[0];
    $range = Chrono::uk()->parseText('10-12 серпня', '2012-08-10 09:30')[0];
    $spacedRange = Chrono::uk()->parseText('10 - 22 серпня 2012', '2012-08-10 09:30')[0];
    $prepositionRange = Chrono::uk()->parseText('із 10 по 22 серпня 2012', '2012-08-10 09:30')[0];
    $crossMonth = Chrono::uk()->parseText('10 серпня - 12 вересня', '2012-08-10 09:30')[0];
    $crossMonthWithYear = Chrono::uk()->parseText('10 серпня - 12 вересня 2013', '2012-08-10 09:30')[0];
    $dateTime = Chrono::uk()->parseText('5 травня 12:00', '2012-08-10 09:30')[0];
    $ordinalDate = Chrono::uk()->parseText('п\'яте травня', '2012-08-10 09:30')[0];
    $compoundOrdinalDate = Chrono::uk()->parseText('двадцять п\'яте травня', '2012-02-10 09:30')[0];
    $dateFollowedByTime = Chrono::uk()->parseText('24го жовтня, 9:00', '2017-07-07 15:00')[0];
    $abbreviatedYear = Chrono::uk()->parseText('03 сер 96', '2012-08-10 09:30')[0];
    $month = Chrono::uk()->parseText('серпень 2012', '2012-08-10 09:30')[0];

    expect($numericDate->index)->toBe(0)
        ->and($numericDate->text)->toBe('10.08.2012')
        ->and($numericDate->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->index)->toBe(0)
        ->and($date->text)->toBe('10 серпня 2012')
        ->and($date->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->tags())->toContain('parser/UKMonthNameLittleEndianParser')
        ->and($abbreviatedOrdinalYear->text)->toBe('3 лют 82')
        ->and($abbreviatedOrdinalYear->start->date()->toDateTimeString())->toBe('1982-02-03 12:00:00')
        ->and($prefixedDate->text)->toBe('10 серпня')
        ->and($prefixedDate->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($weekdayDate->text)->toBe('Четвер, 10 січня')
        ->and($weekdayDate->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($abbreviatedMonthYear->text)->toBe('сер 96')
        ->and($abbreviatedMonthYear->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->start->tags())->toContain('parser/UKMonthNameLittleEndianParser')
        ->and($spacedRange->text)->toBe('10 - 22 серпня 2012')
        ->and($spacedRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($spacedRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($prepositionRange->text)->toBe('із 10 по 22 серпня 2012')
        ->and($prepositionRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prepositionRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($crossMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($crossMonth->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($dateTime->text)->toBe('5 травня 12:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($ordinalDate->text)->toBe('п\'яте травня')
        ->and($ordinalDate->start->date()->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($compoundOrdinalDate->text)->toBe('двадцять п\'яте травня')
        ->and($compoundOrdinalDate->start->date()->toDateTimeString())->toBe('2012-05-25 12:00:00')
        ->and($dateFollowedByTime->text)->toBe('24го жовтня, 9:00')
        ->and($dateFollowedByTime->start->date()->toDateTimeString())->toBe('2017-10-24 09:00:00')
        ->and($abbreviatedYear->text)->toBe('03 сер 96')
        ->and($abbreviatedYear->start->date()->toDateTimeString())->toBe('1996-08-03 12:00:00')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/UKMonthNameParser');
});

it('parses ukrainian month name dates with separators', function () {
    expect(Chrono::uk()->parseText('10-серпня 2012', '2012-08-08')[0]->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and(Chrono::uk()->parseText('10-серпня-2012', '2012-08-08')[0]->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and(Chrono::uk()->parseText('10/серпня 2012', '2012-08-08')[0]->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and(Chrono::uk()->parseText('10/серпня/2012', '2012-08-08')[0]->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('honors ukrainian forward dates for month name ranges', function () {
    $range = Chrono::ukrainian()->parseText('22-23 лют в 7', '2016-03-15', ['forwardDate' => true])[0];

    expect($range->index)->toBe(0)
        ->and($range->text)->toBe('22-23 лют в 7')
        ->and($range->start->date()->toDateTimeString())->toBe('2017-02-22 07:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2017-02-23 07:00:00');
});

it('does not parse impossible ukrainian month name dates in strict mode', function () {
    expect(Chrono::strictUkrainian()->parseText('32 серпня 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('29 лютого 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('32 серпня', '2012-08-10'))->toBe([])
        ->and(Chrono::strictUkrainian()->parseText('29 лютого', '2013-08-10'))->toBe([]);
});
