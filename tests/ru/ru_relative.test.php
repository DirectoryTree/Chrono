<?php

use Chrono\Chrono;

it('parses russian relative date expressions', function () {
    $thisWeek = Chrono::ru()->parseText('на этой неделе', '2017-11-19 12:00')[0];
    $thisMonth = Chrono::ru()->parseText('в этом месяце', '2017-11-19 12:00')[0];
    $lastWeek = Chrono::ru()->parseText('на прошлой неделе', '2016-10-01 12:00')[0];
    $nextQuarter = Chrono::ru()->parseText('в следующем квартале', '2016-10-01 12:00')[0];

    expect($thisWeek->start->date()->toDateTimeString())->toBe('2017-11-19 12:00:00')
        ->and($thisWeek->start->tags())->toContain('parser/RURelativeDateFormatParser')
        ->and($thisMonth->start->date()->toDateTimeString())->toBe('2017-11-01 12:00:00')
        ->and($lastWeek->start->date()->toDateTimeString())->toBe('2016-09-24 12:00:00')
        ->and($nextQuarter->start->date()->toDateTimeString())->toBe('2017-01-01 12:00:00');
});
