<?php

use Chrono\Chrono;

it('parses vietnamese later time unit expressions', function () {
    $vietnamese = Chrono::vi();
    $result = $vietnamese->parseText('3 ngày sau', '2012-08-10 09:30')[0];
    $prefixed = $vietnamese->parseText('Sự kiện 3 ngày sau.', '2012-08-10 12:00')[0];
    $weeks = $vietnamese->parseText('2 tuần nữa', '2012-08-10 12:00')[0];
    $months = $vietnamese->parseText('3 tháng tới', '2012-08-10 12:00')[0];
    $years = $vietnamese->parseText('10 năm sau', '2012-08-10 12:00')[0];
    $wordDays = $vietnamese->parseText('ba ngày sau', '2012-08-10 12:00')[0];
    $wordWeeks = $vietnamese->parseText('hai tuần nữa', '2012-08-10 12:00')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-13 09:30:00')
        ->and($prefixed->index)->toBe(12)
        ->and($prefixed->text)->toBe('3 ngày sau')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($months->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00')
        ->and($years->start->date()->toDateTimeString())->toBe('2022-08-10 12:00:00')
        ->and($wordDays->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($wordWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00');
});
