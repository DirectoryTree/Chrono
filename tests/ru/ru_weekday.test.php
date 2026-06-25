<?php

use Chrono\Chrono;

it('parses russian weekdays', function () {
    $weekday = Chrono::ru()->parseText('среда', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::ru()->parseText('следующий понедельник', '2012-08-10 09:30')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/RUWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/RUWeekdayParser');
});
