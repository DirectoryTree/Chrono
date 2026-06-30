<?php

use DirectoryTree\Chrono\Chrono;

it('parses simplified chinese weekdays', function () {
    $chinese = Chrono::zhHans();
    $thursday = $chinese->parseText('星期四', '2016-09-02')[0];
    $monday = $chinese->parseText('我周一要打游戏', '2012-08-10')[0];
    $forwardThursday = $chinese->parseText('礼拜四 (forward dates only)', '2016-09-02', ['forwardDate' => true])[0];
    $sunday = $chinese->parseText('礼拜日', '2016-09-02')[0];
    $lastWednesday = $chinese->parseText('我上个礼拜三在打游戏', '2016-09-02')[0];
    $nextSunday = $chinese->parseText('我下星期天打游戏', '2016-09-02')[0];
    $thisMonday = $chinese->parseText('我这个星期一要打游戏', '2012-08-10')[0];
    $weekdayRange = $chinese->parseText('星期六至星期一', '2016-09-02', ['forwardDate' => true])[0];

    expect($thursday->text)->toBe('星期四')
        ->and($thursday->start->date()->toDateTimeString())->toBe('2016-09-01 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->isCertain('day'))->toBeFalse()
        ->and($thursday->start->isCertain('month'))->toBeFalse()
        ->and($thursday->start->isCertain('year'))->toBeFalse()
        ->and($thursday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->text)->toBe('周一')
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($monday->start->tags())->toContain('parser/ZHHansWeekdayParser')
        ->and($forwardThursday->text)->toBe('礼拜四')
        ->and($forwardThursday->start->date()->toDateTimeString())->toBe('2016-09-08 12:00:00')
        ->and($sunday->text)->toBe('礼拜日')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2016-09-04 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($lastWednesday->text)->toBe('上个礼拜三')
        ->and($lastWednesday->start->date()->toDateTimeString())->toBe('2016-08-24 12:00:00')
        ->and($lastWednesday->start->get('weekday'))->toBe(3)
        ->and($nextSunday->text)->toBe('下星期天')
        ->and($nextSunday->start->date()->toDateTimeString())->toBe('2016-09-04 12:00:00')
        ->and($thisMonday->text)->toBe('这个星期一')
        ->and($thisMonday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00');
});

it('parses simplified chinese weekday ranges with upstream separators', function () {
    $chinese = Chrono::zhHans();

    foreach (['星期六至星期一', '星期六到星期一', '星期六~星期一', '星期六～星期一', '星期六－星期一', '星期六ー星期一'] as $text) {
        $range = $chinese->parseText($text, '2016-09-02', ['forwardDate' => true])[0];

        expect($range->text)->toBe($text)
            ->and($range->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
            ->and($range->start->get('weekday'))->toBe(6)
            ->and($range->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00')
            ->and($range->end?->get('weekday'))->toBe(1);
    }
});
