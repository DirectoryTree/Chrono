<?php

use Chrono\Chrono;

it('parses slash dates with forward date option', function () {
    $date = Chrono::parseDate('Book 6/20', '2026-06-23 09:00', ['forwardDate' => true]);
    $monthDay = Chrono::parse('5/31', '1999-06-01', ['forwardDate' => true])[0];
    $dateTime = Chrono::parse('1/8 at 12pm', '2021-09-25 12:00:00', ['forwardDate' => true])[0];

    expect($date?->toDateTimeString())->toBe('2027-06-20 12:00:00')
        ->and($monthDay->text)->toBe('5/31')
        ->and($monthDay->start->get('year'))->toBe(2000)
        ->and($monthDay->start->get('month'))->toBe(5)
        ->and($monthDay->start->get('day'))->toBe(31)
        ->and($monthDay->start->isCertain('year'))->toBeFalse()
        ->and($monthDay->start->date()->toDateTimeString())->toBe('2000-05-31 12:00:00')
        ->and($dateTime->text)->toBe('1/8 at 12pm')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2022-01-08 12:00:00');
});

it('uses the closest year for slash dates without explicit years', function () {
    expect(Chrono::parseDate('1/1', '2012-12-31 09:00')?->toDateTimeString())
        ->toBe('2013-01-01 12:00:00')
        ->and(Chrono::parseDate('12/31', '2012-01-01 09:00')?->toDateTimeString())
        ->toBe('2011-12-31 12:00:00')
        ->and(Chrono::parseDate('12/31', '2012-01-01 09:00', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2012-12-31 12:00:00');
});

it('parses slash month year shorthand', function () {
    $offset = Chrono::parse('    04/2016   ', '2012-08-10')[0];
    $result = Chrono::parse('The event is going ahead (04/2016)', '2012-08-10')[0];
    $published = Chrono::parse('Published: 06/2004', '2012-08-10')[0];

    expect($offset->text)->toBe('04/2016')
        ->and($offset->index)->toBe(4)
        ->and($result->text)->toBe('04/2016')
        ->and($result->index)->toBe(26)
        ->and($result->start->get('year'))->toBe(2016)
        ->and($result->start->get('month'))->toBe(4)
        ->and($result->start->get('day'))->toBe(1)
        ->and($result->start->date()->toDateTimeString())->toBe('2016-04-01 12:00:00')
        ->and($result->start->isCertain('year'))->toBeTrue()
        ->and($result->start->isCertain('month'))->toBeTrue()
        ->and($result->start->isCertain('day'))->toBeFalse()
        ->and($published->text)->toBe('06/2004')
        ->and($published->index)->toBe(11)
        ->and($published->start->date()->toDateTimeString())->toBe('2004-06-01 12:00:00');
});

it('parses slash dates with leading slash and inferred day month order', function () {
    $plain = Chrono::parse('8/10/2012', '2012-08-10')[0];
    $colonPrefixed = Chrono::parse(': 8/1/2012', '2012-08-10')[0];
    $deadline = Chrono::parse('The Deadline is 8/10/2012', '2012-08-10')[0];
    $weekday = Chrono::parse('The Deadline is Tuesday 11/3/2015', '2015-11-03')[0];
    $strict = Chrono::strict()->parseText('2/28/2014', '2012-08-10')[0];
    $strictDash = Chrono::strict()->parseText('12-30-16', '2012-08-10')[0];
    $strictWeekdayDash = Chrono::strict()->parseText('Friday 12-30-16', '2012-08-10')[0];
    $short = Chrono::parse('8/10', '2012-08-10')[0];
    $twoDigitPastYear = Chrono::parse('8/10/82', '2012-08-10')[0];

    expect($plain->text)
        ->toBe('8/10/2012')
        ->and($plain->index)->toBe(0)
        ->and($plain->start->get('year'))->toBe(2012)
        ->and($plain->start->get('month'))->toBe(8)
        ->and($plain->start->get('day'))->toBe(10)
        ->and($plain->start->isCertain('year'))->toBeTrue()
        ->and($plain->start->isCertain('month'))->toBeTrue()
        ->and($plain->start->isCertain('day'))->toBeTrue()
        ->and($colonPrefixed->text)->toBe('8/1/2012')
        ->and($colonPrefixed->index)->toBe(2)
        ->and($colonPrefixed->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($deadline->text)->toBe('8/10/2012')
        ->and($deadline->index)->toBe(16)
        ->and($deadline->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($weekday->text)->toBe('Tuesday 11/3/2015')
        ->and($weekday->index)->toBe(16)
        ->and($weekday->start->date()->toDateTimeString())->toBe('2015-11-03 12:00:00')
        ->and($strict->text)->toBe('2/28/2014')
        ->and($strictDash->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($strictWeekdayDash->text)->toBe('Friday 12-30-16')
        ->and($strictWeekdayDash->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($short->text)->toBe('8/10')
        ->and($short->start->get('year'))->toBe(2012)
        ->and($short->start->get('month'))->toBe(8)
        ->and($short->start->get('day'))->toBe(10)
        ->and($short->start->isCertain('year'))->toBeFalse()
        ->and($short->start->isCertain('month'))->toBeTrue()
        ->and($short->start->isCertain('day'))->toBeTrue()
        ->and($plain->tags())->toContain('parser/SlashDateFormatParser')
        ->and($plain->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($twoDigitPastYear->start->get('year'))->toBe(1982)
        ->and($twoDigitPastYear->start->date()->toDateTimeString())->toBe('1982-08-10 12:00:00')
        ->and(Chrono::parse('/05/25/2015', '2012-08-10')[0]->text)
        ->toBe('/05/25/2015')
        ->and(Chrono::parseDate('/05/25/2015', '2012-08-10')?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parse('25/05/2015', '2012-08-10')[0]->text)
        ->toBe('25/05/2015')
        ->and(Chrono::parseDate('25/05/2015', '2012-08-10')?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parse('14/4 90', '2012-08-10')[0]->text)
        ->toBe('14/4')
        ->and(Chrono::parseDate('14/4 90', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-04-14 12:00:00');
});

it('parses upstream slash date splitter variants', function () {
    $reference = '2015-05-25';

    expect(Chrono::parseDate('2015-05-25', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('2015/05/25', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('2015.05.25', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('05-25-2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('05/25/2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('05.25.2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('/05/25/2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00')
        ->and(Chrono::parseDate('25/05/2015', $reference)?->toDateTimeString())
        ->toBe('2015-05-25 12:00:00');
});

it('supports british english slash dates', function () {
    $british = Chrono::gb()->parseText('Book 6/10/2018', '2012-08-10')[0];
    $upstreamBritish = Chrono::gb()->parseText('8/10/2012', '2012-08-10')[0];
    $strictDash = Chrono::strict()->parseText('30-12-16', '2012-08-10')[0];
    $weekday = Chrono::british()->parseText('Friday 30-12-16', '2012-08-10')[0];

    expect(Chrono::parseDate('6/10/2018', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-06-10 12:00:00')
        ->and($upstreamBritish->text)->toBe('8/10/2012')
        ->and($upstreamBritish->start->date()->toDateTimeString())->toBe('2012-10-08 12:00:00')
        ->and($british->text)->toBe('6/10/2018')
        ->and($british->start->date()->toDateTimeString())->toBe('2018-10-06 12:00:00')
        ->and(Chrono::enGb()->parseDateText('6/10/2018', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-10-06 12:00:00')
        ->and($strictDash->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($weekday->text)->toBe('Friday 30-12-16')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ENSlashDateParser');
});

it('parses slash dates with month names and attached times', function () {
    $plain = Chrono::parse('06/Nov/2023:06:36:02', '2012-08-10')[0];
    $zoned = Chrono::parse('06/Nov/2023:06:36:02 +0200', '2012-08-10')[0];

    $monthName = Chrono::gb()->parseText('8/Oct/2012', '2012-08-10')[0];
    $strictMonthName = Chrono::strict()->parseText('06/Nov/2023', '2012-08-10')[0];

    expect($monthName->text)->toBe('8/Oct/2012')
        ->and($monthName->index)->toBe(0)
        ->and($monthName->start->get('year'))->toBe(2012)
        ->and($monthName->start->get('month'))->toBe(10)
        ->and($monthName->start->get('day'))->toBe(8)
        ->and($monthName->start->date()->toDateTimeString())->toBe('2012-10-08 12:00:00')
        ->and($strictMonthName->text)->toBe('06/Nov/2023')
        ->and($strictMonthName->start->date()->toDateTimeString())->toBe('2023-11-06 12:00:00')
        ->and($plain->text)->toBe('06/Nov/2023:06:36:02')
        ->and($plain->start->date()->toDateTimeString())->toBe('2023-11-06 06:36:02')
        ->and($plain->start->tags())->toContain('parser/ENSlashDateParser')
        ->and($zoned->text)->toBe('06/Nov/2023:06:36:02 +0200')
        ->and($zoned->start->timezoneOffset())->toBe(120)
        ->and($zoned->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-11-06 06:36:02 +02:00');
});

it('merges slash dates followed by separated time expressions', function () {
    expect(Chrono::parseDate('05/31/2024 14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024.14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024:14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024-14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00');
});

it('parses slash date ranges', function () {
    $result = Chrono::en()->parseText('8/10/2012 - 8/15/2012', '2012-08-10')[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe('8/10/2012 - 8/15/2012')
        ->and($result->tags())->toContain('parser/SlashDateFormatParser')
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($result->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($result->end?->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($result->end?->tags())->toContain('parser/SlashDateFormatParser');
});

it('parses slash date ranges with times', function () {
    $plain = Chrono::parse('from 01/21/2021 10:00 to 01/01/2023 07:00', '2012-08-10 12:00')[0];
    $meridiem = Chrono::parse('08/08/2023, 09:15 AM to 08/29/2023, 09:15 AM', '2012-08-10 12:00')[0];

    expect($plain->start->date()->toDateTimeString())->toBe('2021-01-21 10:00:00')
        ->and($plain->end?->date()->toDateTimeString())->toBe('2023-01-01 07:00:00')
        ->and($plain->tags())->toContain('parser/SlashDateFormatParser')
        ->and($plain->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($plain->end?->tags())->toContain('parser/SlashDateFormatParser')
        ->and($meridiem->start->date()->toDateTimeString())->toBe('2023-08-08 09:15:00')
        ->and($meridiem->end?->date()->toDateTimeString())->toBe('2023-08-29 09:15:00');
});

it('rejects impossible upstream slash dates', function () {
    expect(Chrono::parse('8/32/2014', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('8/32', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('2/29/2014', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('2014/22/29', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('2014/13/22', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('80-32-89-89', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('02/29/2022', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('06/31/2022', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('06/-31/2022', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('18/13/2022', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('15/28/2022', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('4/13/1', '2012-08-10'))->toBe([]);
});
