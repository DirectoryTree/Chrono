<?php

use Chrono\Chrono;

it('parses ukrainian weekdays', function () {
    $weekday = Chrono::uk()->parseText('середа', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::uk()->parseText('наступний понеділок', '2012-08-10 09:30')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/UKWeekdayParser');
});
