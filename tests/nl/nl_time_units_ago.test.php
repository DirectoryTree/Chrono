<?php

use Chrono\Chrono;

it('parses dutch ago relative durations', function () {
    $dutch = Chrono::nl();
    $halfHourAgo = $dutch->parseText('   half uur geleden', '2012-08-10 12:14')[0];
    $threeSecondsAgo = $dutch->parseText('drie seconden geleden', '2012-08-10 12:14')[0];
    $nestedAgo = $dutch->parseText('15 uur 29 minuten geleden', '2012-08-10 22:30')[0];

    expect($halfHourAgo->index)->toBe(3)
        ->and($halfHourAgo->text)->toBe('half uur geleden')
        ->and($halfHourAgo->start->date()->toDateTimeString())->toBe('2012-08-10 11:44:00')
        ->and($threeSecondsAgo->text)->toBe('drie seconden geleden')
        ->and($threeSecondsAgo->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:57')
        ->and($nestedAgo->text)->toBe('15 uur 29 minuten geleden')
        ->and($nestedAgo->start->date()->toDateTimeString())->toBe('2012-08-10 07:01:00');
});
