<?php

use Chrono\Chrono;

it('parses simplified chinese deadline expressions', function () {
    $deadline = Chrono::zhHans()->parseText('3天后', '2012-08-10 09:30')[0];
    $daysWithin = Chrono::zhHans()->parseText('五日内我要通关游戏', '2012-08-10')[0];

    expect($deadline->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($deadline->start->tags())->toContain('parser/ZHHansDeadlineFormatParser')
        ->and($daysWithin->text)->toBe('五日内')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00');
});
