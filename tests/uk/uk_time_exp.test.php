<?php

use Chrono\Chrono;

it('parses ukrainian time expressions', function () {
    $time = Chrono::uk()->parseText('о 6:30 вечора', '2012-08-10 09:30')[0];
    $fullTime = Chrono::uk()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $timeRange = Chrono::uk()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morning = Chrono::uk()->parseText('об 11 ранку', '2016-10-01 08:00')[0];
    $evening = Chrono::uk()->parseText('в 11 вечора', '2016-10-01 08:00')[0];

    expect($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/UKTimeExpressionParser')
        ->and($fullTime->text)->toBe('20:32:13')
        ->and($fullTime->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morning->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($evening->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00');
});
