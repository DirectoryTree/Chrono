<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese forward dates', function () {
    $vietnamese = Chrono::vi();
    $result = $vietnamese->parse('ngày 15 tháng 3', '2012-08-10 12:00', ['forwardDate' => true])[0];
    $timeOnly = $vietnamese->parse('7 giờ sáng', '2012-08-10 08:00', ['forwardDate' => true])[0];
    $weekday = $vietnamese->parse('thứ hai', '2012-08-14 12:00', ['forwardDate' => true])[0];
    $slashDate = $vietnamese->parse('15/3', '2012-08-10 12:00', ['forwardDate' => true])[0];
    $sameWeekday = $vietnamese->parse('thứ năm', '2012-08-09 12:00', ['forwardDate' => true])[0];
    $month = $vietnamese->parse('tháng 3', '2012-08-10 12:00', ['forwardDate' => true])[0];

    expect($result->start->date()->toDateTimeString())->toBe('2013-03-15 12:00:00')
        ->and($result->start->isCertain('year'))->toBeFalse()
        ->and($timeOnly->start->date()->toDateTimeString())->toBe('2012-08-11 07:00:00')
        ->and($weekday->start->get('weekday'))->toBe(1)
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($slashDate->start->date()->toDateTimeString())->toBe('2013-03-15 12:00:00')
        ->and($sameWeekday->start->get('weekday'))->toBe(4)
        ->and($sameWeekday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($month->start->get('month'))->toBe(3)
        ->and($month->start->get('year'))->toBe(2013);
});
