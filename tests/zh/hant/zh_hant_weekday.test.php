<?php

use Chrono\Chrono;

it('parses traditional chinese weekdays', function () {
    $chinese = Chrono::zhHant();
    $thursday = $chinese->parseText('星期四', '2016-09-02')[0];
    $monday = $chinese->parseText('我週一要打遊戲', '2012-08-10')[0];
    $forwardThursday = $chinese->parseText('禮拜四 (forward dates only)', '2016-09-02', ['forwardDate' => true])[0];
    $sunday = $chinese->parseText('禮拜日', '2016-09-02')[0];
    $lastWednesday = $chinese->parseText('雞上個禮拜三全部都係雞', '2016-09-02')[0];
    $nextSunday = $chinese->parseText('雞下星期天全部都係雞', '2016-09-02')[0];
    $thisMonday = $chinese->parseText('我這個星期一要打遊戲', '2012-08-10')[0];
    $weekdayRange = $chinese->parseText('星期六-星期一', '2016-09-02', ['forwardDate' => true])[0];

    expect($thursday->text)->toBe('星期四')
        ->and($thursday->start->date()->toDateTimeString())->toBe('2016-09-01 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->isCertain('day'))->toBeFalse()
        ->and($thursday->start->isCertain('month'))->toBeFalse()
        ->and($thursday->start->isCertain('year'))->toBeFalse()
        ->and($thursday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->text)->toBe('週一')
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($monday->start->tags())->toContain('parser/ZHHantWeekdayParser')
        ->and($forwardThursday->text)->toBe('禮拜四')
        ->and($forwardThursday->start->date()->toDateTimeString())->toBe('2016-09-08 12:00:00')
        ->and($sunday->text)->toBe('禮拜日')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2016-09-04 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($lastWednesday->text)->toBe('上個禮拜三')
        ->and($lastWednesday->start->date()->toDateTimeString())->toBe('2016-08-24 12:00:00')
        ->and($lastWednesday->start->get('weekday'))->toBe(3)
        ->and($nextSunday->text)->toBe('下星期天')
        ->and($nextSunday->start->date()->toDateTimeString())->toBe('2016-09-04 12:00:00')
        ->and($thisMonday->text)->toBe('這個星期一')
        ->and($thisMonday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00')
        ->and($weekdayRange->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('weekday'))->toBeTrue()
        ->and($weekdayRange->end?->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('weekday'))->toBeTrue();
});
