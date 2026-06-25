<?php

use Carbon\CarbonImmutable;
use Chrono\Chrono;
use Chrono\ParsedComponents;
use Chrono\Weekday;

it('parses weekday prefixed month name dates', function () {
    $compact = Chrono::parse('Sun 15Sep', '2013-08-10')[0];
    $uppercaseCompact = Chrono::parse('SUN 15SEP', '2013-08-10')[0];
    $longWeekday = Chrono::parse('The Deadline is Tuesday, 10 January', '2012-08-10')[0];
    $shortWeekday = Chrono::parse('The Deadline is Tue, 10 January', '2012-08-10')[0];
    $middleEndianWeekday = Chrono::parse('The Deadline is Tuesday, January 10', '2012-08-10')[0];
    $sunDotted = Chrono::parse('Sun., March 6, 2016', '2012-08-10')[0];
    $punctuated = Chrono::parse('Wed, Jan 20th, 2016             ', '2012-08-10')[0];

    expect($compact->text)->toBe('Sun 15Sep')
        ->and($compact->index)->toBe(0)
        ->and($compact->start->get('year'))->toBe(2013)
        ->and($compact->start->get('month'))->toBe(9)
        ->and($compact->start->get('day'))->toBe(15)
        ->and($compact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($compact->start->isCertain('weekday'))->toBeTrue()
        ->and($uppercaseCompact->text)->toBe('SUN 15SEP')
        ->and($uppercaseCompact->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($longWeekday->text)->toBe('Tuesday, 10 January')
        ->and($longWeekday->index)->toBe(16)
        ->and($longWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($longWeekday->start->get('weekday'))->toBe(2)
        ->and($shortWeekday->text)->toBe('Tue, 10 January')
        ->and($shortWeekday->index)->toBe(16)
        ->and($shortWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($shortWeekday->start->get('weekday'))->toBe(2)
        ->and($middleEndianWeekday->text)->toBe('Tuesday, January 10')
        ->and($middleEndianWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($middleEndianWeekday->start->get('weekday'))->toBe(2)
        ->and($sunDotted->text)->toBe('Sun., March 6, 2016')
        ->and($sunDotted->start->date()->toDateTimeString())->toBe('2016-03-06 12:00:00')
        ->and($punctuated->text)->toBe('Wed, Jan 20th, 2016')
        ->and($punctuated->start->date()->toDateTimeString())->toBe('2016-01-20 12:00:00')
        ->and(Chrono::parseDate('Sunday, March, 6th 2016', '2012-08-10')?->toDateTimeString())
        ->toBe('2016-03-06 12:00:00');
});

it('parses weekday prefixed slash dates', function () {
    $longYear = Chrono::parse('The Deadline is Tuesday 11/3/2015', '2015-11-03')[0];
    $shortYear = Chrono::parse('Friday 12-30-16', '2012-08-10')[0];
    $littleEndian = Chrono::parse('Friday 30-12-16', '2012-08-10')[0];

    expect($longYear->text)->toBe('Tuesday 11/3/2015')
        ->and($longYear->start->date()->toDateTimeString())->toBe('2015-11-03 12:00:00')
        ->and($longYear->start->isCertain('weekday'))->toBeTrue()
        ->and($longYear->start->tags())->toContain('parser/ENSlashDateParser')
        ->and($shortYear->text)->toBe('Friday 12-30-16')
        ->and($shortYear->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00')
        ->and($littleEndian->text)->toBe('Friday 30-12-16')
        ->and($littleEndian->start->date()->toDateTimeString())->toBe('2016-12-30 12:00:00');
});

it('detects only-weekday components like upstream parsing components', function () {
    $weekday = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))->assign('weekday', 2);
    $weekdayWithYear = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))
        ->assign('weekday', 2)
        ->assign('year', 2026);
    $weekdayWithHour = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))
        ->assign('weekday', 2)
        ->assign('hour', 8);
    $weekdayWithDay = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))
        ->assign('weekday', 2)
        ->assign('day', 23);

    expect($weekday->isOnlyWeekdayComponent())->toBeTrue()
        ->and($weekdayWithYear->isOnlyWeekdayComponent())->toBeTrue()
        ->and($weekdayWithHour->isOnlyWeekdayComponent())->toBeTrue()
        ->and($weekdayWithDay->isOnlyWeekdayComponent())->toBeFalse();
});

