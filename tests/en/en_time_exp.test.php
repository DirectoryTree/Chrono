<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\MergingCalculation;
use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Locales\En\Parsers\EnTimeExpressionParser;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\ParsedComponents;

it('merges date and time components like upstream helpers', function () {
    $date = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:00:00'), [
        'year' => 2022,
        'month' => 8,
        'day' => 27,
    ]);

    $time = new ParsedComponents(CarbonImmutable::parse('2022-08-27 09:30:00'), [
        'hour' => 9,
        'minute' => 30,
        'meridiem' => Meridiem::PM->value,
    ]);

    $merged = MergingCalculation::mergeDateTimeComponent($date, $time);

    expect($merged->date()->toDateTimeString())->toBe('2022-08-27 21:30:00')
        ->and($merged->isCertain('hour'))->toBeTrue()
        ->and($merged->isCertain('second'))->toBeFalse()
        ->and($merged->get('meridiem'))->toBe(Meridiem::PM);
});

it('derives component dates from constructor values like upstream parsing components', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2012-08-10 12:00:00'), [
        'year' => 2020,
        'month' => 4,
        'day' => 5,
        'hour' => 6,
        'minute' => 7,
    ]);

    expect($components->date()->toDateTimeString())->toBe('2020-04-05 06:07:00')
        ->and($components->get('year'))->toBe(2020)
        ->and($components->get('month'))->toBe(4)
        ->and($components->get('day'))->toBe(5)
        ->and($components->get('hour'))->toBe(6)
        ->and($components->get('minute'))->toBe(7);
});

it('merges dates followed by time ranges', function () {
    $result = Chrono::parse('Something happen on 2014-04-18 13:00 - 16:00 as')[0];

    expect($result->text)->toBe('2014-04-18 13:00 - 16:00')
        ->and($result->start->date()->toDateTimeString())->toBe('2014-04-18 13:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2014-04-18 16:00:00')
        ->and($result->tags())->toContain('refiner/mergeTrailingTimeRange');
});

