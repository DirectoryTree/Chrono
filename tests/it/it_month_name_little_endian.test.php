<?php

use DirectoryTree\Chrono\Chrono;

it('parses italian month name expressions', function () {
    $italian = Chrono::it();
    $prefixed = $italian->parse('Ci vediamo ad Agosto 2017.', '2012-08-10')[0];

    expect($italian->parse('Partiremo in giugno', '2012-08-10')[0]->text)
        ->toBe('giugno')
        ->and($italian->parse('Partiremo in giugno', '2012-08-10')[0]->start->tags())->toContain('parser/ITMonthNameParser')
        ->and($italian->date('Partiremo in giugno', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-06-01 12:00:00')
        ->and($prefixed->index)->toBe(14)
        ->and($prefixed->text)->toBe('Agosto 2017')
        ->and($prefixed->start->get('year'))->toBe(2017)
        ->and($prefixed->start->get('month'))->toBe(8)
        ->and($prefixed->start->get('day'))->toBe(1)
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2017-08-01 12:00:00')
        ->and($italian->parse('Partiremo giugno 2026', '2012-08-10')[0]->text)
        ->toBe('giugno 2026')
        ->and($italian->date('Partiremo giugno 2026', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-01 12:00:00')
        ->and($italian->date('Partiremo giu 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-06-01 12:00:00')
        ->and($italian->parse('Partiremo giu', '2012-08-10'))
        ->toBe([]);
});

it('parses italian little endian month name dates and ranges', function () {
    $italian = Chrono::it();
    $range = $italian->parse('Evento dal 10 al 12 agosto 2012', '2012-08-10')[0];
    $explicitYear = $italian->parse('10 Agosto 2012', '2012-08-10')[0];
    $prefixed = $italian->parse('La scadenza è il 10 Agosto', '2012-08-10')[0];

    expect($italian->parse('Evento il 10 agosto', '2012-08-10')[0]->text)
        ->toBe('10 agosto')
        ->and($italian->parse('Evento il 10 agosto', '2012-08-10')[0]->start->tags())->toContain('parser/ITMonthNameLittleEndianParser')
        ->and($italian->date('Evento il 10 agosto', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($explicitYear->index)->toBe(0)
        ->and($explicitYear->text)->toBe('10 Agosto 2012')
        ->and($explicitYear->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixed->index)->toBe(17)
        ->and($prefixed->text)->toBe('10 Agosto')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($italian->date('Evento il decimo agosto', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($italian->date('Evento il 10 agosto 96', '2012-08-10')?->toDateTimeString())
        ->toBe('1996-08-10 12:00:00')
        ->and($range->text)->toBe('10 al 12 agosto 2012')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00');
});
