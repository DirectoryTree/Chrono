<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese month year expressions', function () {
    $vietnamese = Chrono::vi();
    $month = $vietnamese->parseText('tháng chạp năm 1975', '2012-08-10')[0];
    $numberedMonth = $vietnamese->parseText('tháng 4 năm 1975', '2012-08-10')[0];
    $oldMonth = $vietnamese->parseText('tháng 1 năm 1863', '2012-08-10')[0];
    $slashMonth = $vietnamese->parseText('tháng 3/1975', '2012-08-10')[0];
    $impliedYear = $vietnamese->parseText('tháng 3', '2012-08-10')[0];

    expect($month->start->date()->toDateTimeString())->toBe('1975-12-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/VIMonthYearParser')
        ->and($numberedMonth->text)->toBe('tháng 4 năm 1975')
        ->and($numberedMonth->start->date()->toDateTimeString())->toBe('1975-04-01 12:00:00')
        ->and($numberedMonth->start->isCertain('month'))->toBeTrue()
        ->and($numberedMonth->start->isCertain('year'))->toBeTrue()
        ->and($numberedMonth->start->isCertain('day'))->toBeFalse()
        ->and($oldMonth->start->date()->toDateTimeString())->toBe('1863-01-01 12:00:00')
        ->and($slashMonth->start->date()->toDateTimeString())->toBe('1975-03-01 12:00:00')
        ->and($impliedYear->start->get('month'))->toBe(3)
        ->and($impliedYear->start->get('year'))->toBe(2012)
        ->and($impliedYear->start->isCertain('year'))->toBeFalse()
        ->and($vietnamese->parseText('tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 0', '2012-08-10'))->toBe([]);
});
