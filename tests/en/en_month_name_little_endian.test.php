<?php

use Chrono\Chrono;

it('parses little endian month name dates with two digit years', function () {
    $explicit = Chrono::parse('10 August 2012', '2012-08-10')[0];
    $result = Chrono::parse('3rd Feb 82', '2012-08-10')[0];
    $leadingZero = Chrono::parse('03 Aug 96', '2012-08-10')[0];
    $withoutLeadingZero = Chrono::parse('3 Aug 96', '2012-08-10')[0];
    $singleDigit = Chrono::parse('9 Aug 96', '2012-08-10')[0];
    $deadline = Chrono::parse('The Deadline is 10 August', '2012-08-10')[0];
    $march = Chrono::parse('31st March, 2016', '2012-08-10')[0];
    $february = Chrono::parse('23rd february, 2016', '2012-08-10')[0];

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
        ->and($withoutLeadingZero->text)->toBe('3 Aug 96')
        ->and($withoutLeadingZero->start->date()->toDateTimeString())->toBe('1996-08-03 12:00:00')
        ->and($singleDigit->text)->toBe('9 Aug 96')
        ->and($singleDigit->start->date()->toDateTimeString())->toBe('1996-08-09 12:00:00')
        ->and($deadline->text)->toBe('10 August')
        ->and($deadline->index)->toBe(16)
        ->and($deadline->start->get('year'))->toBe(2012)
        ->and($deadline->start->get('month'))->toBe(8)
        ->and($deadline->start->get('day'))->toBe(10)
        ->and($march->text)->toBe('31st March, 2016')
        ->and($march->start->date()->toDateTimeString())->toBe('2016-03-31 12:00:00')
        ->and($february->text)->toBe('23rd february, 2016')
        ->and($february->start->date()->toDateTimeString())->toBe('2016-02-23 12:00:00');
});

it('parses little endian weekday-prefixed month name dates', function () {
    $shortWeekday = Chrono::parse('Sun 15Sep', '2013-08-10')[0];
    $upperWeekday = Chrono::parse('SUN 15SEP', '2013-08-10')[0];
    $longWeekday = Chrono::parse('The Deadline is Tuesday, 10 January', '2012-08-10')[0];
    $abbreviatedWeekday = Chrono::parse('The Deadline is Tue, 10 January', '2012-08-10')[0];

    expect($shortWeekday->text)->toBe('Sun 15Sep')
        ->and($shortWeekday->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($upperWeekday->text)->toBe('SUN 15SEP')
        ->and($upperWeekday->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($longWeekday->text)->toBe('Tuesday, 10 January')
        ->and($longWeekday->index)->toBe(16)
        ->and($longWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($longWeekday->start->get('weekday'))->toBe(2)
        ->and($abbreviatedWeekday->text)->toBe('Tue, 10 January')
        ->and($abbreviatedWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(2);
});

it('parses little endian month name dates with separators', function () {
    expect(Chrono::parseDate('10-August 2012', '2012-08-08')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10-August-2012', '2012-08-08')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10/August 2012', '2012-08-08')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10/August/2012', '2012-08-08')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00');
});

it('parses little endian ordinal word month name expressions', function () {
    $date = Chrono::parse('Twenty-fourth of May', '2012-08-10')[0];
    $range = Chrono::parse('Eighth to eleventh May 2010', '2012-08-10')[0];

    expect($date->text)->toBe('Twenty-fourth of May')
        ->and($date->start->get('year'))->toBe(2012)
        ->and($date->start->get('month'))->toBe(5)
        ->and($date->start->get('day'))->toBe(24)
        ->and($range->text)->toBe('Eighth to eleventh May 2010')
        ->and($range->start->date()->toDateTimeString())->toBe('2010-05-08 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2010-05-11 12:00:00');
});

it('parses little endian same month ranges', function () {
    $result = Chrono::parse('10 - 22 August 2012', '2012-08-10')[0];
    $toRange = Chrono::parse('10 to 22 August 2012', '2012-08-10')[0];

    expect($result->text)->toBe('10 - 22 August 2012')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($toRange->text)->toBe('10 to 22 August 2012')
        ->and($toRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($toRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00');
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
        ->and(Chrono::parseDate('7 May 11:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-07 11:00:00')
        ->and(Chrono::parseDate('24th October, 9 am', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 09:00:00')
        ->and(Chrono::parseDate('24th October, 9 pm', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 21:00:00')
        ->and(Chrono::parse('24 October, 9 pm', '2017-07-07 15:00')[0]->text)
        ->toBe('24 October, 9 pm')
        ->and(Chrono::parseDate('24 October, 9 pm', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 21:00:00')
        ->and(Chrono::parse('24 October, 9 p.m.', '2017-07-07 15:00')[0]->text)
        ->toBe('24 October, 9 p.m.')
        ->and(Chrono::parseDate('24 October, 9 p.m.', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 21:00:00')
        ->and(Chrono::parseDate('24 October 10 o clock', '2017-07-07 15:00')?->toDateTimeString())
        ->toBe('2017-10-24 10:00:00');
});

it('moves little endian ranges forward when requested', function () {
    $normal = Chrono::casual()->parseText('22-23 Feb at 7pm', '2016-03-15')[0];
    $forward = Chrono::casual()->parseText('22-23 Feb at 7pm', '2016-03-15', ['forwardDate' => true])[0];
    $explicitRange = Chrono::parse('17 August 2013 - 19 August 2013', '2012-08-10')[0];

    expect($normal->start->date()->toDateTimeString())->toBe('2016-02-22 19:00:00')
        ->and($normal->end?->date()->toDateTimeString())->toBe('2016-02-23 19:00:00')
        ->and($forward->start->date()->toDateTimeString())->toBe('2017-02-22 19:00:00')
        ->and($forward->end?->date()->toDateTimeString())->toBe('2017-02-23 19:00:00')
        ->and($explicitRange->start->date()->toDateTimeString())->toBe('2013-08-17 12:00:00')
        ->and($explicitRange->end?->date()->toDateTimeString())->toBe('2013-08-19 12:00:00');
});

it('rejects impossible little endian month name dates in strict mode', function () {
    expect(Chrono::strict()->parseText('32 August 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strict()->parseText('29 February 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strict()->parseText('32 August', '2012-08-10'))->toBe([])
        ->and(Chrono::strict()->parseText('29 February', '2013-08-10'))->toBe([]);
});

it('parses little endian year 3000 and current-year dates with times', function () {
    $future = Chrono::parse('Jan 1 3000, 9:30')[0];
    $current = Chrono::parse('Jan 1 2025, 9:30')[0];

    expect($future->index)->toBe(0)
        ->and($future->text)->toBe('Jan 1 3000, 9:30')
        ->and($future->start->date()->toDateTimeString())->toBe('3000-01-01 09:30:00')
        ->and($current->index)->toBe(0)
        ->and($current->text)->toBe('Jan 1 2025, 9:30')
        ->and($current->start->date()->toDateTimeString())->toBe('2025-01-01 09:30:00');
});
