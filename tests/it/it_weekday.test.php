<?php

use DirectoryTree\Chrono\Chrono;

it('parses italian weekdays', function () {
    $italian = Chrono::it();
    $monday = $italian->parse('Lunedì', '2012-08-09')[0];
    $thursday = $italian->parse('Giovedì', '2012-08-09')[0];
    $sunday = $italian->parse('Domenica', '2012-08-09')[0];
    $merged = $italian->parse('lunedì, 10 agosto 2012', '2012-08-10')[0];

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('Lunedì')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($thursday->index)->toBe(0)
        ->and($thursday->text)->toBe('Giovedì')
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($sunday->index)->toBe(0)
        ->and($sunday->text)->toBe('Domenica')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($italian->parse('Ci vediamo lunedì', '2012-08-10')[0]->text)
        ->toBe('lunedì')
        ->and($italian->parse('Ci vediamo lunedì', '2012-08-10')[0]->start->tags())->toContain('parser/ITWeekdayParser')
        ->and($italian->date('Ci vediamo lunedì', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($italian->date('Ci vediamo prossimo lunedì', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($italian->date('Ci vediamo scorsa domenica', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 12:00:00')
        ->and($italian->date('Ci vediamo lunedì questa settimana', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($merged->text)->toBe('lunedì, 10 agosto 2012')
        ->and($merged->start->isCertain('weekday'))->toBeTrue()
        ->and($merged->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});
