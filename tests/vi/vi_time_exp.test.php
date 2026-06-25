<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses vietnamese time expressions', function () {
    $vietnamese = Chrono::vi();
    $hour = $vietnamese->parseText('Cuộc hẹn lúc 7 giờ.', '2012-08-10 12:00')[0];
    $morning = $vietnamese->parseText('7 giờ sáng', '2012-08-10 12:00')[0];
    $evening = $vietnamese->parseText('7 giờ tối', '2012-08-10 12:00')[0];
    $time = $vietnamese->parseText('lúc 7 giờ 30 phút', '2012-08-10 12:00')[0];
    $twentyFourHour = $vietnamese->parseText('vào 15 giờ 45 phút', '2012-08-10 12:00')[0];
    $colon = $vietnamese->parseText('Hẹn lúc 15:30.', '2012-08-10 12:00')[0];
    $dateTime = $vietnamese->parseText('ngày 30 tháng 4 năm 1975 lúc 11 giờ', '2012-08-10 12:00')[0];
    $afternoon = $vietnamese->parseText('3 giờ chiều', '2012-08-10 12:00')[0];
    $night = $vietnamese->parseText('10 giờ đêm', '2012-08-10 12:00')[0];
    $noon = $vietnamese->parseText('12 giờ trưa', '2012-08-10 12:00')[0];
    $midnight = $vietnamese->parseText('12 giờ sáng', '2012-08-10 12:00')[0];

    expect($hour->text)->toBe('lúc 7 giờ')
        ->and($hour->index)->toBe(9)
        ->and($hour->start->get('hour'))->toBe(7)
        ->and($hour->start->get('minute'))->toBe(0)
        ->and($morning->start->get('hour'))->toBe(7)
        ->and($morning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($evening->start->get('hour'))->toBe(19)
        ->and($time->start->get('hour'))->toBe(7)
        ->and($time->start->get('minute'))->toBe(30)
        ->and($twentyFourHour->start->get('hour'))->toBe(15)
        ->and($twentyFourHour->start->get('minute'))->toBe(45)
        ->and($colon->start->get('hour'))->toBe(15)
        ->and($colon->start->get('minute'))->toBe(30)
        ->and($dateTime->start->date()->toDateTimeString())->toBe('1975-04-30 11:00:00')
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($night->start->get('hour'))->toBe(22)
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($noon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($midnight->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($hour->start->isCertain('hour'))->toBeTrue()
        ->and($hour->start->isCertain('meridiem'))->toBeFalse()
        ->and($morning->start->isCertain('meridiem'))->toBeTrue();
});
