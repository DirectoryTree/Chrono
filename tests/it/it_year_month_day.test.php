<?php

use Chrono\Chrono;

it('parses italian casual year month day dates', function () {
    $italian = Chrono::it();
    $dotted = $italian->parseText('2012.08.10', '2012-08-10')[0];
    $prefixedDotted = $italian->parseText('La scadenza è 2012.08.10', '2012-08-10')[0];

    expect($italian->parseText('Pubblicato il 2026/06/23', '2012-08-10')[0]->text)
        ->toBe('2026/06/23')
        ->and($italian->parseText('Pubblicato il 2026/06/23', '2012-08-10')[0]->start->tags())->toContain('parser/ITCasualYearMonthDayParser')
        ->and($italian->parseDateText('Pubblicato il 2026/06/23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($italian->parseDateText('Pubblicato il 2026 giugno 23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($italian->parseDateText('Pubblicato il 2026 giu 23', '2012-08-10')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and($italian->parseText('Pubblicato il 2026/13/23', '2012-08-10'))
        ->toBe([])
        ->and($dotted->index)->toBe(0)
        ->and($dotted->text)->toBe('2012.08.10')
        ->and($dotted->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedDotted->index)->toBe(15)
        ->and($prefixedDotted->text)->toBe('2012.08.10')
        ->and($prefixedDotted->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and(Chrono::strictItalian()->parseText('2014.02.28')[0]->text)->toBe('2014.02.28')
        ->and(Chrono::strictItalian()->parseDateText('2014.12.28')?->toDateTimeString())->toBe('2014-12-28 12:00:00')
        ->and($italian->parseText('2012.80.10', '2012-08-10'))
        ->toBe([])
        ->and($italian->parseText('2014.08.32', '2012-08-10'))
        ->toBe([])
        ->and($italian->parseText('2014.02.30', '2012-08-10'))
        ->toBe([]);
});
