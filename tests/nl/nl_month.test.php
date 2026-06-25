<?php

use Chrono\Chrono;

it('parses dutch month only and month year expressions', function () {
    $dutch = Chrono::nl();

    expect($dutch->parseText('Planning januari, 2012', '2012-08-10')[0]->text)
        ->toBe('januari, 2012')
        ->and($dutch->parseText('Planning januari, 2012', '2012-08-10')[0]->start->tags())->toContain('parser/NLMonthNameParser')
        ->and($dutch->parseDateText('Planning januari, 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-01-01 12:00:00')
        ->and($dutch->parseDateText('september 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->parseDateText('sept 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->parseDateText('sep 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->parseDateText('sep. 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->parseText('sep-2012', '2012-08-10')[0]->text)
        ->toBe('sep-2012')
        ->and($dutch->parseDateText('mrt 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-03-01 12:00:00')
        ->and($dutch->parseDateText('Planning januari', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-01-01 12:00:00')
        ->and($dutch->parseDateText('In januari', '2020-11-22')?->toDateTimeString())
        ->toBe('2021-01-01 12:00:00')
        ->and($dutch->parseDateText('in jan', '2020-11-22')?->toDateTimeString())
        ->toBe('2021-01-01 12:00:00')
        ->and($dutch->parseDateText('mei', '2020-11-22')?->toDateTimeString())
        ->toBe('2021-05-01 12:00:00')
        ->and($dutch->parseDateText('Planning jan 87', '2012-08-10')?->toDateTimeString())
        ->toBe('1987-01-01 12:00:00')
        ->and($dutch->parseText('The date is sep 2012 is the date', '2012-08-10')[0]->index)
        ->toBe(12)
        ->and($dutch->parseText('By Angie ja november 2019', '2012-08-10')[0]->text)
        ->toBe('november 2019')
        ->and($dutch->parseText('Op 23 MRT. 2022', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2022-03-23 12:00:00')
        ->and($dutch->parseText('aug 96', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('1996-08-01 12:00:00')
        ->and($dutch->parseText('96 aug 96', '2012-08-10')[0]->text)
        ->toBe('aug 96');
});
