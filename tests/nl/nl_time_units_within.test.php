<?php

use Chrono\Chrono;

it('parses dutch within relative durations', function () {
    $dutch = Chrono::nl();
    $spelledWithin = $dutch->parseText('we have to make something binnen vijf dagen.', '2012-08-10 12:14')[0];
    $withinTenDays = $dutch->parseText('we have to make something binnen de 10 dagen', '2012-08-10 12:14')[0];
    $withinOneHour = $dutch->parseText('binnen 1 uur', '2012-08-10 12:14')[0];
    $withinTwoWeeks = $dutch->parseText('Binnen de 2 weken', '2012-08-10 12:14')[0];
    $withinMinuteShort = $dutch->parseText('Binnen 5 min a car need to move', '2012-08-10 12:14')[0];

    expect($spelledWithin->text)->toBe('binnen vijf dagen')
        ->and($spelledWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:14:00')
        ->and($withinTenDays->text)->toBe('binnen de 10 dagen')
        ->and($withinTenDays->start->date()->toDateTimeString())->toBe('2012-08-20 12:14:00')
        ->and($withinOneHour->text)->toBe('binnen 1 uur')
        ->and($withinOneHour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($withinTwoWeeks->text)->toBe('Binnen de 2 weken')
        ->and($withinTwoWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($withinMinuteShort->text)->toBe('Binnen 5 min')
        ->and($withinMinuteShort->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00');
});
