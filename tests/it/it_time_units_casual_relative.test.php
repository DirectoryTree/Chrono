<?php

use Chrono\Chrono;

it('parses italian time unit relative expressions', function () {
    $italian = Chrono::it();

    expect($italian->parseText('Ci vediamo in 2 giorni', '2012-08-10 09:30')[0]->text)
        ->toBe('in 2 giorni')
        ->and($italian->parseDateText('Ci vediamo in 2 giorni', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00')
        ->and($italian->parseDateText('Ci siamo visti 3 giorni fa', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-07 09:30:00')
        ->and($italian->parseDateText('Ci vediamo 4 ore dopo', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 13:30:00')
        ->and($italian->parseDateText('Ci vediamo prossima 1 settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-17 09:30:00')
        ->and($italian->parseDateText('Ci siamo visti ultima 1 settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-03 09:30:00')
        ->and($italian->parseDateText('Ci vediamo 2 giorni', '2012-08-10 09:30', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00');
});
