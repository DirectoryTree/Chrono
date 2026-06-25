<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses vietnamese casual time references', function () {
    $vietnamese = Chrono::vi();
    $morning = $vietnamese->parseText('buổi sáng', '2012-08-10 09:30')[0];
    $afternoon = $vietnamese->parseText('buổi chiều', '2012-08-10 12:00')[0];
    $midnight = $vietnamese->parseText('nửa đêm', '2012-08-10 12:00')[0];

    expect($morning->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($morning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($morning->start->tags())->toContain('parser/VICasualTimeParser')
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($afternoon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00');
});
