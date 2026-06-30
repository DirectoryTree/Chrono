<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\CasualReferences;
use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Locales\En\Refiners\EnExtractYearSuffixRefiner;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Weekday;

it('extracts english year suffixes from unknown-year dates', function () {
    $refiner = new EnExtractYearSuffixRefiner;
    $reference = Reference::make('2012-08-10');
    $options = new Options;
    $date = CarbonImmutable::parse('2012-03-14 12:00');

    $components = new ParsedComponents($date);
    $components->assign('month', 3);
    $components->assign('day', 14);

    $result = new ParsedResult(0, 'March 14', $components);
    $refined = $refiner->refine('March 14 2026', [$result], $reference, $options)[0];

    $shortComponents = new ParsedComponents($date);
    $shortComponents->assign('month', 3);
    $shortComponents->assign('day', 14);

    $shortResult = new ParsedResult(0, 'March 14', $shortComponents);
    $shortRefined = $refiner->refine('March 14 90', [$shortResult], $reference, $options)[0];

    expect($refined->text)->toBe('March 14 2026')
        ->and($refined->start->get('year'))->toBe(2026)
        ->and($refined->start->isCertain('year'))->toBeTrue()
        ->and($refined->tags())->toContain('refiner/extractYearSuffix')
        ->and($shortRefined->text)->toBe('March 14')
        ->and($shortRefined->start->isCertain('year'))->toBeFalse();
});

it('parses casual dates with time', function () {
    $date = Chrono::parseDate('tomorrow at 4pm', '2026-06-23 09:00');

    expect($date?->toDateTimeString())->toBe('2026-06-24 16:00:00');
});