it('parses weekdays', function () {
    $monday = Chrono::parse('Monday', '2012-08-09')[0];
    $thursday = Chrono::parse('Thursday', '2012-08-09')[0];
    $sunday = Chrono::parse('Sunday', '2012-08-09')[0];
    $date = Chrono::parseDate('next Friday at 4pm', '2026-06-23 09:00');

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('Monday')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($thursday->index)->toBe(0)
        ->and($thursday->text)->toBe('Thursday')
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($sunday->index)->toBe(0)
        ->and($sunday->text)->toBe('Sunday')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(Weekday::SUNDAY->value)
        ->and($date?->toDateTimeString())->toBe('2026-07-03 16:00:00');
});

it('parses past and postfix week weekday modifiers', function () {
    $last = Chrono::parse('The Deadline is last Friday...', '2012-08-09')[0];
    $past = Chrono::parse('The Deadline is past Friday...', '2012-08-09')[0];
    $nextWeek = Chrono::parse("Let's have a meeting on Friday next week", '2015-04-18')[0];
    $nextWeekWithComma = Chrono::parse('I plan on taking the day off on Tuesday, next week', '2015-04-18')[0];

    expect($last->index)->toBe(16)
        ->and($last->text)->toBe('last Friday')
        ->and($last->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($last->start->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($past->index)->toBe(16)
        ->and($past->text)->toBe('past Friday')
        ->and($past->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($past->start->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($nextWeek->index)->toBe(21)
        ->and($nextWeek->text)->toBe('on Friday next week')
        ->and($nextWeek->start->date()->toDateTimeString())->toBe('2015-04-24 12:00:00')
        ->and($nextWeek->start->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($nextWeekWithComma->index)->toBe(29)
        ->and($nextWeekWithComma->text)->toBe('on Tuesday, next week')
        ->and($nextWeekWithComma->start->date()->toDateTimeString())->toBe('2015-04-21 12:00:00')
        ->and($nextWeekWithComma->start->get('weekday'))->toBe(Weekday::TUESDAY->value);
});

it('parses weekdays with casual times', function () {
    $result = Chrono::casual()->parseText('Lets meet on Tuesday morning', '2015-04-18')[0];

    expect($result->index)->toBe(10)
        ->and($result->text)->toBe('on Tuesday morning')
        ->and($result->start->date()->toDateTimeString())->toBe('2015-04-21 06:00:00')
        ->and($result->start->get('weekday'))->toBe(Weekday::TUESDAY->value);
});

it('merges weekday overlaps with explicit dates', function () {
    $monthName = Chrono::casual()->parseText('Sunday, December 7, 2014', '2012-08-09')[0];
    $slashDate = Chrono::casual()->parseText('Sunday 12/7/2014', '2012-08-09')[0];

    expect($monthName->text)->toBe('Sunday, December 7, 2014')
        ->and($monthName->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthName->start->get('weekday'))->toBe(Weekday::SUNDAY->value)
        ->and($monthName->start->isCertain('weekday'))->toBeTrue()
        ->and($slashDate->text)->toBe('Sunday 12/7/2014')
        ->and($slashDate->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($slashDate->start->get('weekday'))->toBe(Weekday::SUNDAY->value)
        ->and($slashDate->start->isCertain('weekday'))->toBeTrue();
});

it('uses chrono weekday modifier semantics', function () {
    $monday = Chrono::parse('Monday', '2012-08-09')[0];
    $thursday = Chrono::parse('Thursday', '2012-08-09')[0];
    $sunday = Chrono::parse('Sunday', '2012-08-09')[0];
    $abbreviated = Chrono::parse('Mon.', '2012-08-09')[0];
    $nextWeek = Chrono::parse('Tuesday of next week', '2022-08-02')[0];
    $lastWeek = Chrono::parse('Tuesday of last week', '2022-08-02')[0];
    $thisWeek = Chrono::parse('Wednesday of this week', '2022-08-02')[0];
    $thisSameDay = Chrono::parse('Tuesday of this week', '2022-08-02')[0];
    $nextFriday = Chrono::parse('Friday of next week', '2022-08-02')[0];
    $nextMonday = Chrono::parse('Monday of next week', '2022-08-02')[0];
    $lastFriday = Chrono::parse('Friday of last week', '2022-08-02')[0];
    $nextWeekWithTime = Chrono::parse('Tuesday of next week after 2pm', '2022-08-02')[0];
    $nextFridayWithTime = Chrono::parse('Friday of next week at 9am', '2022-08-02')[0];
    $sentence = Chrono::parse("Let's sync on Tuesday of next week", '2022-08-02')[0];

    expect($monday->start->date()->toDateTimeString())
        ->toBe('2012-08-06 12:00:00')
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->tags())->toContain('parser/ENWeekdayParser')
        ->and($thursday->text)->toBe('Thursday')
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($thursday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($sunday->text)->toBe('Sunday')
        ->and($sunday->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($sunday->start->get('weekday'))->toBe(Weekday::SUNDAY->value)
        ->and($abbreviated->text)->toBe('Mon.')
        ->and($abbreviated->start->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and(Chrono::parseDate('This Saturday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-06 12:00:00')
        ->and(Chrono::parseDate('This Sunday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-07 12:00:00')
        ->and(Chrono::parseDate('This Wednesday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-03 12:00:00')
        ->and(Chrono::parseDate('This Saturday', '2022-08-07')?->toDateTimeString())
        ->toBe('2022-08-13 12:00:00')
        ->and(Chrono::parseDate('This Sunday', '2022-08-07')?->toDateTimeString())
        ->toBe('2022-08-07 12:00:00')
        ->and(Chrono::parseDate('This Wednesday', '2022-08-07')?->toDateTimeString())
        ->toBe('2022-08-10 12:00:00')
        ->and(Chrono::parseDate('Last Saturday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-07-30 12:00:00')
        ->and(Chrono::parseDate('Last Sunday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-07-31 12:00:00')
        ->and(Chrono::parseDate('Last Wednesday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-07-27 12:00:00')
        ->and(Chrono::parseDate('Next Saturday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-13 12:00:00')
        ->and(Chrono::parseDate('Next Sunday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-14 12:00:00')
        ->and(Chrono::parseDate('Next Wednesday', '2022-08-02')?->toDateTimeString())
        ->toBe('2022-08-10 12:00:00')
        ->and(Chrono::parseDate('Next Saturday', '2022-08-06')?->toDateTimeString())
        ->toBe('2022-08-13 12:00:00')
        ->and(Chrono::parseDate('Next Sunday', '2022-08-06')?->toDateTimeString())
        ->toBe('2022-08-14 12:00:00')
        ->and(Chrono::parseDate('Next Wednesday', '2022-08-06')?->toDateTimeString())
        ->toBe('2022-08-10 12:00:00')
        ->and(Chrono::parseDate('Next Saturday', '2022-08-07')?->toDateTimeString())
        ->toBe('2022-08-13 12:00:00')
        ->and(Chrono::parseDate('Next Sunday', '2022-08-07')?->toDateTimeString())
        ->toBe('2022-08-14 12:00:00')
        ->and(Chrono::parseDate('Next Wednesday', '2022-08-07')?->toDateTimeString())
        ->toBe('2022-08-10 12:00:00')
        ->and($nextWeek->text)->toBe('Tuesday of next week')
        ->and($nextWeek->start->date()->toDateTimeString())->toBe('2022-08-09 12:00:00')
        ->and($nextWeek->start->get('weekday'))->toBe(Weekday::TUESDAY->value)
        ->and($nextFriday->start->date()->toDateTimeString())->toBe('2022-08-12 12:00:00')
        ->and($nextMonday->start->date()->toDateTimeString())->toBe('2022-08-08 12:00:00')
        ->and($lastWeek->text)->toBe('Tuesday of last week')
        ->and($lastWeek->start->date()->toDateTimeString())->toBe('2022-07-26 12:00:00')
        ->and($lastFriday->start->date()->toDateTimeString())->toBe('2022-07-29 12:00:00')
        ->and($thisWeek->text)->toBe('Wednesday of this week')
        ->and($thisWeek->start->date()->toDateTimeString())->toBe('2022-08-03 12:00:00')
        ->and($thisSameDay->start->date()->toDateTimeString())->toBe('2022-08-02 12:00:00')
        ->and($nextWeekWithTime->text)->toBe('Tuesday of next week after 2pm')
        ->and($nextWeekWithTime->start->date()->toDateTimeString())->toBe('2022-08-09 14:00:00')
        ->and($nextWeekWithTime->start->get('weekday'))->toBe(Weekday::TUESDAY->value)
        ->and($nextFridayWithTime->text)->toBe('Friday of next week at 9am')
        ->and($nextFridayWithTime->start->date()->toDateTimeString())->toBe('2022-08-12 09:00:00')
        ->and($sentence->index)->toBe(11)
        ->and($sentence->text)->toBe('on Tuesday of next week')
        ->and($sentence->start->date()->toDateTimeString())->toBe('2022-08-09 12:00:00');
});

it('parses weekend and weekday mentions', function () {
    expect(Chrono::parseDate('last weekend', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-13 12:00:00')
        ->and(Chrono::parseDate('this weekend', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-19 12:00:00')
        ->and(Chrono::parseDate('next weekend', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-26 12:00:00')
        ->and(Chrono::parseDate('last weekday', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-17 12:00:00')
        ->and(Chrono::parseDate('next weekday', '2024-10-18 12:00')?->toDateTimeString())
        ->toBe('2024-10-21 12:00:00')
        ->and(Chrono::parseDate('last weekday', '2024-10-19 12:00')?->toDateTimeString())
        ->toBe('2024-10-18 12:00:00')
        ->and(Chrono::parseDate('next weekday', '2024-10-19 12:00')?->toDateTimeString())
        ->toBe('2024-10-21 12:00:00');
});

it('parses weekday ranges', function () {
    $crossWeek = Chrono::parse('Friday to Monday', '2023-04-09')[0];
    $sameWeek = Chrono::parse('Monday to Friday', '2023-04-09')[0];

    expect($crossWeek->start->date()->toDateTimeString())->toBe('2023-04-07 12:00:00')
        ->and($crossWeek->start->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($crossWeek->end?->date()->toDateTimeString())->toBe('2023-04-10 12:00:00')
        ->and($crossWeek->end?->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($sameWeek->start->date()->toDateTimeString())->toBe('2023-04-10 12:00:00')
        ->and($sameWeek->start->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($sameWeek->end?->date()->toDateTimeString())->toBe('2023-04-14 12:00:00')
        ->and($sameWeek->end?->get('weekday'))->toBe(Weekday::FRIDAY->value);
});

it('parses forward weekday ranges', function () {
    $monday = Chrono::parse('Monday (forward dates only)', '2012-08-09', ['forwardDate' => true])[0];
    $sundayMorning = Chrono::parse('sunday morning', '2021-08-15 20:00', ['forwardDate' => true])[0];
    $result = Chrono::parse('vacation monday - friday', '2019-06-13 12:00', ['forwardDate' => true])[0];
    $thisRange = Chrono::parse('this Friday to this Monday', '2016-08-04', ['forwardDate' => true])[0];

    expect($monday->text)->toBe('Monday')
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($sundayMorning->text)->toBe('sunday morning')
        ->and($sundayMorning->start->date()->toDateTimeString())->toBe('2021-08-22 06:00:00')
        ->and($sundayMorning->start->get('weekday'))->toBe(Weekday::SUNDAY->value)
        ->and($sundayMorning->start->isCertain('weekday'))->toBeTrue()
        ->and($sundayMorning->start->isCertain('day'))->toBeFalse()
        ->and($result->text)->toBe('monday - friday')
        ->and($result->start->date()->toDateTimeString())->toBe('2019-06-17 12:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2019-06-21 12:00:00')
        ->and($thisRange->text)->toBe('this Friday to this Monday')
        ->and($thisRange->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($thisRange->start->isCertain('weekday'))->toBeTrue()
        ->and($thisRange->start->isCertain('day'))->toBeFalse()
        ->and($thisRange->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($thisRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($thisRange->end?->isCertain('day'))->toBeFalse();
});

it('parses weekday time ranges', function () {
    $result = Chrono::parse('timeoff monday 7 to 9am', '2019-06-13 12:00', ['forwardDate' => true])[0];

    expect($result->text)->toBe('monday 7 to 9am')
        ->and($result->start->date()->toDateTimeString())->toBe('2019-06-17 07:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2019-06-17 09:00:00');
});

it('moves weekday range starts back when the end is earlier', function () {
    $result = Chrono::parse('Monday afternoon to last night', '2017-07-07 00:00')[0];

    expect($result->text)->toBe('Monday afternoon to last night')
        ->and($result->start->date()->toDateTimeString())->toBe('2017-07-03 15:00:00')
        ->and($result->end?->date()->toDateTimeString())->toBe('2017-07-07 00:00:00');
});
