<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses vietnamese casual date and time references', function () {
    $vietnamese = Chrono::vi();
    $now = $vietnamese->parse('bây giờ', '2012-08-10 09:30:45.123')[0];
    $nowAlternative = $vietnamese->parse('lúc này', '2012-08-10 08:09:10.011')[0];
    $today = $vietnamese->parse('hôm nay', '2012-08-10 09:30')[0];
    $prefixedToday = $vietnamese->parse('Cuộc họp hôm nay.', '2012-08-10 12:00')[0];
    $prefixedYesterday = $vietnamese->parse('Hội nghị hôm qua.', '2012-08-10 12:00')[0];
    $tomorrow = $vietnamese->parse('Lịch ngày mai.', '2012-08-10 12:00')[0];
    $dayBeforeYesterday = $vietnamese->parse('hôm kia', '2012-08-10 12:00')[0];
    $morning = $vietnamese->parse('buổi sáng', '2012-08-10 09:30')[0];
    $dateMorning = $vietnamese->parse('hôm nay buổi sáng', '2012-08-10 06:00')[0];
    $noon = $vietnamese->parse('buổi trưa', '2012-08-10 12:00')[0];
    $afternoon = $vietnamese->parse('buổi chiều', '2012-08-10 12:00')[0];
    $evening = $vietnamese->parse('buổi tối', '2012-08-10 12:00')[0];
    $night = $vietnamese->parse('buổi đêm', '2012-08-10 12:00')[0];
    $bareNight = $vietnamese->parse('đêm', '2012-08-10 12:00')[0];
    $midnight = $vietnamese->parse('nửa đêm', '2012-08-10 12:00')[0];
    $dawn = $vietnamese->parse('bình minh', '2012-08-10 12:00')[0];
    $earlyMorning = $vietnamese->parse('sáng sớm', '2012-08-10 12:00')[0];
    $todayAfternoon = $vietnamese->parse('hôm nay buổi chiều', '2012-08-10 12:00')[0];

    expect($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 09:30:45.123')
        ->and($now->start->tags())->toContain('parser/VICasualDateParser')
        ->and($nowAlternative->text)->toBe('lúc này')
        ->and($nowAlternative->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 09:30:00')
        ->and($today->start->isCertain('year'))->toBeTrue()
        ->and($today->start->isCertain('month'))->toBeTrue()
        ->and($today->start->isCertain('day'))->toBeTrue()
        ->and($today->start->isCertain('hour'))->toBeFalse()
        ->and($prefixedToday->index)->toBe(9)
        ->and($prefixedToday->text)->toBe('hôm nay')
        ->and($prefixedToday->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedYesterday->index)->toBe(9)
        ->and($prefixedYesterday->text)->toBe('hôm qua')
        ->and($prefixedYesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($vietnamese->date('hôm qua', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 09:30:00')
        ->and($vietnamese->date('hôm qua', '2012-08-01 12:00')?->toDateTimeString())->toBe('2012-07-31 12:00:00')
        ->and($tomorrow->index)->toBe(5)
        ->and($tomorrow->text)->toBe('ngày mai')
        ->and($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($vietnamese->date('ngày mai', '2012-08-31 12:00')?->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($vietnamese->date('ngày kia', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-12 09:30:00')
        ->and($dayBeforeYesterday->text)->toBe('hôm kia')
        ->and($dayBeforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($morning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($morning->start->tags())->toContain('parser/VICasualTimeParser')
        ->and($dateMorning->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($dateMorning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($dateMorning->tags())->toContain('refiner/mergeDateFollowedByTime')
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
        ->and($dawn->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($dawn->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($earlyMorning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($earlyMorning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($todayAfternoon->start->get('day'))->toBe(10)
        ->and($todayAfternoon->start->get('hour'))->toBe(15);
});
