<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese casual relative time unit expressions', function () {
    $vietnamese = Chrono::vi();
    $yesterdayMorning = $vietnamese->parse('hôm qua lúc 7 giờ', '2012-08-10 06:00')[0];
    $tomorrowAfternoon = $vietnamese->parse('ngày mai lúc 15 giờ', '2012-08-10 12:00')[0];
    $todayMorning = $vietnamese->parse('hôm nay buổi sáng', '2012-08-10 12:00')[0];
    $lastMonth = $vietnamese->parse('tháng trước', '2012-08-10 12:00')[0];
    $nextYear = $vietnamese->parse('năm sau', '2012-08-10 12:00')[0];
    $lastWeek = $vietnamese->parse('tuần trước', '2012-08-10 12:00')[0];
    $twoWeeksAgo = $vietnamese->parse('2 tuần trước', '2012-08-10 12:00')[0];
    $nextWeek = $vietnamese->parse('tuần sau', '2012-08-10 09:30')[0];

    expect($yesterdayMorning->start->date()->toDateTimeString())->toBe('2012-08-09 07:00:00')
        ->and($tomorrowAfternoon->start->get('day'))->toBe(11)
        ->and($tomorrowAfternoon->start->get('hour'))->toBe(15)
        ->and($todayMorning->start->get('day'))->toBe(10)
        ->and($todayMorning->start->get('hour'))->toBe(9)
        ->and($lastMonth->start->get('year'))->toBe(2012)
        ->and($lastMonth->start->get('month'))->toBe(7)
        ->and($nextYear->start->get('year'))->toBe(2013)
        ->and($lastWeek->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($twoWeeksAgo->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($nextWeek->start->date()->toDateTimeString())->toBe('2012-08-17 09:30:00');
});