it('merges time ranges followed by dates', function () {
    $result = Chrono::parse('9:00 AM to 5:00 PM, Tuesday, 20 May 2013', '2013-05-01')[0];

    expect($result->text)->toBe('9:00 AM to 5:00 PM, Tuesday, 20 May 2013')
        ->and($result->start->date()->toDateTimeString())->toBe('2013-05-20 09:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2013-05-20 17:00:00')
        ->and($result->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('parses compact month date time ranges', function () {
    $sameDay = Chrono::parse('SUN 15SEP 11:05 AM - 12:50 PM', '2013-08-01')[0];
    $crossDay = Chrono::parse('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM', '2013-08-01')[0];

    expect($sameDay->text)->toBe('SUN 15SEP 11:05 AM - 12:50 PM')
        ->and($sameDay->start->date()->toDateTimeString())->toBe('2013-09-15 11:05:00')
        ->and($sameDay->end?->date()->toDateTimeString())->toBe('2013-09-15 12:50:00')
        ->and($crossDay->text)->toBe('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM')
        ->and($crossDay->start->date()->toDateTimeString())->toBe('2013-09-13 13:29:00')
        ->and($crossDay->end?->date()->toDateTimeString())->toBe('2013-09-13 15:29:00');
});

it('parses standalone time expressions', function () {
    $result = Chrono::parse('  11 AM ', '2026-06-23 08:00:00')[0];
    $loose = Chrono::parse('  11 AM ', '2016-10-01 08:00:00')[0];
    $prefixed = Chrono::parse('2020 at  11 AM ', '2016-10-01 08:00:00')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2026-06-23 11:00:00')
        ->and($result->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($loose->index)->toBe(2)
        ->and($loose->text)->toBe('11 AM')
        ->and($loose->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($loose->start->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($prefixed->index)->toBe(5)
        ->and($prefixed->text)->toBe('at  11 AM');
});

it('parses standalone 24 hour time expressions with seconds', function () {
    $result = Chrono::parse('20:32:13', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('20:32:13')
        ->and($result->start->get('hour'))->toBe(20)
        ->and($result->start->get('minute'))->toBe(32)
        ->and($result->start->get('second'))->toBe(13)
        ->and($result->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-23 20:32:13')
        ->and($result->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($result->start->tags())->toContain('parser/ENTimeExpressionParser');
});

it('parses standalone time ranges', function () {
    $dash = Chrono::parse('10:00:00 - 21:45:00', '2016-10-01 08:00:00')[0];
    $result = Chrono::parse('10:00:00 until 21:45:00', '2026-06-23 08:00:00')[0];
    $till = Chrono::parse('10:00:00 till 21:45:00', '2016-10-01 11:00:00')[0];
    $through = Chrono::parse('10:00:00 through 21:45:00', '2016-10-01 11:00:00')[0];

    expect($dash->text)->toBe('10:00:00 - 21:45:00')
        ->and($dash->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($dash->start->get('hour'))->toBe(10)
        ->and($dash->start->get('minute'))->toBe(0)
        ->and($dash->start->get('second'))->toBe(0)
        ->and($dash->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($dash->start->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($dash->end?->get('hour'))->toBe(21)
        ->and($dash->end?->get('minute'))->toBe(45)
        ->and($dash->end?->get('second'))->toBe(0)
        ->and($dash->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($dash->end?->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-23 10:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2026-06-23 21:45:00')
        ->and($till->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:00')
        ->and($through->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:00');
});

it('moves merged date time ranges to the next day when the end time is earlier', function () {
    $oneAm = Chrono::parse('December 31, 2022 10:00 pm - 1:00 am', '2017-07-07')[0];
    $midnight = Chrono::parse('December 31, 2022 10:00 pm - 12:00 am', '2017-07-07')[0];

    expect($oneAm->start->date()->toDateTimeString())->toBe('2022-12-31 22:00:00')
        ->and($oneAm->end?->date()->toDateTimeString())->toBe('2023-01-01 01:00:00')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2022-12-31 22:00:00')
        ->and($midnight->end?->date()->toDateTimeString())->toBe('2023-01-01 00:00:00');
});

it('does not treat invalid following date fragments as time ranges', function () {
    $result = Chrono::parse('10:00:00 - 15/15', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('10:00:00')
        ->and($result->end)->toBeNull();
});

it('infers meridiem in time ranges', function () {
    $night = Chrono::parse('10 - 11 at night', '2016-10-01 08:00')[0];
    $startPm = Chrono::parse('8pm - 11', '2016-10-01 08:00')[0];
    $endPm = Chrono::parse('8 - 11pm', '2016-10-01 08:00')[0];
    $overnight = Chrono::parse('11pm - 3', '2016-10-01 08:00')[0];
    $plain = Chrono::parse('7 - 8', '2016-10-01 08:00')[0];
    $compactPm = Chrono::parse('1pm-3', '2012-08-10')[0];
    $compactAm = Chrono::parse('1am-3', '2012-08-10')[0];
    $compactOvernight = Chrono::parse('11pm-3', '2012-08-10')[0];
    $compactSameExplicit = Chrono::parse('10pm-10pm', '2012-08-10')[0];
    $compactSameImplied = Chrono::parse('10pm-10', '2012-08-10')[0];
    $endAm = Chrono::parse('12-3am', '2012-08-10')[0];
    $endPmNoon = Chrono::parse('12-3pm', '2012-08-10')[0];

    expect($night->start->date()->toDateTimeString())->toBe('2016-10-01 22:00:00')
        ->and($night->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($night->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($night->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($startPm->start->date()->toDateTimeString())->toBe('2016-10-01 20:00:00')
        ->and($startPm->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($endPm->start->date()->toDateTimeString())->toBe('2016-10-01 20:00:00')
        ->and($endPm->end?->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($endPm->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($endPm->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2016-10-02 03:00:00')
        ->and($overnight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($plain->start->date()->toDateTimeString())->toBe('2016-10-01 07:00:00')
        ->and($plain->end?->date()->toDateTimeString())->toBe('2016-10-01 08:00:00')
        ->and($compactPm->text)->toBe('1pm-3')
        ->and($compactPm->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($compactPm->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($compactPm->start->isCertain('meridiem'))->toBeTrue()
        ->and($compactPm->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($compactPm->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($compactPm->end?->isCertain('meridiem'))->toBeTrue()
        ->and($compactAm->start->date()->toDateTimeString())->toBe('2012-08-10 01:00:00')
        ->and($compactAm->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($compactAm->end?->date()->toDateTimeString())->toBe('2012-08-10 03:00:00')
        ->and($compactAm->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($compactAm->end?->isCertain('meridiem'))->toBeFalse()
        ->and($compactOvernight->start->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($compactOvernight->end?->date()->toDateTimeString())->toBe('2012-08-11 03:00:00')
        ->and($compactOvernight->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($compactSameExplicit->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($compactSameImplied->end?->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($endAm->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($endAm->end?->date()->toDateTimeString())->toBe('2012-08-10 03:00:00')
        ->and($endPmNoon->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($endPmNoon->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00');
});

it('moves standalone times forward when requested', function () {
    $earlyMorning = Chrono::parse('1am', '2022-05-26 01:57', ['forwardDate' => true])[0];
    $lateMorning = Chrono::parse('11am', '2016-10-01 12:00', ['forwardDate' => true])[0];
    $overnight = Chrono::parse('11am to 1am', '2016-10-01 12:00', ['forwardDate' => true])[0];
    $sameDayRange = Chrono::parse('10am to 12pm', '2016-10-01 11:00', ['forwardDate' => true])[0];

    expect($earlyMorning->start->date()->toDateTimeString())->toBe('2022-05-27 01:00:00')
        ->and($lateMorning->start->date()->toDateTimeString())->toBe('2016-10-02 11:00:00')
        ->and($overnight->start->date()->toDateTimeString())->toBe('2016-10-02 11:00:00')
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2016-10-03 01:00:00')
        ->and($sameDayRange->start->date()->toDateTimeString())->toBe('2016-10-02 10:00:00')
        ->and($sameDayRange->end?->date()->toDateTimeString())->toBe('2016-10-02 12:00:00');
});

it('moves standalone times forward from timezone references', function () {
    $result = Chrono::parse('1am', [
        'instant' => 'Wed May 26 2022 01:57:00 GMT-0500 (CDT)',
        'timezone' => 'CDT',
    ], ['forwardDate' => true])[0];

    expect($result->start->get('year'))->toBe(2022)
        ->and($result->start->get('month'))->toBe(5)
        ->and($result->start->get('day'))->toBe(27)
        ->and($result->start->get('hour'))->toBe(1);
});

it('merges time expressions followed by dates', function () {
    $monthDay = Chrono::parse('8:23 AM, Jul 9', '2016-10-01 08:00')[0];

    expect(Chrono::parseDate('14:15 05/31/2024', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and($monthDay->start->date()->toDateTimeString())
        ->toBe('2016-07-09 08:23:00')
        ->and($monthDay->start->isCertain('year'))->toBeFalse()
        ->and($monthDay->start->isCertain('month'))->toBeTrue()
        ->and($monthDay->start->isCertain('day'))->toBeTrue()
        ->and($monthDay->start->isCertain('hour'))->toBeTrue()
        ->and($monthDay->tags())->toContain('parser/ENTimeExpressionParser')
        ->and(Chrono::parse('8:23 AM ∙ Jul 9', '2016-10-01 08:00')[0]->text)
        ->toBe('8:23 AM ∙ Jul 9')
        ->and(Chrono::parseDate('8:23 AM ∙ Jul 9', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-07-09 08:23:00');
});

it('merges time expressions after dates with upstream separators', function () {
    expect(Chrono::parseDate('05/31/2024 14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024.14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024:14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00')
        ->and(Chrono::parseDate('05/31/2024-14:15', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2024-05-31 14:15:00');
});

it('parses time expressions with day period clues', function () {
    expect(Chrono::parseDate('1 at night', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 01:00:00')
        ->and(Chrono::parseDate('11 tonight', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 23:00:00')
        ->and(Chrono::parseDate('6 in the morning', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 06:00:00')
        ->and(Chrono::parseDate('1 in the afternoon', '2026-06-23 08:00:00')?->toDateTimeString())
        ->toBe('2026-06-23 13:00:00')
        ->and(Chrono::parseDate('6 in the afternoon', '2016-10-01 08:00:00')?->toDateTimeString())
        ->toBe('2016-10-01 18:00:00');
});

it('parses casual time number expressions', function () {
    $atOne = Chrono::casual()->parseText('at 1')[0];
    $atTwelve = Chrono::casual()->parseText('at 12')[0];
    $atTwelveThirty = Chrono::casual()->parseText('at 12.30')[0];

    expect($atOne->text)->toBe('at 1')
        ->and($atOne->start->get('hour'))->toBe(1)
        ->and($atTwelve->text)->toBe('at 12')
        ->and($atTwelve->start->get('hour'))->toBe(12)
        ->and($atTwelveThirty->text)->toBe('at 12.30')
        ->and($atTwelveThirty->start->get('hour'))->toBe(12)
        ->and($atTwelveThirty->start->get('minute'))->toBe(30);
});

it('rejects upstream English time expression false positives', function () {
    expect(Chrono::parse('2020'))->toBe([])
        ->and(Chrono::parse('2020  '))->toBe([])
        ->and(Chrono::parse('2019 to 2020'))->toBe([])
        ->and(Chrono::parse("I'm at 101,194 points!"))->toBe([])
        ->and(Chrono::parse("I'm at 101 points!"))->toBe([])
        ->and(Chrono::parse("I'm at 10.1"))->toBe([])
        ->and(Chrono::parse("I'm at 10.1 - 10.12"))->toBe([])
        ->and(Chrono::parse("I'm at 10 - 10.1"))->toBe([])
        ->and(Chrono::strict()->parseText("I'm at 10"))->toBe([])
        ->and(Chrono::strict()->parseText("I'm at 10 - 20"))->toBe([])
        ->and(Chrono::strict()->parseText('7-730'))->toBe([]);
});

it('parses upstream top-level English date and time integrations', function () {
    $dateTimeRange = Chrono::parse('Something happen on 2014-04-18 13:00 - 16:00 as')[0];
    $timeRange = Chrono::parse('between 3:30-4:30pm', '2020-07-06')[0];
    $timezoneTime = Chrono::parse('9:00 PST', '2020-07-06')[0];
    $quotedRange = Chrono::parse("between '3:30-4:30pm'", '2020-07-06')[0];
    $quotedDate = Chrono::parse("The date is '2014-04-18'")[0];

    expect($dateTimeRange->text)->toBe('2014-04-18 13:00 - 16:00')
        ->and($dateTimeRange->start->date()->toDateTimeString())->toBe('2014-04-18 13:00:00')
        ->and($dateTimeRange->end?->date()->toDateTimeString())->toBe('2014-04-18 16:00:00')
        ->and($timeRange->text)->toBe('3:30-4:30pm')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2020-07-06 15:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2020-07-06 16:30:00')
        ->and($timezoneTime->text)->toBe('9:00 PST')
        ->and($timezoneTime->start->get('hour'))->toBe(9)
        ->and($timezoneTime->start->get('minute'))->toBe(0)
        ->and($timezoneTime->start->timezoneOffset())->toBe(-480)
        ->and($quotedRange->text)->toBe('3:30-4:30pm')
        ->and($quotedRange->start->date()->toDateTimeString())->toBe('2020-07-06 15:30:00')
        ->and($quotedRange->end?->date()->toDateTimeString())->toBe('2020-07-06 16:30:00')
        ->and($quotedDate->text)->toBe('2014-04-18')
        ->and($quotedDate->start->date()->toDateTimeString())->toBe('2014-04-18 12:00:00');
});

it('parses upstream top-level English random text integrations', function () {
    $email = Chrono::parse("Adam <Adam@supercalendar.com> написал(а):\nThe date is 02.07.2013")[0];
    $hotel = Chrono::parse('...Thursday, December 15, 2011 Best Available Rate ')[0];
    $shortFlight = Chrono::parse('SUN 15SEP 11:05 AM - 12:50 PM')[0];
    $longFlight = Chrono::parse('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM')[0];
    $timeBeforeDate = Chrono::parse('9:00 AM to 5:00 PM, Tuesday, 20 May 2013')[0];
    $casualRange = Chrono::parse('Monday afternoon to last night', '2017-07-07')[0];
    $dashDateTime = Chrono::parse('07-27-2022, 02:00 AM', '2017-07-07')[0];

    expect($email->text)->toBe('02.07.2013')
        ->and($hotel->text)->toBe('Thursday, December 15, 2011')
        ->and($hotel->start->get('year'))->toBe(2011)
        ->and($shortFlight->text)->toBe('SUN 15SEP 11:05 AM - 12:50 PM')
        ->and($shortFlight->end?->get('hour'))->toBe(12)
        ->and($shortFlight->end?->get('minute'))->toBe(50)
        ->and($longFlight->text)->toBe('FRI 13SEP 1:29 PM - FRI 13SEP 3:29 PM')
        ->and($longFlight->start->get('day'))->toBe(13)
        ->and($longFlight->start->get('hour'))->toBe(13)
        ->and($longFlight->start->get('minute'))->toBe(29)
        ->and($longFlight->end?->get('day'))->toBe(13)
        ->and($longFlight->end?->get('hour'))->toBe(15)
        ->and($longFlight->end?->get('minute'))->toBe(29)
        ->and($timeBeforeDate->text)->toBe('9:00 AM to 5:00 PM, Tuesday, 20 May 2013')
        ->and($timeBeforeDate->start->date()->toDateTimeString())->toBe('2013-05-20 09:00:00')
        ->and($timeBeforeDate->end?->date()->toDateTimeString())->toBe('2013-05-20 17:00:00')
        ->and($casualRange->text)->toBe('Monday afternoon to last night')
        ->and($casualRange->start->get('day'))->toBe(3)
        ->and($casualRange->start->get('month'))->toBe(7)
        ->and($casualRange->end?->get('day'))->toBe(7)
        ->and($casualRange->end?->get('month'))->toBe(7)
        ->and($dashDateTime->text)->toBe('07-27-2022, 02:00 AM')
        ->and($dashDateTime->start->get('day'))->toBe(27)
        ->and($dashDateTime->start->get('month'))->toBe(7)
        ->and($dashDateTime->start->get('year'))->toBe(2022)
        ->and($dashDateTime->start->get('hour'))->toBe(2)
        ->and($dashDateTime->start->get('meridiem'))->toBe(Meridiem::AM);
});

it('parses upstream top-level multiple results and parser customization', function () {
    $results = Chrono::parse('I will see you at 2:30. If not I will see you somewhere between 3:30-4:30pm', '2020-07-06');
    $withoutTime = Chrono::casual()->withoutParser(EnTimeExpressionParser::class)
        ->parseText('Thursday 9AM', '2020-11-29');

    expect($results)->toHaveCount(2)
        ->and($results[0]->text)->toBe('at 2:30')
        ->and($results[0]->start->get('year'))->toBe(2020)
        ->and($results[0]->start->get('month'))->toBe(7)
        ->and($results[0]->start->get('day'))->toBe(6)
        ->and($results[0]->start->get('hour'))->toBe(2)
        ->and($results[0]->end)->toBeNull()
        ->and($results[1]->text)->toBe('3:30-4:30pm')
        ->and($results[1]->start->get('hour'))->toBe(15)
        ->and($results[1]->start->get('minute'))->toBe(30)
        ->and($results[1]->end?->get('hour'))->toBe(16)
        ->and($results[1]->end?->get('minute'))->toBe(30)
        ->and($withoutTime)->toHaveCount(1)
        ->and($withoutTime[0]->text)->toBe('Thursday')
        ->and($withoutTime[0]->start->get('year'))->toBe(2020)
        ->and($withoutTime[0]->start->get('month'))->toBe(11)
        ->and($withoutTime[0]->start->get('day'))->toBe(26);
});
