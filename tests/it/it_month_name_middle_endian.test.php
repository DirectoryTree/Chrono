<?php

use DirectoryTree\Chrono\Chrono;

it('parses italian middle endian month name dates and ranges', function () {
    $italian = Chrono::it();
    $range = $italian->parse('Evento agosto 10-12 2012', '2012-08-10')[0];
    $explicitYear = $italian->parse('Agosto 10, 2012', '2012-08-10')[0];
    $prefixed = $italian->parse('La scadenza è Agosto 10', '2012-08-10')[0];

    expect($italian->parse('Evento agosto 10', '2012-08-10')[0]->text)
        ->toBe('agosto 10')
        ->and($italian->parse('Evento agosto 10', '2012-08-10')[0]->start->tags())->toContain('parser/ITMonthNameMiddleEndianParser')
        ->and($italian->date('Evento agosto 10', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($explicitYear->index)->toBe(0)
        ->and($explicitYear->text)->toBe('Agosto 10, 2012')
        ->and($explicitYear->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixed->text)->toBe('Agosto 10')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($italian->date('Evento agosto decimo', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($italian->date('Evento agosto 10 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-08-10 12:00:00')
        ->and($range->text)->toBe('agosto 10-12 2012')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($italian->parse('Evento agosto 12:00', '2012-08-10')[0]->text)
        ->toBe('12:00');
});
