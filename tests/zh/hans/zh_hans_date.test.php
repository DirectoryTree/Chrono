<?php

use Chrono\Chrono;

it('parses simplified chinese dates', function () {
    $chinese = Chrono::zhHans();
    $date = $chinese->parseText('2014年7月12日', '2012-08-10')[0];
    $hanDate = $chinese->parseText('我二零一六年，九月三号要打游戏', '2012-08-10')[0];

    expect($date->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHansDateParser')
        ->and($hanDate->text)->toBe('二零一六年，九月三号')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00');
});
