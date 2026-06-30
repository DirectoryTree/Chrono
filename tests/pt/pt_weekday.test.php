<?php

use DirectoryTree\Chrono\Chrono;

it('parses portuguese weekdays', function () {
    $portuguese = Chrono::pt();
    $weekday = $portuguese->parseText('quarta-feira', '2012-08-10')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/PTWeekdayParser')
        ->and($portuguese->parseDateText('próximo segunda', '2012-08-10')?->toDateTimeString())->toBe('2012-08-13 00:00:00');
});
