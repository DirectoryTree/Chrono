<?php

use Chrono\Chrono;

it('parses english date and time expressions from the upstream mixed fixture', function () {
    $dateTime = Chrono::parse('Something happen on 2014-04-18 13:00 - 16:00 as')[0];
    $range = Chrono::parse('between 3:30-4:30pm', '2020-07-06')[0];
    $timezone = Chrono::parse('9:00 PST', '2020-07-06')[0];

    expect($dateTime->text)->toBe('2014-04-18 13:00 - 16:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2014-04-18 13:00:00')
        ->and($dateTime->end?->date()->toDateTimeString())->toBe('2014-04-18 16:00:00')
        ->and($range->text)->toBe('3:30-4:30pm')
        ->and($range->start->date()->toDateTimeString())->toBe('2020-07-06 15:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2020-07-06 16:30:00')
        ->and($timezone->text)->toBe('9:00 PST')
        ->and($timezone->start->get('hour'))->toBe(9)
        ->and($timezone->start->get('minute'))->toBe(0)
        ->and($timezone->start->get('timezoneOffset'))->toBe(-480);
});

it('parses quoted english expressions', function () {
    $parenthesized = Chrono::parse('Want to meet for dinner (5pm EST)?', '2020-07-06')[0];
    $quotedRange = Chrono::parse("between '3:30-4:30pm'", '2020-07-06')[0];
    $quotedDate = Chrono::parse("The date is '2014-04-18'")[0];

    expect($parenthesized->text)->toContain('5pm EST')
        ->and($quotedRange->text)->toContain('3:30-4:30pm')
        ->and($quotedRange->start->date()->toDateTimeString())->toBe('2020-07-06 15:30:00')
        ->and($quotedRange->end?->date()->toDateTimeString())->toBe('2020-07-06 16:30:00')
        ->and($quotedDate->text)->toContain('2014-04-18')
        ->and($quotedDate->start->date()->toDateTimeString())->toBe('2014-04-18 12:00:00');
});

it('parses random english text cases from upstream', function () {
    $email = Chrono::parse("Adam <Adam@supercalendar.com> написал(а):\nThe date is 02.07.2013")[0];
    $range = Chrono::parse('174 November 1,2001- March 31,2002')[0];
    $weekday = Chrono::parse('...Thursday, December 15, 2011 Best Available Rate ')[0];
    $flight = Chrono::parse('SUN 15SEP 11:05 AM - 12:50 PM')[0];
    $flightRange = Chrono::parse('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM')[0];
    $dateAfterTime = Chrono::parse('9:00 AM to 5:00 PM, Tuesday, 20 May 2013')[0];
    $relativeRange = Chrono::parse('Monday afternoon to last night', '2017-07-07')[0];
    $dashDateTime = Chrono::parse('07-27-2022, 02:00 AM', '2017-07-07')[0];

    expect($email->text)->toBe('02.07.2013')
        ->and($range->text)->toBe('November 1,2001- March 31,2002')
        ->and($weekday->text)->toBe('Thursday, December 15, 2011')
        ->and($weekday->start->get('year'))->toBe(2011)
        ->and($flight->text)->toBe('SUN 15SEP 11:05 AM - 12:50 PM')
        ->and($flight->end?->get('hour'))->toBe(12)
        ->and($flight->end?->get('minute'))->toBe(50)
        ->and($flightRange->text)->toBe('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM')
        ->and($flightRange->start->get('day'))->toBe(13)
        ->and($flightRange->start->get('hour'))->toBe(13)
        ->and($flightRange->start->get('minute'))->toBe(29)
        ->and($flightRange->end?->get('day'))->toBe(13)
        ->and($flightRange->end?->get('hour'))->toBe(15)
        ->and($flightRange->end?->get('minute'))->toBe(29)
        ->and($dateAfterTime->text)->toBe('9:00 AM to 5:00 PM, Tuesday, 20 May 2013')
        ->and($dateAfterTime->start->date()->toDateTimeString())->toBe('2013-05-20 09:00:00')
        ->and($dateAfterTime->end?->date()->toDateTimeString())->toBe('2013-05-20 17:00:00')
        ->and($relativeRange->text)->toBe('Monday afternoon to last night')
        ->and($relativeRange->start->get('day'))->toBe(3)
        ->and($relativeRange->start->get('month'))->toBe(7)
        ->and($relativeRange->end?->get('day'))->toBe(7)
        ->and($relativeRange->end?->get('month'))->toBe(7)
        ->and($dashDateTime->text)->toBe('07-27-2022, 02:00 AM')
        ->and($dashDateTime->start->date()->toDateTimeString())->toBe('2022-07-27 02:00:00');
});

it('parses multiple english date results', function () {
    $results = Chrono::parse('I will see you at 2:30. If not I will see you somewhere between 3:30-4:30pm', '2020-07-06');

    expect($results)->toHaveCount(2)
        ->and($results[0]->text)->toBe('at 2:30')
        ->and($results[0]->start->date()->toDateTimeString())->toBe('2020-07-06 02:30:00')
        ->and($results[0]->end)->toBeNull()
        ->and($results[1]->text)->toBe('3:30-4:30pm')
        ->and($results[1]->start->date()->toDateTimeString())->toBe('2020-07-06 15:30:00')
        ->and($results[1]->end?->date()->toDateTimeString())->toBe('2020-07-06 16:30:00');
});

it('parses english variants in strict mode and regional slash modes', function () {
    expect(Chrono::strict()->parseText('Tuesday'))->toBe([])
        ->and(Chrono::en()->parseText('6/10/2018')[0]->start->date()->toDateTimeString())
        ->toBe('2018-06-10 12:00:00')
        ->and(Chrono::enGb()->parseText('6/10/2018')[0]->start->date()->toDateTimeString())
        ->toBe('2018-10-06 12:00:00');
});
