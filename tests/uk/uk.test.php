<?php

use DirectoryTree\Chrono\Chrono;

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
