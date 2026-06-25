<?php

use Chrono\Chrono;

it('parses little endian month name dates with two digit years', function () {
    $explicit = Chrono::parse('10 August 2012', '2012-08-10')[0];
    $result = Chrono::parse('3rd Feb 82', '2012-08-10')[0];
    $leadingZero = Chrono::parse('03 Aug 96', '2012-08-10')[0];
    $singleDigit = Chrono::parse('9 Aug 96', '2012-08-10')[0];
    $deadline = Chrono::parse('The Deadline is 10 August', '2012-08-10')[0];

    expect($explicit->text)->toBe('10 August 2012')
        ->and($explicit->index)->toBe(0)
        ->and($explicit->start->get('year'))->toBe(2012)
        ->and($explicit->start->get('month'))->toBe(8)
        ->and($explicit->start->get('day'))->toBe(10)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->text)->toBe('3rd Feb 82')
        ->and($result->index)->toBe(0)
        ->and($result->start->get('year'))->toBe(1982)
        ->and($result->start->get('month'))->toBe(2)
        ->and($result->start->get('day'))->toBe(3)
        ->and($result->start->date()->toDateTimeString())->toBe('1982-02-03 12:00:00')
        ->and($leadingZero->text)->toBe('03 Aug 96')
        ->and($leadingZero->start->date()->toDateTimeString())->toBe('1996-08-03 12:00:00')
        ->and($singleDigit->text)->toBe('9 Aug 96')
        ->and($singleDigit->start->date()->toDateTimeString())->toBe('1996-08-09 12:00:00')
        ->and($deadline->text)->toBe('10 August')
        ->and($deadline->index)->toBe(16)
        ->and($deadline->start->get('year'))->toBe(2012)
        ->and($deadline->start->get('month'))->toBe(8)
        ->and($deadline->start->get('day'))->toBe(10);
});

it('parses little endian same month ranges', function () {
    $result = Chrono::parse('10 - 22 August 2012', '2012-08-10')[0];

    expect($result->text)->toBe('10 - 22 August 2012')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00');
});

it('parses little endian cross month ranges', function () {
    $result = Chrono::parse('10 August - 12 September', '2012-08-10')[0];

    expect($result->text)->toBe('10 August - 12 September')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00');
});

it('uses an end year for both dates in little endian cross month ranges', function () {
    $result = Chrono::parse('10 August - 12 September 2013', '2012-08-10')[0];
    $startYear = Chrono::parse('10 August 2013 - 12 September', '2012-08-10')[0];

    expect($result->text)->toBe('10 August - 12 September 2013')
        ->and($result->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($startYear->text)->toBe('10 August 2013 - 12 September')
        ->and($startYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($startYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00');
});

it('parses little endian month name dates followed by times', function () {
    expect(Chrono::parse('12th of July at 19:00', '2012-08-10')[0]->text)
        ->toBe('12th of July at 19:00')
        ->and(Chrono::parse('12th August', '2012-08-10')[0]->text)
        ->toBe('12th August')
        ->and(Chrono::parseDate('12 August', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and(Chrono::parseDate('12th of August', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and(Chrono::parseDate('12th of July at 19:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:00:00')
        ->and(Chrono::parseDate('5 May 12:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-05 12:00:00')
        ->and(Chrono::parseDate('24th October, 9 am', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 09:00:00')
        ->and(Chrono::parse('24 October, 9 p.m.', '2017-07-07 15:00')[0]->text)
        ->toBe('24 October, 9 p.m.')
        ->and(Chrono::parseDate('24 October, 9 p.m.', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 21:00:00')
        ->and(Chrono::parseDate('24 October 10 o clock', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 10:00:00');
});
