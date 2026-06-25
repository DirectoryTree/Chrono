<?php

use Chrono\Chrono;

it('parses vietnamese date ranges', function () {
    $vietnamese = Chrono::vi();
    $range = $vietnamese->parseText('30/04/1975 - 01/05/1975', '2012-08-10')[0];
    $connectorRange = $vietnamese->parseText('từ ngày 5 tháng 8 đến ngày 10 tháng 8 năm 2012', '2012-08-10 12:00')[0];
    $emDashRange = $vietnamese->parseText('ngày 1 tháng 4 – ngày 30 tháng 4 năm 2000', '2012-08-10 12:00')[0];
    $hyphenRange = $vietnamese->parseText('ngày 3 tháng 9 - ngày 5 tháng 9 năm 1945', '2012-08-10 12:00')[0];
    $monthRange = $vietnamese->parseText('tháng 3 tới tháng 5 năm 1975', '2012-08-10 12:00')[0];
    $yearEndRange = $vietnamese->parseText('ngày 1 tháng 1 đến ngày 31 tháng 12 năm 2020', '2012-08-10 12:00')[0];

    expect($range->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('1975-05-01 12:00:00')
        ->and($range->tags())->toContain('refiner/mergeDateRange')
        ->and($connectorRange->start->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($connectorRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($emDashRange->start->get('day'))->toBe(1)
        ->and($emDashRange->start->get('month'))->toBe(4)
        ->and($emDashRange->end?->date()->toDateTimeString())->toBe('2000-04-30 12:00:00')
        ->and($hyphenRange->start->date()->toDateTimeString())->toBe('1945-09-03 12:00:00')
        ->and($hyphenRange->end?->date()->toDateTimeString())->toBe('1945-09-05 12:00:00')
        ->and($monthRange->start->get('month'))->toBe(3)
        ->and($monthRange->end?->get('month'))->toBe(5)
        ->and($monthRange->end?->get('year'))->toBe(1975)
        ->and($yearEndRange->start->date()->lt($yearEndRange->end?->date()))->toBeTrue();
});
