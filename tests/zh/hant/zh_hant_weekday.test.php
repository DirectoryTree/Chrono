<?php

use Chrono\Chrono;

it('parses traditional chinese weekdays', function () {
    $weekday = Chrono::zhHant()->parseText('下個星期一', '2012-08-10')[0];
    $weekdayRange = Chrono::zhHant()->parseText('星期六-星期一', '2016-09-02', ['forwardDate' => true])[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ZHHantRelationWeekdayParser')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00');
});
