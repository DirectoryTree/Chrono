<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese within time unit expressions', function () {
    $vietnamese = Chrono::vi();
    $result = $vietnamese->parseText('trong 5 ngày', '2012-08-10')[0];
    $days = $vietnamese->parseText('trong 3 ngày', '2012-08-10 12:00')[0];
    $weeks = $vietnamese->parseText('Hoàn thành trong 2 tuần.', '2012-08-10 12:00')[0];
    $months = $vietnamese->parseText('trong vòng 3 tháng', '2012-08-10 12:00')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($days->text)->toBe('trong 3 ngày')
        ->and($days->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($months->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00');
});
