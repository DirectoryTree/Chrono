<?php

use DirectoryTree\Chrono\Chrono;

it('parses italian casual dates', function () {
    $italian = Chrono::it();
    $now = $italian->parseText('La scadenza è ora', '2012-08-10 08:09:10.011')[0];
    $today = $italian->parseText('La scadenza è oggi', '2012-08-10 14:12')[0];
    $tomorrow = $italian->parseText('La scadenza è domani', '2012-08-10 17:10')[0];

    expect($now->index)->toBe(14)
        ->and($now->text)->toBe('ora')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->get('year'))->toBe(2012)
        ->and($now->start->get('month'))->toBe(8)
        ->and($now->start->get('day'))->toBe(10)
        ->and($now->start->get('hour'))->toBe(8)
        ->and($now->start->get('minute'))->toBe(9)
        ->and($now->start->get('second'))->toBe(10)
        ->and($now->start->get('millisecond'))->toBe(11)
        ->and($now->start->tags())->toContain('parser/ITCasualDateParser')
        ->and($today->index)->toBe(14)
        ->and($today->text)
        ->toBe('oggi')
        ->and($today->start->date()->toDateTimeString())
        ->toBe('2012-08-10 14:12:00')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->index)->toBe(14)
        ->and($tomorrow->text)
        ->toBe('domani')
        ->and($tomorrow->start->date()->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($italian->parseDateText('La scadenza è dmn', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($italian->parseDateText('Ci vediamo questa sera', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($italian->parseDateText('Ci vediamo ieri sera', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00');
});

it('parses italian casual times', function () {
    $italian = Chrono::it();

    expect($italian->parseDateText('Ci vediamo questa mattina', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($italian->parseText('Ci vediamo questa mattina', '2012-08-10 09:30')[0]->start->tags())->toContain('parser/ITCasualTimeParser')
        ->and($italian->parseDateText('Ci vediamo pomeriggio', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($italian->parseDateText('Ci vediamo sera', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($italian->parseDateText('Ci vediamo mezzogiorno', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($italian->parseDateText('Ci vediamo mezzanotte', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00');
});
