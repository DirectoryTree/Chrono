<?php

use Chrono\Chrono;

it('parses dutch later relative durations', function () {
    $dutch = Chrono::nl();
    $fromNow = $dutch->parseText('5 dagen vanaf nu', '2012-08-10 00:00')[0];
    $minutesFromNow = $dutch->parseText('15 minuten vanaf nu', '2012-08-10 12:14')[0];
    $minutesOut = $dutch->parseText('15 minuten uit', '2012-08-10 12:14')[0];
    $secondsFromNow = $dutch->parseText('Over 12 seconden', '2012-08-10 12:14')[0];

    expect($fromNow->text)->toBe('5 dagen vanaf nu')
        ->and($fromNow->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($minutesFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($minutesOut->text)->toBe('15 minuten uit')
        ->and($minutesOut->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($secondsFromNow->text)->toBe('Over 12 seconden')
        ->and($secondsFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:12');
});

it('matches upstream dutch later relative duration examples', function (string $text, string $reference, string $expectedText, string $expectedDate, int $expectedIndex = 0) {
    $result = Chrono::nl()->parseText($text, $reference)[0];

    expect($result->index)->toBe($expectedIndex)
        ->and($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate);
})->with([
    ['2 dagen later', '2012-08-10 12:00', '2 dagen later', '2012-08-12 12:00:00'],
    ['5 minuten later', '2012-08-10 10:00', '5 minuten later', '2012-08-10 10:05:00'],
    ['3 weken later', '2012-07-10 10:00', '3 weken later', '2012-07-31 10:00:00'],
    ['5 dagen vanaf nu we hebben iets gedaan', '2012-08-10', '5 dagen vanaf nu', '2012-08-15 00:00:00'],
    ['10 dagen vanaf nu we hebben iets gedaan', '2012-08-10', '10 dagen vanaf nu', '2012-08-20 00:00:00'],
    ['15 minuten eerder', '2012-08-10 12:14', '15 minuten eerder', '2012-08-10 11:59:00'],
    ['   12 uur vanaf nu', '2012-08-10 12:14', '12 uur vanaf nu', '2012-08-11 00:14:00', 3],
    ['   half uur vanaf nu', '2012-08-10 12:14', 'half uur vanaf nu', '2012-08-10 12:44:00', 3],
    ['Over 12 uur heb ik iets gedaan', '2012-08-10 12:14', 'Over 12 uur', '2012-08-11 00:14:00'],
    ['Over 12 seconden heb ik iets gedaan', '2012-08-10 12:14', 'Over 12 seconden', '2012-08-10 12:14:12'],
    ['over drie seconden heb ik iets gedaan', '2012-08-10 12:14', 'over drie seconden', '2012-08-10 12:14:03'],
    ['Over 5 dagen hebben we iets gedaan', '2012-08-10', 'Over 5 dagen', '2012-08-15 00:00:00'],
    ['Over een dag hebben we iets gedaan', '2012-08-10', 'Over een dag', '2012-08-11 00:00:00'],
    ['een minuutje uit', '2012-08-10 12:14', 'een minuutje uit', '2012-08-10 12:15:00'],
    ['in 1 uur', '2012-08-10 12:14', 'in 1 uur', '2012-08-10 13:14:00'],
    ['over 1,5 uur', '2012-08-10 12:40', 'over 1,5 uur', '2012-08-10 14:10:00'],
]);

it('matches upstream dutch strict later relative duration examples', function (string $text, string $reference, string $expectedDate) {
    $result = Chrono::strictDutch()->parseText($text, $reference)[0];

    expect($result->text)->toBe($text)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate);
})->with([
    ['15 minuten vanaf nu', '2012-08-10 12:14', '2012-08-10 12:29:00'],
    ['25 minuten later', '2012-08-10 12:40', '2012-08-10 13:05:00'],
]);
