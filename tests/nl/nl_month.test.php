<?php

use DirectoryTree\Chrono\Chrono;

it('parses dutch month only and month year expressions', function () {
    $dutch = Chrono::nl();

    expect($dutch->parse('Planning januari, 2012', '2012-08-10')[0]->text)
        ->toBe('januari, 2012')
        ->and($dutch->parse('Planning januari, 2012', '2012-08-10')[0]->start->tags())->toContain('parser/NLMonthNameParser')
        ->and($dutch->date('Planning januari, 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-01-01 12:00:00')
        ->and($dutch->date('september 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->date('sept 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->date('sep 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->date('sep. 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-09-01 12:00:00')
        ->and($dutch->parse('sep-2012', '2012-08-10')[0]->text)
        ->toBe('sep-2012')
        ->and($dutch->date('mrt 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-03-01 12:00:00')
        ->and($dutch->date('Planning januari', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-01-01 12:00:00')
        ->and($dutch->date('In januari', '2020-11-22')?->toDateTimeString())
        ->toBe('2021-01-01 12:00:00')
        ->and($dutch->date('in jan', '2020-11-22')?->toDateTimeString())
        ->toBe('2021-01-01 12:00:00')
        ->and($dutch->date('mei', '2020-11-22')?->toDateTimeString())
        ->toBe('2021-05-01 12:00:00')
        ->and($dutch->date('Planning jan 87', '2012-08-10')?->toDateTimeString())
        ->toBe('1987-01-01 12:00:00')
        ->and($dutch->parse('The date is sep 2012 is the date', '2012-08-10')[0]->index)
        ->toBe(12)
        ->and($dutch->parse('By Angie ja november 2019', '2012-08-10')[0]->text)
        ->toBe('november 2019')
        ->and($dutch->parse('Op 23 MRT. 2022', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2022-03-23 12:00:00')
        ->and($dutch->parse('aug 96', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('1996-08-01 12:00:00')
        ->and($dutch->parse('96 aug 96', '2012-08-10')[0]->text)
        ->toBe('aug 96');
});
