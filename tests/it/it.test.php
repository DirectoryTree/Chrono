<?php

use DirectoryTree\Chrono\Chrono;

it('merges italian dates with times and date ranges', function () {
    $italian = Chrono::it();
    $dateTime = $italian->parse('Ci vediamo 10 agosto 2012 alle 6:30', '2012-08-10')[0];
    $timeRange = $italian->parse('Ci vediamo 10 agosto 2012 dalle 6:30 - 8:45', '2012-08-10')[0];
    $dateRange = $italian->parse('Evento 10 agosto 2012 - 12 agosto 2012', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 agosto 2012 alle 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->start->isCertain('hour'))->toBeTrue()
        ->and($timeRange->text)->toBe('10 agosto 2012 dalle 6:30 - 8:45')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dateRange->text)->toBe('10 agosto 2012 - 12 agosto 2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});

it('parses and merges italian relative date references', function () {
    $italian = Chrono::it();
    $before = $italian->parse('2 giorni prima 10 agosto 2012', '2012-08-01')[0];
    $after = $italian->parse('2 giorni dopo 10 agosto 2012', '2012-08-01')[0];

    expect($italian->date('questa settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-05 09:30:00')
        ->and($italian->date('questo mese', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-01 09:30:00')
        ->and($italian->date('questo anno', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-01-01 09:30:00')
        ->and($italian->date('prossimo settimana', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-17 09:30:00')
        ->and($before->text)->toBe('2 giorni prima 10 agosto 2012')
        ->and($before->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($after->text)->toBe('2 giorni dopo 10 agosto 2012')
        ->and($after->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});
