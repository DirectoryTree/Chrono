<?php

use Chrono\Chrono;

it('parses russian time expressions', function () {
    $timeWithSeconds = Chrono::ru()->parseText('20:32:13', '2016-10-01 08:00')[0];
    $time = Chrono::ru()->parseText('в 6:30 вечера', '2012-08-10 09:30')[0];
    $timeRange = Chrono::ru()->parseText('10:00:00 - 21:45:01', '2016-10-01 08:00')[0];
    $morningTime = Chrono::ru()->parseText('в 11 утра', '2016-10-01 08:00')[0];
    $eveningTime = Chrono::ru()->parseText('в 11 вечера', '2016-10-01 08:00')[0];
    $morningRange = Chrono::ru()->parseText('с 10 до 11 утра', '2016-10-01 08:00')[0];
    $eveningRange = Chrono::ru()->parseText('с 10 до 11 вечера', '2016-10-01 08:00')[0];

    expect($timeWithSeconds->text)->toBe('20:32:13')
        ->and($timeWithSeconds->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($time->start->tags())->toContain('parser/RUTimeExpressionParser')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:01')
        ->and($morningTime->text)->toBe('в 11 утра')
        ->and($morningTime->start->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningTime->text)->toBe('в 11 вечера')
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2016-10-01 10:00:00')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 11:00:00')
        ->and($eveningRange->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($eveningRange->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00');
});
