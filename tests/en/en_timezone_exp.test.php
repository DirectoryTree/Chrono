<?php

use Carbon\CarbonImmutable;
use Chrono\Chrono;
use Chrono\Locales\En\Parsers\EnTimeUnitCasualRelativeFormatParser;
use Chrono\Month;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Timezone;
use Chrono\Weekday;

it('parses iso datetimes with timezone suffixes', function () {
    $offset = Chrono::parse('1994-11-05T08:15:30-05:30')[0];
    $utc = Chrono::parse('1994-11-05T13:15:30Z')[0];
    $fractional = Chrono::parse('2016-05-07T23:45:00.487+01:00')[0];
    $longFractional = Chrono::parse('2016-05-07T12:45:00.1234+01:00')[0];
    $local = Chrono::parse('1994-11-05T13:15:30')[0];
    $localNoon = Chrono::parse('2015-07-31T12:00:00')[0];
    $prefixedUtc = Chrono::parse('- 1994-11-05T13:15:30Z')[0];
    $localWithoutSeconds = Chrono::parse('2024-01-01T00:00')[0];
    $hourOnlyPositive = Chrono::parse('1994-11-05T13:15:30+09')[0];
    $hourOnlyNegative = Chrono::parse('1994-11-05T13:15:30-05')[0];
    $compact = Chrono::parse('1994-11-05T13:15:30+0900')[0];

    expect($offset->text)->toBe('1994-11-05T08:15:30-05:30')
        ->and($offset->start->timezoneOffset())->toBe(-330)
        ->and($offset->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 -05:30')
        ->and($offset->tags())->toContain('parser/ISOFormatParser')
        ->and($utc->text)->toBe('1994-11-05T13:15:30Z')
        ->and($utc->start->timezoneOffset())->toBe(0)
        ->and($utc->tags())->toContain('parser/ISOFormatParser')
        ->and($fractional->text)->toBe('2016-05-07T23:45:00.487+01:00')
        ->and($fractional->start->timezoneOffset())->toBe(60)
        ->and($fractional->start->get('millisecond'))->toBe(487)
        ->and($fractional->start->isCertain('millisecond'))->toBeTrue()
        ->and($fractional->start->date()->format('Y-m-d H:i:s.v P'))->toBe('2016-05-07 23:45:00.487 +01:00')
        ->and($fractional->tags())->toContain('parser/ISOFormatParser')
        ->and($longFractional->start->get('millisecond'))->toBe(1234)
        ->and($longFractional->start->date()->format('Y-m-d H:i:s.v P'))->toBe('2016-05-07 12:45:01.234 +01:00')
        ->and($local->start->timezoneOffset())->toBeNull()
        ->and($local->start->get('millisecond'))->toBe(0)
        ->and($local->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($localNoon->text)->toBe('2015-07-31T12:00:00')
        ->and($localNoon->start->get('year'))->toBe(2015)
        ->and($localNoon->start->get('month'))->toBe(7)
        ->and($localNoon->start->get('day'))->toBe(31)
        ->and($localNoon->start->get('hour'))->toBe(12)
        ->and($localNoon->start->get('minute'))->toBe(0)
        ->and($localNoon->start->get('second'))->toBe(0)
        ->and($localNoon->start->get('millisecond'))->toBe(0)
        ->and($localNoon->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($prefixedUtc->index)->toBe(2)
        ->and($prefixedUtc->text)->toBe('1994-11-05T13:15:30Z')
        ->and($prefixedUtc->start->timezoneOffset())->toBe(0)
        ->and($localWithoutSeconds->text)->toBe('2024-01-01T00:00')
        ->and($localWithoutSeconds->start->date()->toDateTimeString())->toBe('2024-01-01 00:00:00')
        ->and($localWithoutSeconds->start->timezoneOffset())->toBeNull()
        ->and($hourOnlyPositive->text)->toBe('1994-11-05T13:15:30+09')
        ->and($hourOnlyPositive->start->timezoneOffset())->toBe(540)
        ->and($hourOnlyPositive->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 13:15:30 +09:00')
        ->and($hourOnlyNegative->text)->toBe('1994-11-05T13:15:30-05')
        ->and($hourOnlyNegative->start->timezoneOffset())->toBe(-300)
        ->and($compact->text)->toBe('1994-11-05T13:15:30+0900')
        ->and($compact->start->timezoneOffset())->toBe(540);
});

it('skips unlikely bare month abbreviations', function () {
    $context = Chrono::parse('By Angie Mar November 2019', '2012-08-10')[0];

    expect($context->text)->toBe('November 2019')
        ->and($context->start->date()->toDateTimeString())->toBe('2019-11-01 12:00:00')
        ->and(Chrono::parse('Mar', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('in Jan', '2020-11-22')[0]->text)->toBe('Jan')
        ->and(Chrono::parseDate('in Jan', '2020-11-22')?->toDateTimeString())->toBe('2021-01-01 12:00:00');
});

it('does not attach timezone abbreviations to month only expressions', function () {
    $result = Chrono::parse('People visiting Buñol towards the end of August get a good chance', '2012-08-10')[0];

    expect($result->text)->toBe('August')
        ->and($result->start->timezoneOffset())->toBeNull()
        ->and($result->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00');
});

it('validates calendar dates with implied timezone components like upstream', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2021-03-13 14:22:14'), [
        'day' => 13,
        'month' => 3,
        'year' => 2021,
        'hour' => 14,
        'minute' => 22,
        'second' => 14,
        'millisecond' => 0,
    ]);

    $components->imply('timezoneOffset', -300);

    expect($components->isValidDate())->toBeTrue()
        ->and($components->get('timezoneOffset'))->toBe(-300)
        ->and($components->isCertain('timezoneOffset'))->toBeFalse();
});

it('parses casual times with timezone abbreviations', function () {
    $morning = Chrono::parse('Jan 1, 2020 Morning UTC')[0];
    $evening = Chrono::parse('Jan 1, 2020 Evening JST')[0];

    expect($morning->text)->toBe('Jan 1, 2020 Morning UTC')
        ->and($morning->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-01-01 06:00:00 +00:00')
        ->and($morning->start->get('year'))->toBe(2020)
        ->and($morning->start->get('month'))->toBe(1)
        ->and($morning->start->get('day'))->toBe(1)
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($morning->start->timezoneOffset())->toBe(0)
        ->and($evening->text)->toBe('Jan 1, 2020 Evening JST')
        ->and($evening->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-01-01 20:00:00 +09:00')
        ->and($evening->start->get('year'))->toBe(2020)
        ->and($evening->start->get('month'))->toBe(1)
        ->and($evening->start->get('day'))->toBe(1)
        ->and($evening->start->get('hour'))->toBe(20)
        ->and($evening->start->timezoneOffset())->toBe(540);
});

it('honors upstream casual relative abbreviation parser options', function () {
    $custom = Chrono::strict()->withParser(new EnTimeUnitCasualRelativeFormatParser(false));
    $spelled = $custom->parseText('-2 hours 5 minutes', '2016-10-01 12:00')[0];

    expect($custom->parseText('-3y', '2016-10-01 12:00'))->toBe([])
        ->and($custom->parseText('last 2m', '2016-10-01 12:00'))->toBe([])
        ->and($spelled->text)->toBe('-2 hours 5 minutes')
        ->and($spelled->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00');
});

it('merges postfix plus and minus duration offsets', function () {
    $weekday = Chrono::parse('next tuesday +10 days', '2023-12-29 00:00')[0];
    $isoDate = Chrono::parse('2023-12-29 -10days', '2023-12-29 00:00')[0];
    $now = Chrono::parse('now + 40minutes', '2023-12-29 08:30')[0];

    expect($weekday->text)->toBe('next tuesday +10 days')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2024-01-12 12:00:00')
        ->and($weekday->tags())->toContain('refiner/mergeRelativeAfterDate')
        ->and($isoDate->text)->toBe('2023-12-29 -10days')
        ->and($isoDate->start->date()->toDateTimeString())->toBe('2023-12-19 12:00:00')
        ->and($isoDate->tags())->toContain('refiner/mergeRelativeAfterDate')
        ->and($now->text)->toBe('now + 40minutes')
        ->and($now->start->date()->toDateTimeString())->toBe('2023-12-29 09:10:00')
        ->and($now->tags())->toContain('refiner/mergeRelativeAfterDate');
});

it('moves standalone times forward with timezone-aware references like upstream', function () {
    $result = Chrono::parse('1am', [
        'instant' => 'Wed May 26 2022 01:57:00 GMT-0500 (CDT)',
        'timezone' => 'CDT',
    ], ['forwardDate' => true])[0];

    expect($result->text)->toBe('1am')
        ->and($result->start->get('year'))->toBe(2022)
        ->and($result->start->get('month'))->toBe(5)
        ->and($result->start->get('day'))->toBe(27)
        ->and($result->start->get('hour'))->toBe(1);
});

it('parses utc and gmt timezone offsets', function () {
    $result = Chrono::parse('11 am utc+02:45', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('11 am utc+02:45')
        ->and($result->start->timezoneOffset())->toBe(165)
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2026-06-23 11:00:00 +02:45');
});

it('preserves timezone offsets when merging dates and times', function () {
    $compact = Chrono::parse('wednesday, september 16, 2020 at 11 am utc+0245')[0];
    $hourOnly = Chrono::parse('wednesday, september 16, 2020 at 11 am utc+02')[0];
    $gmt = Chrono::parse('wednesday, september 16, 2020 at 11 am GMT -08:45')[0];
    $named = Chrono::parse('wednesday, september 16, 2020 at 11 am GMT+0900 (JST)')[0];

    expect($compact->start->timezoneOffset())->toBe(165)
        ->and($hourOnly->start->timezoneOffset())->toBe(120)
        ->and($gmt->start->timezoneOffset())->toBe(-525)
        ->and($named->start->timezoneOffset())->toBe(540)
        ->and($named->text)->toBe('wednesday, september 16, 2020 at 11 am GMT+0900 (JST)');
});

it('parses upstream parenthesized and compact numeric timezone offsets', function () {
    $hourOnly = Chrono::parse('wednesday, september 16, 2020 at 23.00+14')[0];
    $parenthesized = Chrono::parse('published: 10:30 (gmt-2:30).')[0];
    $lowercaseGmt = Chrono::parse('wednesday, september 16, 2020 at 11 am gmt+02 ')[0];

    expect($hourOnly->text)->toBe('wednesday, september 16, 2020 at 23.00+14')
        ->and($hourOnly->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($hourOnly->start->timezoneOffset())->toBe(840)
        ->and($parenthesized->text)->toBe('10:30 (gmt-2:30)')
        ->and($parenthesized->start->get('hour'))->toBe(10)
        ->and($parenthesized->start->get('minute'))->toBe(30)
        ->and($parenthesized->start->timezoneOffset())->toBe(-150)
        ->and($lowercaseGmt->text)->toBe('wednesday, september 16, 2020 at 11 am gmt+02')
        ->and($lowercaseGmt->start->get('hour'))->toBe(11)
        ->and($lowercaseGmt->start->get('minute'))->toBe(0)
        ->and($lowercaseGmt->start->timezoneOffset())->toBe(120);
});

it('parses dotted time with numeric timezone offsets', function () {
    $valid = Chrono::parse('wednesday, september 16, 2020 at 23.00+1400')[0];
    $invalid = Chrono::parse('wednesday, september 16, 2020 at 23.00+15')[0];
    $trailingDigit = Chrono::parse('today at 10:00+09001', '2012-08-10 12:00')[0];

    expect($valid->text)->toBe('wednesday, september 16, 2020 at 23.00+1400')
        ->and($valid->start->date()->format('Y-m-d H:i:s P'))->toBe('2020-09-16 23:00:00 +14:00')
        ->and($valid->start->timezoneOffset())->toBe(840)
        ->and($invalid->text)->toBe('wednesday, september 16, 2020 at 23.00')
        ->and($invalid->start->timezoneOffset())->toBeNull()
        ->and($trailingDigit->text)->toBe('today at 10:00+0900')
        ->and($trailingDigit->start->timezoneOffset())->toBe(540);
});

it('parses pre-1900 iso timezone offsets', function () {
    $utc = Chrono::parse('1900-01-01T00:00:00-00:00')[0];
    $negative = Chrono::parse('1900-01-01T00:00:00-01:00')[0];
    $positive = Chrono::parse('1900-01-01T00:00:00+08:00')[0];

    expect($utc->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 +00:00')
        ->and($utc->start->timezoneOffset())->toBe(0)
        ->and($negative->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 -01:00')
        ->and($negative->start->timezoneOffset())->toBe(-60)
        ->and($positive->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 +08:00')
        ->and($positive->start->timezoneOffset())->toBe(480);
});

it('does not treat postfix duration offsets as timezones', function () {
    $result = Chrono::parse('today +10 days', '2026-06-23 08:00:00')[0];

    expect($result->start->timezoneOffset())->toBeNull()
        ->and($result->start->date()->toDateTimeString())->toBe('2026-07-03 08:00:00');
});

it('parses timezone abbreviations', function () {
    $result = Chrono::parse('11 am JST', '2026-06-23 08:00:00')[0];

    expect($result->text)->toBe('11 am JST')
        ->and($result->start->timezoneOffset())->toBe(540)
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2026-06-23 11:00:00 +09:00');
});

it('extracts timezone abbreviations onto range ends independently like upstream', function () {
    $start = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 10:00:00')))
        ->assign('hour', 10)
        ->assign('timezoneOffset', 540);

    $end = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 11:00:00')))
        ->assign('hour', 11);

    $result = new ParsedResult(0, '10am-11am', $start, $end);
    $results = (new ExtractTimezoneAbbrRefiner)->refine(
        '10am-11am JST',
        [$result],
        Reference::make('2026-06-23 08:00:00'),
        new Options,
    );

    expect($results[0]->text)
        ->toBe('10am-11am JST')
        ->and($results[0]->start->timezoneOffset())->toBe(540)
        ->and($results[0]->end?->timezoneOffset())->toBe(540);
});

it('parses upstream timezone abbreviation map entries', function () {
    expect(Chrono::parse('11 am NPT', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(345)
        ->and(Chrono::parse('11 am ACST', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(570)
        ->and(Chrono::parse('11 am WET', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(0)
        ->and(Chrono::parse('11 am NOVT', '2026-06-23 08:00:00')[0]->start->timezoneOffset())
        ->toBe(360);
});

it('keeps wrapping punctuation outside timezone abbreviations', function () {
    $bare = Chrono::parse('Want to meet for dinner (5pm EST)?', '2020-09-01 12:00')[0];
    $wrapped = Chrono::parse('Want to meet for dinner 5pm (EST)?', '2020-09-01 12:00')[0];
    $open = Chrono::parse('today at 10:00 (JST', '2012-08-10 12:00')[0];
    $close = Chrono::parse('today at 10:00 JST)', '2012-08-10 12:00')[0];

    expect($bare->text)->toBe('5pm EST)')
        ->and($bare->start->timezoneOffset())->toBe(-300)
        ->and($wrapped->text)->toBe('5pm (EST)')
        ->and($wrapped->start->timezoneOffset())->toBe(-300)
        ->and($open->text)->toBe('today at 10:00 (JST')
        ->and($open->start->timezoneOffset())->toBe(540)
        ->and($close->text)->toBe('today at 10:00 JST)')
        ->and($close->start->timezoneOffset())->toBe(540);
});

it('parses custom timezone abbreviations from options', function () {
    $unknown = Chrono::parse('Jan 1st 2023 at 10:00 XYZ', '2023-01-01')[0];
    $custom = Chrono::parse('Jan 1st 2023 at 10:00 XYZ', '2023-01-01', [
        'timezones' => ['XYZ' => -180],
    ])[0];

    expect($unknown->start->timezoneOffset())->toBeNull()
        ->and($custom->text)->toBe('Jan 1st 2023 at 10:00 XYZ')
        ->and($custom->start->timezoneOffset())->toBe(-180)
        ->and($custom->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-01-01 10:00:00 -03:00');
});

it('keeps now and later relative instants stable across timezone settings like upstream', function () {
    $instant = CarbonImmutable::createFromTimestamp(1637674343);
    $later = $instant->addHours(2);

    foreach ([
        ['instant' => $instant],
        ['instant' => $instant, 'timezone' => null],
        ['instant' => $instant, 'timezone' => 'BST'],
        ['instant' => $instant, 'timezone' => 'JST'],
    ] as $reference) {
        $now = Chrono::parse('now', $reference)[0];
        $twoHoursLater = Chrono::parse('2 hour later', $reference)[0];

        expect($now->text)->toBe('now')
            ->and($now->start->date()->getTimestamp())->toBe($instant->getTimestamp())
            ->and($twoHoursLater->text)->toBe('2 hour later')
            ->and($twoHoursLater->start->date()->getTimestamp())->toBe($later->getTimestamp());
    }
});

it('parses timezone abbreviations on date-only and relative results', function () {
    $date = Chrono::parse('Wednesday, September 16, 2020, EST')[0];
    $lowercaseWord = Chrono::parse('in 1 day get eggs and milk', '2020-11-14 13:48:22')[0];
    $relativeDay = Chrono::parse('in 1 day GET', '2020-11-14 13:48:22')[0];
    $relativeWeek = Chrono::parse('next week EST', '2020-11-14 13:48:22')[0];

    expect($date->text)->toBe('Wednesday, September 16, 2020, EST')
        ->and($date->start->timezoneOffset())->toBe(-300)
        ->and($lowercaseWord->text)->toBe('in 1 day')
        ->and($lowercaseWord->start->timezoneOffset())->toBeNull()
        ->and($relativeDay->text)->toBe('in 1 day GET')
        ->and($relativeDay->start->timezoneOffset())->toBe(240)
        ->and($relativeWeek->text)->toBe('next week EST')
        ->and($relativeWeek->start->timezoneOffset())->toBe(-300);
});

it('parses date ranges with timezone abbreviations on both endpoints', function () {
    $result = Chrono::parse('10:30 JST today to 10:30 pst tomorrow', '2016-10-01 08:00')[0];

    expect($result->text)->toBe('10:30 JST today to 10:30 pst tomorrow')
        ->and($result->start->date()->format('Y-m-d H:i:s P'))->toBe('2016-10-01 10:30:00 +09:00')
        ->and($result->start->timezoneOffset())->toBe(540)
        ->and($result->end?->date()->format('Y-m-d H:i:s P'))->toBe('2016-10-02 10:30:00 -08:00')
        ->and($result->end?->timezoneOffset())->toBe(-480)
        ->and($result->tags())->toContain('refiner/mergeDateRange');
});

it('uses timezone-aware reference arrays', function () {
    $bst = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'BST',
    ]);

    $jst = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'JST',
    ]);

    $custom = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'BBB',
    ], [
        'timezones' => ['BBB' => 60],
    ]);

    $npt = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'NPT',
    ]);

    $ambiguous = Chrono::parseDate('At 4pm tomorrow', [
        'instant' => '2021-06-06T19:00:00+09:00',
        'timezone' => 'XYZ',
    ], [
        'timezones' => [
            'XYZ' => [
                'timezoneOffsetDuringDst' => -120,
                'timezoneOffsetNonDst' => -180,
                'dstStart' => fn (int $year): CarbonImmutable => Timezone::getLastWeekdayOfMonth($year, Month::MARCH, Weekday::SUNDAY, 2),
                'dstEnd' => fn (int $year): CarbonImmutable => Timezone::getLastWeekdayOfMonth($year, Month::OCTOBER, Weekday::SUNDAY, 3),
            ],
        ],
    ]);

    $jsDateString = Chrono::parseDate('Friday at 4pm', [
        'instant' => 'Wed Jun 09 2021 07:00:00 GMT-0500 (CDT)',
        'timezone' => 'CDT',
    ]);

    $jsDateStringTime = Chrono::parseDate('1am', [
        'instant' => 'Wed May 26 2022 01:57:00 GMT-0500 (CDT)',
        'timezone' => 'CDT',
    ]);

    expect($bst?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +01:00')
        ->and($jst?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +09:00')
        ->and($custom?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +01:00')
        ->and($npt?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 +05:45')
        ->and($ambiguous?->format('Y-m-d H:i:s P'))->toBe('2021-06-07 16:00:00 -02:00')
        ->and($jsDateString?->format('Y-m-d H:i:s P'))->toBe('2021-06-11 16:00:00 -05:00')
        ->and($jsDateStringTime?->format('Y-m-d H:i:s P'))->toBe('2022-05-26 01:00:00 -05:00');
});
