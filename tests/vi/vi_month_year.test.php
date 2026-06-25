<?php

use Chrono\Chrono;

it('parses vietnamese month year expressions', function () {
    $month = Chrono::vi()->parseText('tháng chạp năm 1975', '2012-08-10')[0];
    $numberedMonth = Chrono::vi()->parseText('tháng 4 năm 1975', '2012-08-10')[0];
    $slashMonth = Chrono::vi()->parseText('tháng 3/1975', '2012-08-10')[0];

    expect($month->start->date()->toDateTimeString())->toBe('1975-12-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/VIMonthYearParser')
        ->and($numberedMonth->start->date()->toDateTimeString())->toBe('1975-04-01 12:00:00')
        ->and($slashMonth->start->date()->toDateTimeString())->toBe('1975-03-01 12:00:00');
});
