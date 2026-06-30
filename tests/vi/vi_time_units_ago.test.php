<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese ago time unit expressions', function () {
    $vietnamese = Chrono::vi();
    $result = $vietnamese->parseText('2 ngày trước', '2012-08-10 09:30')[0];
    $prefixed = $vietnamese->parseText('Sự kiện 3 ngày trước.', '2012-08-10 12:00')[0];
    $weeks = $vietnamese->parseText('2 tuần trước', '2012-08-10 12:00')[0];
    $months = $vietnamese->parseText('3 tháng trước', '2012-08-10 12:00')[0];
    $years = $vietnamese->parseText('5 năm trước', '2012-08-10 12:00')[0];
    $pastMonth = $vietnamese->parseText('1 tháng qua', '2012-08-10 12:00')[0];
    $wordWeeks = $vietnamese->parseText('hai tuần trước', '2012-08-10 12:00')[0];
    $wordDays = $vietnamese->parseText('ba ngày trước', '2012-08-10 12:00')[0];
    $wordMonth = $vietnamese->parseText('một tháng qua', '2012-08-10 12:00')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($prefixed->index)->toBe(8)
        ->and($prefixed->text)->toBe('3 ngày trước')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-07 12:00:00')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($months->start->date()->toDateTimeString())->toBe('2012-05-10 12:00:00')
        ->and($years->start->date()->toDateTimeString())->toBe('2007-08-10 12:00:00')
        ->and($pastMonth->start->date()->toDateTimeString())->toBe('2012-07-10 12:00:00')
        ->and($wordWeeks->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($wordDays->start->date()->toDateTimeString())->toBe('2012-08-07 12:00:00')
        ->and($wordMonth->start->date()->toDateTimeString())->toBe('2012-07-10 12:00:00');
});
