<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses vietnamese casual time references', function () {
    $vietnamese = Chrono::vi();
    $morningTime = $vietnamese->parseText('7 giờ sáng', '2012-08-10 12:00')[0];
    $todayMorning = $vietnamese->parseText('hôm nay buổi sáng', '2012-08-10 06:00')[0];
    $noon = $vietnamese->parseText('buổi trưa', '2012-08-10 12:00')[0];
    $afternoon = $vietnamese->parseText('buổi chiều', '2012-08-10 12:00')[0];
    $evening = $vietnamese->parseText('buổi tối', '2012-08-10 12:00')[0];
    $night = $vietnamese->parseText('buổi đêm', '2012-08-10 12:00')[0];
    $bareNight = $vietnamese->parseText('đêm', '2012-08-10 12:00')[0];
    $midnight = $vietnamese->parseText('nửa đêm', '2012-08-10 12:00')[0];
    $dawn = $vietnamese->parseText('bình minh', '2012-08-10 12:00')[0];
    $earlyMorning = $vietnamese->parseText('sáng sớm', '2012-08-10 12:00')[0];
    $todayAfternoon = $vietnamese->parseText('hôm nay buổi chiều', '2012-08-10 12:00')[0];

    expect($morningTime->start->get('hour'))->toBe(7)
        ->and($morningTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($todayMorning->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($todayMorning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($todayMorning->start->tags())->toContain('parser/VICasualTimeParser')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($noon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($afternoon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($evening->start->get('hour'))->toBe(19)
        ->and($evening->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($night->start->get('hour'))->toBe(22)
        ->and($night->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($bareNight->start->get('hour'))->toBe(22)
        ->and($bareNight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($midnight->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($dawn->start->get('hour'))->toBe(6)
        ->and($dawn->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($earlyMorning->start->get('hour'))->toBe(6)
        ->and($earlyMorning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($todayAfternoon->start->get('day'))->toBe(10)
        ->and($todayAfternoon->start->get('hour'))->toBe(15);
});
