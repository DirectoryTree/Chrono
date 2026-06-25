<?php

use Chrono\Chrono;

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
