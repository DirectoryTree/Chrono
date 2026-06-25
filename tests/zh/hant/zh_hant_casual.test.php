<?php

use Chrono\Chrono;

it('parses traditional chinese casual dates', function () {
    $chinese = Chrono::zhHant();
    $now = $chinese->parseText('雞而家全部都係雞', '2012-08-10 08:09:10.011')[0];
    $lastNight = $chinese->parseText('雞昨天晚上全部都係雞', '2012-08-10 12:00')[0];

    expect($now->text)->toBe('而家')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00');
});
