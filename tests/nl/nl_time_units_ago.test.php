<?php

use DirectoryTree\Chrono\Chrono;

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

it('matches upstream dutch ago relative duration examples', function (string $text, string $reference, string $expectedText, string $expectedDate, int $expectedIndex = 0) {
    $result = Chrono::nl()->parseText($text, $reference)[0];

    expect($result->index)->toBe($expectedIndex)
        ->and($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate);
})->with([
    ['5 dagen geleden, hebben we wat gedaan', '2012-08-10', '5 dagen geleden', '2012-08-05 00:00:00'],
    ['10 dagen geleden, hebben we wat gedaan', '2012-08-10', '10 dagen geleden', '2012-07-31 00:00:00'],
    ['15 minuten geleden', '2012-08-10 12:14', '15 minuten geleden', '2012-08-10 11:59:00'],
    ['15 minuten eerder', '2012-08-10 12:14', '15 minuten eerder', '2012-08-10 11:59:00'],
    ['15 minuten voor', '2012-08-10 12:14', '15 minuten voor', '2012-08-10 11:59:00'],
    ['   12 uur geleden', '2012-08-10 12:14', '12 uur geleden', '2012-08-10 00:14:00', 3],
    ['1u geleden', '2012-08-10 12:14', '1u geleden', '2012-08-10 11:14:00'],
    ['12 uur geleden deed ik iets', '2012-08-10 12:14', '12 uur geleden', '2012-08-10 00:14:00'],
    ['12 seconden geleden deed ik iets', '2012-08-10 12:14', '12 seconden geleden', '2012-08-10 12:13:48'],
    ['drie seconden geleden deed ik iets', '2012-08-10 12:14', 'drie seconden geleden', '2012-08-10 12:13:57'],
    ['5 dagen geleden, hebben we iets gedaan', '2012-08-10', '5 dagen geleden', '2012-08-05 00:00:00'],
    ['Een dag geleden, hebben we wat gedaan', '2012-08-10', 'Een dag geleden', '2012-08-09 00:00:00'],
    ['een minuut geleden', '2012-08-10 12:14', 'een minuut geleden', '2012-08-10 12:13:00'],
    ['5 maanden geleden, hebben we iets gedaan', '2012-10-10', '5 maanden geleden', '2012-05-10 00:00:00'],
    ['5 jaar geleden,  hebben we iets gedaan', '2012-08-10', '5 jaar geleden', '2007-08-10 00:00:00'],
    ['een week geleden, hebben we iets gedaan', '2012-08-03', 'een week geleden', '2012-07-27 00:00:00'],
    ['paar dagen geleden, hebben we iets gedaan', '2012-08-02', 'paar dagen geleden', '2012-07-31 00:00:00'],
    ['1 dag 21 uur geleden ', '2012-08-10 22:30', '1 dag 21 uur geleden', '2012-08-09 01:30:00'],
    ['3 min 49 sec geleden ', '2012-08-10 22:30', '3 min 49 sec geleden', '2012-08-10 22:26:11'],
]);

it('matches upstream dutch ago negative cases', function (string $text) {
    expect(Chrono::nl()->parseText($text))->toBe([]);
})->with([
    'een paar uur',
    '5 dagen',
]);

// Upstream also checks "15 uur 29 min" against the root chrono export. The
// Dutch parser intentionally accepts "15 uur" as a clock time, so that case is
// not portable to this locale-specific PHP test.
