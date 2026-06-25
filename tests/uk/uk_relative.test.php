<?php

use Chrono\Chrono;

it('parses ukrainian relative date expressions', function () {
    $thisWeek = Chrono::uk()->parseText('на цьому тижні', '2017-11-19 12:00')[0];
    $thisMonth = Chrono::uk()->parseText('у цьому місяці', '2017-11-19 12:00')[0];
    $pastWeek = Chrono::uk()->parseText('на минулому тижні', '2016-10-01 12:00')[0];
    $nextQuarter = Chrono::uk()->parseText('в наступному кварталі', '2016-10-01 12:00')[0];

    expect($thisWeek->start->date()->toDateTimeString())->toBe('2017-11-19 12:00:00')
        ->and($thisMonth->start->date()->toDateTimeString())->toBe('2017-11-01 12:00:00')
        ->and($pastWeek->start->date()->toDateTimeString())->toBe('2016-09-24 12:00:00')
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00');
});
