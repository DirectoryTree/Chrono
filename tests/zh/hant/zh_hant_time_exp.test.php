<?php

use Chrono\Chrono;

it('parses traditional chinese time expressions', function () {
    $time = Chrono::zhHant()->parseText('聽晚10點到聽晚11點', '2012-08-10 12:00')[0];

    expect($time->text)->toBe('聽晚10點到聽晚11點')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-11 22:00:00')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-11 23:00:00');
});
