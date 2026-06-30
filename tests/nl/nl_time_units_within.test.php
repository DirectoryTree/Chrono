<?php

use DirectoryTree\Chrono\Chrono;

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

it('matches upstream dutch within relative duration examples', function (string $text, string $reference, string $expectedText, string $expectedDate, int $expectedIndex = 0) {
    $result = Chrono::nl()->parseText($text, $reference)[0];

    expect($result->index)->toBe($expectedIndex)
        ->and($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate);
})->with([
    ['we have to make something binnen 5 dagen.', '2012-08-10', 'binnen 5 dagen', '2012-08-15 00:00:00', 26],
    ['we have to make something binnen vijf dagen.', '2012-08-10', 'binnen vijf dagen', '2012-08-15 00:00:00', 26],
    ['we have to make something binnen de 10 dagen', '2012-08-10', 'binnen de 10 dagen', '2012-08-20 00:00:00', 26],
    ['binnen 5 minuten', '2012-08-10 12:14', 'binnen 5 minuten', '2012-08-10 12:19:00'],
    ['wait voor 5 minuten', '2012-08-10 12:14', 'voor 5 minuten', '2012-08-10 12:19:00', 5],
    ['Binnen 5 minuten ben ik thuis', '2012-08-10 12:14', 'Binnen 5 minuten', '2012-08-10 12:19:00'],
    ['Binnen de 5 minuten moet een auto zich verzetten', '2012-08-10 12:14', 'Binnen de 5 minuten', '2012-08-10 12:19:00'],
    ['Binnen 5 seconden moet een auto zich verzetten', '2012-08-10 12:14', 'Binnen 5 seconden', '2012-08-10 12:14:05'],
    ['Binnen een maand', '2012-08-10 12:14', 'Binnen een maand', '2012-09-10 12:14:00'],
    ['Binnen een jaar', '2012-08-10 12:14', 'Binnen een jaar', '2013-08-10 12:14:00'],
    ['Binnen 5 minuten A car need to move', '2012-08-10 12:14', 'Binnen 5 minuten', '2012-08-10 12:19:00'],
    ['binnen een week', '2016-10-01', 'binnen een week', '2016-10-08 00:00:00'],
    ['Binnen 24 uur', '2020-07-10 12:14', 'Binnen 24 uur', '2020-07-11 12:14:00'],
    ['binnen een dag', '2020-07-10 12:14', 'binnen een dag', '2020-07-11 12:14:00'],
]);

it('matches upstream dutch within certainty examples', function (string $text, string $expectedDate, array $certain, array $uncertain) {
    $result = Chrono::nl()->parseText($text, '2016-10-01 14:52')[0];

    expect($result->text)->toBe($text)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate);

    foreach ($certain as $component) {
        expect($result->start->isCertain($component))->toBeTrue();
    }

    foreach ($uncertain as $component) {
        expect($result->start->isCertain($component))->toBeFalse();
    }
})->with([
    ['binnen 2 minuten', '2016-10-01 14:54:00', ['year', 'month', 'day', 'hour', 'minute'], []],
    ['binnen 2 uur', '2016-10-01 16:52:00', ['year', 'month', 'day', 'hour', 'minute'], []],
    ['binnen de 12 maand', '2017-10-01 14:52:00', ['year', 'month'], ['day', 'hour', 'minute']],
    ['binnen de 3 dagen', '2016-10-04 14:52:00', ['year', 'month', 'day'], ['hour', 'minute']],
]);

it('matches upstream dutch within implied certainty example', function () {
    $result = Chrono::nl()->parseText('Binnen de 30 dagen', '2012-08-10 12:14')[0];

    expect($result->text)->toBe('Binnen de 30 dagen')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-09-09 12:14:00')
        ->and($result->start->isCertain('year'))->toBeTrue()
        ->and($result->start->isCertain('month'))->toBeTrue()
        ->and($result->start->isCertain('day'))->toBeTrue()
        ->and($result->start->isCertain('hour'))->toBeFalse()
        ->and($result->start->isCertain('minute'))->toBeFalse()
        ->and($result->start->isCertain('second'))->toBeFalse();
});