it('preserves the reference timestamp for now', function () {
    $result = Chrono::parse('The Deadline is now', '2012-08-10 08:09:10.011')[0];

    expect($result->text)->toBe('now')
        ->and($result->start->get('hour'))->toBe(8)
        ->and($result->start->get('minute'))->toBe(9)
        ->and($result->start->get('second'))->toBe(10)
        ->and($result->start->get('millisecond'))->toBe(11)
        ->and($result->start->isCertain('millisecond'))->toBeTrue()
        ->and($result->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011');
});

it('parses casual date aliases', function () {
    expect(Chrono::parseDate('tmr', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-24 09:00:00')
        ->and(Chrono::parseDate('tmrw', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-24 09:00:00')
        ->and(Chrono::parseDate('overmorrow', '2026-06-23 09:00')?->toDateTimeString())
        ->toBe('2026-06-25 09:00:00');
});

it('creates casual last-night components like upstream common references', function () {
    $early = CasualReferences::lastNight(Reference::make('2012-08-10 01:00'));
    $morningBoundary = CasualReferences::lastNight(Reference::make('2012-08-10 06:00'));
    $midday = CasualReferences::lastNight(Reference::make('2012-08-10 12:00'));

    expect($early->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($early->get('hour'))->toBe(0)
        ->and($morningBoundary->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($midday->date()->toDateTimeString())->toBe('2012-08-10 00:00:00');
});

it('parses upstream casual date and time combinations', function () {
    $todayAtFive = Chrono::parse('The Deadline is today 5PM', '2012-08-10 12:00')[0];
    $tomorrowNoon = Chrono::parse('Tomorrow at noon', '2012-08-10 14:00')[0];
    $firstRange = Chrono::parse('The event is today - next friday', '2012-08-04 12:00')[0];
    $secondRange = Chrono::parse('The event is today - next friday', '2012-08-10 12:00')[0];

    expect($todayAtFive->index)->toBe(16)
        ->and($todayAtFive->text)->toBe('today 5PM')
        ->and($todayAtFive->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($todayAtFive->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tomorrowNoon->text)->toBe('Tomorrow at noon')
        ->and($tomorrowNoon->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($firstRange->index)->toBe(13)
        ->and($firstRange->text)->toBe('today - next friday')
        ->and($firstRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($firstRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($secondRange->index)->toBe(13)
        ->and($secondRange->text)->toBe('today - next friday')
        ->and($secondRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($secondRange->end?->date()->toDateTimeString())->toBe('2012-08-17 12:00:00');
});

it('parses last night relative to the reference time', function () {
    expect(Chrono::parseDate('last night', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and(Chrono::parseDate('last night', '2012-08-10 01:00')?->toDateTimeString())
        ->toBe('2012-08-10 00:00:00');
});

it('parses upstream casual standalone expressions', function () {
    $tonight = Chrono::parse('tonight', '2012-01-01 12:00')[0];
    $tonightEight = Chrono::parse('tonight 8pm', '2012-01-01 12:00')[0];
    $tonightAtEight = Chrono::parse('tonight at 8', '2012-01-01 12:00')[0];
    $tomorrowBefore = Chrono::parse('tomorrow before 4pm', '2012-01-01 12:00')[0];
    $tomorrowAfter = Chrono::parse('tomorrow after 4pm', '2012-01-01 12:00')[0];
    $thursday = Chrono::parse('thurs', '2012-08-10 12:00')[0];
    $thisEvening = Chrono::parse('this evening', '2016-10-01')[0];
    $yesterdayAfternoon = Chrono::parse('yesterday afternoon', '2016-10-01')[0];

    expect($tonight->text)->toBe('tonight')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($tonight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonightEight->text)->toBe('tonight 8pm')
        ->and($tonightEight->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($tonightEight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonightAtEight->text)->toBe('tonight at 8')
        ->and($tonightAtEight->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($tonightAtEight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tomorrowBefore->text)->toBe('tomorrow before 4pm')
        ->and($tomorrowBefore->start->date()->toDateTimeString())->toBe('2012-01-02 16:00:00')
        ->and($tomorrowAfter->text)->toBe('tomorrow after 4pm')
        ->and($tomorrowAfter->start->date()->toDateTimeString())->toBe('2012-01-02 16:00:00')
        ->and($thursday->text)->toBe('thurs')
        ->and($thursday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($thisEvening->text)->toBe('this evening')
        ->and($thisEvening->start->date()->toDateTimeString())->toBe('2016-10-01 20:00:00')
        ->and($yesterdayAfternoon->text)->toBe('yesterday afternoon')
        ->and($yesterdayAfternoon->start->date()->toDateTimeString())->toBe('2016-09-30 15:00:00');
});

it('parses casual times', function () {
    $morning = Chrono::parse('this morning', '2026-06-23 12:00')[0];

    expect($morning->start->date()->toDateTimeString())
        ->toBe('2026-06-23 06:00:00')
        ->and($morning->start->tags())->toContain('parser/ENCasualTimeParser')
        ->and(Chrono::parseDate('this afternoon', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-23 15:00:00')
        ->and(Chrono::parse('this afternoon at 3', '2016-10-01 08:00')[0]->text)
        ->toBe('this afternoon at 3')
        ->and(Chrono::parseDate('this afternoon at 3', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-10-01 15:00:00')
        ->and(Chrono::parseDate('this evening', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-23 20:00:00')
        ->and(Chrono::parseDate('noon', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-23 12:00:00')
        ->and(Chrono::parse('at 12', '2012-08-10')[0]->text)->toBe('at 12')
        ->and(Chrono::parse('at 12', '2012-08-10')[0]->start->get('hour'))->toBe(12)
        ->and(Chrono::parse('at 12.30', '2012-08-10')[0]->text)->toBe('at 12.30')
        ->and(Chrono::parse('at 12.30', '2012-08-10')[0]->start->get('hour'))->toBe(12)
        ->and(Chrono::parse('at 12.30', '2012-08-10')[0]->start->get('minute'))->toBe(30);
});

it('defaults explicit casual time milliseconds to zero', function () {
    $morning = Chrono::parse('morning', '2012-08-10 08:09:10.011')[0];
    $midnight = Chrono::parse('midnight', '2012-08-10 08:09:10.011')[0];

    expect($morning->start->get('millisecond'))->toBe(0)
        ->and($morning->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 06:00:00.000')
        ->and($midnight->start->get('millisecond'))->toBe(0)
        ->and($midnight->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-11 00:00:00.000');
});

it('parses midnight relative to the reference time', function () {
    expect(Chrono::parseDate('midnight', '2026-06-23 12:00')?->toDateTimeString())
        ->toBe('2026-06-24 00:00:00')
        ->and(Chrono::parseDate('midnight', '2026-06-23 01:00')?->toDateTimeString())
        ->toBe('2026-06-23 00:00:00');
});

it('merges casual dates followed by casual times', function () {
    $result = Chrono::parse('tomorrow morning', '2026-06-23 08:00')[0];
    $tonight = Chrono::parse('tonight at 8', '2012-01-01 12:00')[0];
    $midnight = Chrono::parse('at midnight on 12th August', '2012-08-10 15:00')[0];

    expect($result->text)->toBe('tomorrow morning')
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-24 06:00:00')
        ->and($result->start->isCertain('hour'))->toBeFalse()
        ->and($result->start->tags())->toContain('parser/ENCasualDateParser')
        ->and($result->start->tags())->toContain('parser/ENCasualTimeParser')
        ->and($result->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($tonight->text)->toBe('tonight at 8')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($midnight->text)->toBe('midnight on 12th August')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($midnight->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('merges casual date ranges with implied times', function () {
    $morningRange = Chrono::parse('annual leave from today morning to tomorrow', '2012-08-04 12:00')[0];
    $afternoonRange = Chrono::parse('annual leave from today to tomorrow afternoon', '2012-08-04 12:00')[0];

    expect($morningRange->text)->toBe('today morning to tomorrow')
        ->and($morningRange->start->date()->toDateTimeString())->toBe('2012-08-04 06:00:00')
        ->and($morningRange->start->isCertain('hour'))->toBeFalse()
        ->and($morningRange->start->tags())->toContain('parser/ENCasualTimeParser')
        ->and($morningRange->end?->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($morningRange->end?->isCertain('hour'))->toBeFalse()
        ->and($morningRange->tags())->toContain('refiner/mergeDateRange')
        ->and($afternoonRange->text)->toBe('today to tomorrow afternoon')
        ->and($afternoonRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($afternoonRange->start->isCertain('hour'))->toBeFalse()
        ->and($afternoonRange->end?->date()->toDateTimeString())->toBe('2012-08-05 15:00:00')
        ->and($afternoonRange->end?->isCertain('hour'))->toBeFalse()
        ->and($afternoonRange->end?->tags())->toContain('parser/ENCasualTimeParser')
        ->and($afternoonRange->tags())->toContain('refiner/mergeDateRange');
});

it('merges weekdays followed by casual times', function () {
    $result = Chrono::parse('Tuesday morning', '2026-06-23 08:00')[0];
    $prefixed = Chrono::parse('Lets meet on Tuesday morning', '2015-04-18')[0];

    expect($result->text)->toBe('Tuesday morning')
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-23 06:00:00')
        ->and($prefixed->index)->toBe(10)
        ->and($prefixed->text)->toBe('on Tuesday morning')
        ->and($prefixed->start->date()->toDateTimeString())->toBe('2015-04-21 06:00:00')
        ->and($prefixed->start->get('weekday'))->toBe(Weekday::TUESDAY->value);
});

it('parses upstream later and from-now relative duration variants with exact text', function () {
    $twoDays = Chrono::parse('2 days later', '2012-08-10 12:00')[0];
    $threeWeeks = Chrono::parse('3w later', '2012-07-10 10:00')[0];
    $threeMonths = Chrono::parse('3mo later', '2012-07-10 10:00')[0];
    $fromNowHours = Chrono::parse('   12 hrs from now', '2012-08-10 12:14')[0];
    $halfHour = Chrono::parse('   half An hour from now', '2012-08-10 12:14')[0];
    $articleDay = Chrono::parse('A days from now, we did something', '2012-08-10 12:00')[0];
    $outMinute = Chrono::parse('a min out', '2012-08-10 12:14')[0];

    expect($twoDays->text)->toBe('2 days later')
        ->and($twoDays->index)->toBe(0)
        ->and($twoDays->start->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($twoDays->tags())->toContain('result/relativeDate')
        ->and($twoDays->start->isCertain('day'))->toBeTrue()
        ->and($threeWeeks->text)->toBe('3w later')
        ->and($threeWeeks->start->date()->toDateTimeString())->toBe('2012-07-31 10:00:00')
        ->and($threeMonths->text)->toBe('3mo later')
        ->and($threeMonths->start->date()->toDateTimeString())->toBe('2012-10-10 10:00:00')
        ->and($fromNowHours->index)->toBe(3)
        ->and($fromNowHours->text)->toBe('12 hrs from now')
        ->and($fromNowHours->start->date()->toDateTimeString())->toBe('2012-08-11 00:14:00')
        ->and($fromNowHours->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($halfHour->text)->toBe('half An hour from now')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($articleDay->text)->toBe('A days from now')
        ->and($articleDay->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($outMinute->text)->toBe('a min out')
        ->and($outMinute->start->date()->toDateTimeString())->toBe('2012-08-10 12:15:00');
});

it('parses casual relative duration prefixes', function () {
    expect(Chrono::parseDate('next 2 weeks 3 days', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-10-18 12:00:00')
        ->and(Chrono::parseDate('after a year', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-10-01 12:00:00')
        ->and(Chrono::parseDate('next two quarters', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-04-01 12:00:00')
        ->and(Chrono::parseDate('after an hour', '2016-10-01 15:00')?->toDateTimeString())
        ->toBe('2016-10-01 16:00:00')
        ->and(Chrono::parseDate('last 2 weeks', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-17 12:00:00')
        ->and(Chrono::parseDate('past 2 days', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-29 12:00:00');
});

it('parses upstream casual relative unit prefixes with exact text', function () {
    $weeks = Chrono::parse('next 2 weeks', '2016-10-01 12:00')[0];
    $days = Chrono::parse('next 2 days', '2016-10-01 12:00')[0];
    $years = Chrono::parse('next two years', '2016-10-01 12:00')[0];
    $lastWeeks = Chrono::parse('last two weeks', '2016-10-01 12:00')[0];
    $combined = Chrono::parse('+2 months, 5 days', '2016-10-01 12:00')[0];

    expect($weeks->text)->toBe('next 2 weeks')
        ->and($weeks->tags())->toContain('result/relativeDate')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2016-10-15 12:00:00')
        ->and($days->text)->toBe('next 2 days')
        ->and($days->start->date()->toDateTimeString())->toBe('2016-10-03 12:00:00')
        ->and($years->text)->toBe('next two years')
        ->and($years->start->date()->toDateTimeString())->toBe('2018-10-01 12:00:00')
        ->and($lastWeeks->text)->toBe('last two weeks')
        ->and($lastWeeks->start->date()->toDateTimeString())->toBe('2016-09-17 12:00:00')
        ->and($combined->text)->toBe('+2 months, 5 days')
        ->and($combined->start->date()->toDateTimeString())->toBe('2016-12-06 12:00:00');
});

it('parses upstream signed casual relative duration variants', function () {
    $minutes = Chrono::casual()->parseText('+15 minutes', '2012-07-10 12:14')[0];
    $shortMinutes = Chrono::casual()->parseText('+15min', '2012-07-10 12:14')[0];
    $singleMinute = Chrono::casual()->parseText('+1m', '2012-07-10 12:14')[0];
    $spelledNegative = Chrono::parse('-2 hours 5 minutes', '2016-10-01 12:00')[0];

    expect($minutes->text)->toBe('+15 minutes')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2012-07-10 12:29:00')
        ->and($minutes->tags())->toContain('result/relativeDateAndTime')
        ->and($shortMinutes->text)->toBe('+15min')
        ->and($shortMinutes->start->date()->toDateTimeString())->toBe('2012-07-10 12:29:00')
        ->and($singleMinute->text)->toBe('+1m')
        ->and($singleMinute->start->date()->toDateTimeString())->toBe('2012-07-10 12:15:00')
        ->and($spelledNegative->text)->toBe('-2 hours 5 minutes')
        ->and($spelledNegative->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00');
});

it('merges casual date references with before and after durations', function () {
    expect(Chrono::parseDate('2 day before today', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-08 00:00:00')
        ->and(Chrono::parseDate('the day before yesterday', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-08 00:00:00')
        ->and(Chrono::parseDate('2 day before yesterday', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-07 00:00:00')
        ->and(Chrono::parseDate('a week before yesterday', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-02 00:00:00')
        ->and(Chrono::parseDate('2 day after today', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-12 00:00:00')
        ->and(Chrono::parseDate('the day after tomorrow', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-12 00:00:00')
        ->and(Chrono::parseDate('2 day after tomorrow', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-13 00:00:00')
        ->and(Chrono::parseDate('a week after tomorrow', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-18 00:00:00');
});

it('parses upstream casual numeric time expressions', function () {
    $one = Chrono::casual()->parseText('at 1')[0];
    $twelve = Chrono::casual()->parseText('at 12')[0];
    $twelveThirty = Chrono::casual()->parseText('at 12.30')[0];

    expect($one->text)->toBe('at 1')
        ->and($one->start->get('hour'))->toBe(1)
        ->and($twelve->text)->toBe('at 12')
        ->and($twelve->start->get('hour'))->toBe(12)
        ->and($twelveThirty->text)->toBe('at 12.30')
        ->and($twelveThirty->start->get('hour'))->toBe(12)
        ->and($twelveThirty->start->get('minute'))->toBe(30);
});

it('rejects casual time guesses in strict mode', function () {
    $strict = Chrono::strict();

    expect($strict->parseText("I'm at 10", '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText("I'm at 10 - 20", '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('7-730', '2012-08-10 12:00'))->toBe([]);
});

it('merges time expressions followed by casual dates', function () {
    $result = Chrono::parse('10:30 PST today', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('10:30 PST today')
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2026-06-23 10:30:00 -08:00')
        ->and($result->start->timezoneOffset())->toBe(-480)
        ->and($result->tags())->toContain('refiner/mergeTimeFollowedByDate');
});
