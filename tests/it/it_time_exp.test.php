<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses italian time expressions and ranges', function () {
    $italian = Chrono::it();
    $eighteenTen = $italian->parseText('Alle 18:10', '2012-08-10')[0];
    $sixTen = $italian->parseText('Alle 6:10', '2012-08-10')[0];
    $afternoon = $italian->parseText('6 del pomeriggio', '2012-08-10')[0];
    $evening = $italian->parseText('Alle 20 sera', '2012-08-10')[0];
    $tonight = $italian->parseText('Stasera', '2012-08-10')[0];
    $plainRange = $italian->parseText('10:00 - 12:00', '2012-08-10')[0];
    $range = $italian->parseText('dalle 6:30 - 8:45', '2012-08-10')[0];
    $milliseconds = $italian->parseText('8:10:30.123', '2012-08-10')[0];
    $inPoint = $italian->parseText('ore 6 in punto', '2012-08-10')[0];
    $morning = $italian->parseText('6 della mattina', '2012-08-10')[0];

    expect($italian->parseText('Ci vediamo alle 6:13', '2012-08-10')[0]->text)
        ->toBe('alle 6:13')
        ->and($italian->parseText('Ci vediamo alle 6:13', '2012-08-10')[0]->start->tags())->toContain('parser/ITTimeExpressionParser')
        ->and($italian->parseDateText('Ci vediamo alle 6:13', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:13:00')
        ->and($eighteenTen->index)->toBe(0)
        ->and($eighteenTen->text)->toBe('Alle 18:10')
        ->and($eighteenTen->start->date()->toDateTimeString())->toBe('2012-08-10 18:10:00')
        ->and($sixTen->text)->toBe('Alle 6:10')
        ->and($sixTen->start->date()->toDateTimeString())->toBe('2012-08-10 06:10:00')
        ->and($afternoon->text)->toBe('6 del pomeriggio')
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2012-08-10 18:00:00')
        ->and($afternoon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonight->text)->toBe('Stasera')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($plainRange->text)->toBe('10:00 - 12:00')
        ->and($plainRange->start->date()->toDateTimeString())->toBe('2012-08-10 10:00:00')
        ->and($plainRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($inPoint->index)->toBe(4)
        ->and($inPoint->text)->toBe('6 in punto')
        ->and($inPoint->start->get('hour'))->toBe(6)
        ->and($inPoint->start->get('minute'))->toBe(0)
        ->and($morning->index)->toBe(0)
        ->and($morning->text)->toBe('6 della mattina')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($morning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($italian->parseDateText('Ci vediamo alle 6 di sera', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($evening->text)
        ->toBe('Alle 20 sera')
        ->and($evening->start->date()->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($evening->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($italian->parseDateText('Ci vediamo alle 6 di pomeriggio', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($range->text)->toBe('dalle 6:30 - 8:45')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($italian->parseText('Ho 123 cose da fare', '2012-08-10'))
        ->toBe([]);
});
