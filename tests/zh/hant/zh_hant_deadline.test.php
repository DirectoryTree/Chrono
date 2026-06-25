<?php

use Chrono\Chrono;

it('parses traditional chinese deadline expressions', function () {
    $deadline = Chrono::zhHant()->parseText('三天後', '2012-08-10 09:30')[0];
    $daysWithin = Chrono::zhHant()->parseText('五日內我地有d野做', '2012-08-10')[0];

    expect($deadline->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($deadline->start->tags())->toContain('parser/ZHHantDeadlineFormatParser')
        ->and($daysWithin->text)->toBe('五日內')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00');
});
