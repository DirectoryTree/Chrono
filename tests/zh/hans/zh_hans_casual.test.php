<?php

use Chrono\Chrono;

it('parses simplified chinese casual dates', function () {
    $chinese = Chrono::zhHans();
    $today = $chinese->parseText('我今天要打游戏', '2012-08-10 12:00')[0];
    $lastNight = $chinese->parseText('我昨天晚上要打游戏', '2012-08-10 12:00')[0];

    expect($today->text)->toBe('今天')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00');
});
