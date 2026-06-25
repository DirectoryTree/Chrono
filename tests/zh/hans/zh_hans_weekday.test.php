<?php

use Chrono\Chrono;

it('parses simplified chinese weekdays', function () {
    $weekday = Chrono::zhHans()->parseText('下个星期一', '2012-08-10')[0];
    $weekdayRange = Chrono::zhHans()->parseText('星期六至星期一', '2016-09-02', ['forwardDate' => true])[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ZHHansRelationWeekdayParser')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00');
});
