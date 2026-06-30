<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses month name dates and ranges', function () {
    $result = Chrono::parse('Sep 12-13', '2026-06-23')[0];

    expect($result->start->date()->toDateString())->toBe('2026-09-12')
        ->and($result->end?->date()->toDateString())->toBe('2026-09-13');
});

it('parses month name dates with separators', function () {
    expect(Chrono::parse('August-10, 2012', '2012-08-10')[0]->text)
        ->toBe('August-10, 2012')
        ->and(Chrono::parseDate('August/10/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parse('10-August 2012', '2012-08-08')[0]->text)
        ->toBe('10-August 2012')
        ->and(Chrono::parseDate('10-August 2012', '2012-08-08')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10-August-2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parse('10/August 2012', '2012-08-08')[0]->text)
        ->toBe('10/August 2012')
        ->and(Chrono::parseDate('10/August 2012', '2012-08-08')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and(Chrono::parseDate('10/August/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00');
});

it('parses month name dates with ordinal words', function () {
    expect(Chrono::parse('May eighth, 2010', '2012-08-10')[0]->text)
        ->toBe('May eighth, 2010')
        ->and(Chrono::parseDate('May eighth, 2010', '2012-08-10')?->toDateTimeString())
        ->toBe('2010-05-08 12:00:00')
        ->and(Chrono::parseDate('May twenty-fourth', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-24 12:00:00')
        ->and(Chrono::parseDate('Twenty-fourth of May', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-05-24 12:00:00');
});

it('parses month name ranges with ordinal words', function () {
    $littleEndian = Chrono::parse('Eighth to eleventh May 2010', '2012-08-10')[0];
    $middleEndian = Chrono::parse('May eighth - tenth, 2010', '2012-08-10')[0];

    expect($littleEndian->text)->toBe('Eighth to eleventh May 2010')
        ->and($littleEndian->start->date()->toDateTimeString())->toBe('2010-05-08 12:00:00')
        ->and($littleEndian->end?->date()->toDateTimeString())->toBe('2010-05-11 12:00:00')
        ->and($middleEndian->text)->toBe('May eighth - tenth, 2010')
        ->and($middleEndian->start->date()->toDateTimeString())->toBe('2010-05-08 12:00:00')
        ->and($middleEndian->end?->date()->toDateTimeString())->toBe('2010-05-10 12:00:00');
});

it('parses month only expressions', function () {
    $explicitYear = Chrono::parse('She is getting married soon (July 2017).', '2012-08-10')[0];
    $monthOnly = Chrono::parse('She is leaving in August.', '2012-08-10')[0];
    $monthYear = Chrono::parse('I am arriving sometime in August, 2012, probably.', '2012-08-10')[0];
    $january = Chrono::parse('In January', '2020-11-22')[0];
    $jan = Chrono::parse('in Jan', '2020-11-22')[0];
    $may = Chrono::parse('May', '2020-11-22')[0];

    expect($explicitYear->text)->toBe('July 2017')
        ->and($explicitYear->index)->toBe(29)
        ->and($explicitYear->start->date()->toDateTimeString())->toBe('2017-07-01 12:00:00')
        ->and($explicitYear->start->tags())->toContain('parser/ENMonthNameParser')
        ->and($monthOnly->text)->toBe('August')
        ->and($monthOnly->index)->toBe(18)
        ->and($monthOnly->start->get('year'))->toBe(2012)
        ->and($monthOnly->start->get('month'))->toBe(8)
        ->and($monthOnly->start->get('day'))->toBe(1)
        ->and($monthOnly->start->isCertain('year'))->toBeFalse()
        ->and($monthOnly->start->isCertain('month'))->toBeTrue()
        ->and($monthOnly->start->isCertain('day'))->toBeFalse()
        ->and($monthOnly->start->date()->toDateTimeString())->toBe('2012-08-01 12:00:00')
        ->and($monthOnly->tags())->toContain('parser/ENMonthNameParser')
        ->and($monthYear->text)->toBe('August, 2012')
        ->and($monthYear->index)->toBe(26)
        ->and($monthYear->start->tags())->toContain('parser/ENMonthNameParser')
        ->and($january->text)->toBe('January')
        ->and($january->start->date()->toDateTimeString())->toBe('2021-01-01 12:00:00')
        ->and($january->start->isCertain('year'))->toBeFalse()
        ->and($january->start->isCertain('month'))->toBeTrue()
        ->and($january->start->isCertain('day'))->toBeFalse()
        ->and($jan->text)->toBe('Jan')
        ->and($jan->start->date()->toDateTimeString())->toBe('2021-01-01 12:00:00')
        ->and($may->text)->toBe('May')
        ->and($may->start->date()->toDateTimeString())->toBe('2021-05-01 12:00:00')
        ->and(Chrono::parseDate('I am arriving sometime in August, 2012, probably.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-01 12:00:00');
});

it('parses month year expressions with alternate separators', function () {
    $full = Chrono::parse('September 2012', '2012-08-10')[0];
    $longAbbreviation = Chrono::parse('Sept 2012', '2012-08-10')[0];
    $shortAbbreviation = Chrono::parse('Sep 2012', '2012-08-10')[0];
    $dottedAbbreviation = Chrono::parse('Sep. 2012', '2012-08-10')[0];
    $dashSeparated = Chrono::parse('Sep-2012', '2012-08-10')[0];
    $withPrefix = Chrono::parse('in June of 2022', '2012-08-10')[0];
    $statement = Chrono::parse('Statement of comprehensive income for the year ended Dec. 2021', '2012-08-10')[0];
    $context = Chrono::parse('The date is Sep 2012 is the date', '2012-08-10')[0];
    $twoDigitYear = Chrono::parse('Aug 96', '2012-08-10')[0];
    $twoDigitYearContext = Chrono::parse('96 Aug 96', '2012-08-10')[0];

    expect($full->text)->toBe('September 2012')
        ->and($full->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($full->start->isCertain('day'))->toBeFalse()
        ->and($longAbbreviation->text)->toBe('Sept 2012')
        ->and($longAbbreviation->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($shortAbbreviation->text)->toBe('Sep 2012')
        ->and($shortAbbreviation->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($dottedAbbreviation->text)->toBe('Sep. 2012')
        ->and($dottedAbbreviation->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($dashSeparated->text)->toBe('Sep-2012')
        ->and($dashSeparated->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($withPrefix->text)->toBe('June of 2022')
        ->and($withPrefix->start->date()->toDateTimeString())->toBe('2022-06-01 12:00:00')
        ->and($context->text)->toBe('Sep 2012')
        ->and($context->index)->toBe(12)
        ->and($twoDigitYear->text)->toBe('Aug 96')
        ->and($twoDigitYear->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and($twoDigitYearContext->text)->toBe('Aug 96')
        ->and($twoDigitYearContext->index)->toBe(3)
        ->and($twoDigitYearContext->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and(Chrono::parseDate('August 10', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($statement->text)->toBe('Dec. 2021')
        ->and($statement->start->get('year'))->toBe(2021)
        ->and($statement->start->get('month'))->toBe(12)
        ->and($statement->start->get('day'))->toBe(1)
        ->and($statement->start->isCertain('year'))->toBeTrue()
        ->and($statement->start->isCertain('month'))->toBeTrue()
        ->and($statement->start->isCertain('day'))->toBeFalse();
});

it('parses month only ranges', function () {
    $sameYear = Chrono::parse('From May to December', '2023-04-09')[0];
    $crossYear = Chrono::parse('From December to May', '2023-04-09')[0];
    $explicitSameYear = Chrono::parse('From May to December, 2022', '2023-04-09')[0];
    $explicitCrossYear = Chrono::parse('From December to May 2022', '2023-04-09')[0];
    $explicitPastCrossYear = Chrono::parse('From December to May 2020', '2023-04-09')[0];
    $explicitFutureCrossYear = Chrono::parse('From December to May 2025', '2023-04-09')[0];
    $forwardSameYear = Chrono::parse('From May to December', '2023-04-09', ['forwardDate' => true])[0];
    $forwardCrossYear = Chrono::parse('From December to May', '2023-04-09', ['forwardDate' => true])[0];
    $monthYearRange = Chrono::parse('July 2020 to August 2020', '2012-08-10');

    expect($sameYear->text)->toBe('From May to December')
        ->and($sameYear->start->date()->toDateTimeString())->toBe('2023-05-01 12:00:00')
        ->and($sameYear->end?->date()->toDateTimeString())->toBe('2023-12-01 12:00:00')
        ->and($crossYear->text)->toBe('From December to May')
        ->and($crossYear->start->date()->toDateTimeString())->toBe('2022-12-01 12:00:00')
        ->and($crossYear->end?->date()->toDateTimeString())->toBe('2023-05-01 12:00:00')
        ->and($explicitSameYear->start->date()->toDateTimeString())->toBe('2022-05-01 12:00:00')
        ->and($explicitSameYear->end?->date()->toDateTimeString())->toBe('2022-12-01 12:00:00')
        ->and($explicitCrossYear->start->date()->toDateTimeString())->toBe('2021-12-01 12:00:00')
        ->and($explicitCrossYear->end?->date()->toDateTimeString())->toBe('2022-05-01 12:00:00')
        ->and($explicitPastCrossYear->start->date()->toDateTimeString())->toBe('2019-12-01 12:00:00')
        ->and($explicitPastCrossYear->end?->date()->toDateTimeString())->toBe('2020-05-01 12:00:00')
        ->and($explicitFutureCrossYear->start->date()->toDateTimeString())->toBe('2024-12-01 12:00:00')
        ->and($explicitFutureCrossYear->end?->date()->toDateTimeString())->toBe('2025-05-01 12:00:00')
        ->and($forwardSameYear->start->date()->toDateTimeString())->toBe('2023-05-01 12:00:00')
        ->and($forwardSameYear->end?->date()->toDateTimeString())->toBe('2023-12-01 12:00:00')
        ->and($forwardCrossYear->start->date()->toDateTimeString())->toBe('2023-12-01 12:00:00')
        ->and($forwardCrossYear->end?->date()->toDateTimeString())->toBe('2024-05-01 12:00:00')
        ->and($monthYearRange)->toHaveCount(1)
        ->and($monthYearRange[0]->text)->toBe('July 2020 to August 2020')
        ->and($monthYearRange[0]->start->date()->toDateTimeString())->toBe('2020-07-01 12:00:00')
        ->and($monthYearRange[0]->end?->date()->toDateTimeString())->toBe('2020-08-01 12:00:00')
        ->and($monthYearRange[0]->tags())->toContain('parser/ENMonthNameParser');
});

it('uses forward date option for month only expressions', function () {
    expect(Chrono::parseDate('in December', '2023-04-09', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2023-12-01 12:00:00')
        ->and(Chrono::parseDate('in May', '2023-04-09', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2023-05-01 12:00:00');
});

it('does not parse modal may as a month', function () {
    expect(Chrono::parse('The mountain may not move', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('May not be correct', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('in May', '2012-08-10')[0]->text)
        ->toBe('May');
});

it('uses the closest year for month day expressions without explicit years', function () {
    $result = Chrono::parse('The Deadline is January 10', '2012-08-10')[0];

    expect($result->text)->toBe('January 10')
        ->and($result->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00');
});

it('parses trailing years after month date time expressions', function () {
    $plain = Chrono::parse('Thu Oct 26 11:00:09 2023', '2016-10-01 08:00')[0];
    $zoned = Chrono::parse('Thu Oct 26 11:00:09 EDT 2023', '2016-10-01 08:00')[0];

    expect($plain->text)->toBe('Thu Oct 26 11:00:09 2023')
        ->and($plain->start->date()->toDateTimeString())->toBe('2023-10-26 11:00:09')
        ->and($plain->start->get('year'))->toBe(2023)
        ->and($plain->start->get('month'))->toBe(10)
        ->and($plain->start->get('day'))->toBe(26)
        ->and($plain->start->get('hour'))->toBe(11)
        ->and($plain->start->get('minute'))->toBe(0)
        ->and($plain->start->get('second'))->toBe(9)
        ->and($plain->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($zoned->text)->toBe('Thu Oct 26 11:00:09 EDT 2023')
        ->and($zoned->start->get('year'))->toBe(2023)
        ->and($zoned->start->get('month'))->toBe(10)
        ->and($zoned->start->get('day'))->toBe(26)
        ->and($zoned->start->get('hour'))->toBe(11)
        ->and($zoned->start->get('minute'))->toBe(0)
        ->and($zoned->start->get('second'))->toBe(9)
        ->and($zoned->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($zoned->start->timezoneOffset())->toBe(-240)
        ->and($zoned->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-10-26 11:00:09 -04:00');
});

it('parses trailing years after month date time ranges', function () {
    $dayRange = Chrono::parse('Thu Oct 26 - 28, 11:00:09 2023', '2016-10-01 08:00')[0];
    $timeRange = Chrono::parse('Thu Oct 26, 10:00 - 11:00:09 2023', '2016-10-01 08:00')[0];

    expect($dayRange->text)->toBe('Thu Oct 26 - 28, 11:00:09 2023')
        ->and($dayRange->start->date()->toDateTimeString())->toBe('2023-10-26 11:00:09')
        ->and($dayRange->start->get('year'))->toBe(2023)
        ->and($dayRange->start->get('month'))->toBe(10)
        ->and($dayRange->start->get('day'))->toBe(26)
        ->and($dayRange->start->get('hour'))->toBe(11)
        ->and($dayRange->start->get('minute'))->toBe(0)
        ->and($dayRange->start->get('second'))->toBe(9)
        ->and($dayRange->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($dayRange->end?->date()->toDateTimeString())->toBe('2023-10-28 11:00:09')
        ->and($dayRange->end?->get('year'))->toBe(2023)
        ->and($dayRange->end?->get('month'))->toBe(10)
        ->and($dayRange->end?->get('day'))->toBe(28)
        ->and($dayRange->end?->get('hour'))->toBe(11)
        ->and($dayRange->end?->get('minute'))->toBe(0)
        ->and($dayRange->end?->get('second'))->toBe(9)
        ->and($dayRange->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($timeRange->text)->toBe('Thu Oct 26, 10:00 - 11:00:09 2023')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2023-10-26 10:00:00')
        ->and($timeRange->start->get('year'))->toBe(2023)
        ->and($timeRange->start->get('month'))->toBe(10)
        ->and($timeRange->start->get('day'))->toBe(26)
        ->and($timeRange->start->get('hour'))->toBe(10)
        ->and($timeRange->start->get('minute'))->toBe(0)
        ->and($timeRange->start->get('second'))->toBe(0)
        ->and($timeRange->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2023-10-26 11:00:09')
        ->and($timeRange->end?->get('year'))->toBe(2023)
        ->and($timeRange->end?->get('month'))->toBe(10)
        ->and($timeRange->end?->get('day'))->toBe(26)
        ->and($timeRange->end?->get('hour'))->toBe(11)
        ->and($timeRange->end?->get('minute'))->toBe(0)
        ->and($timeRange->end?->get('second'))->toBe(9)
        ->and($timeRange->end?->get('meridiem'))->toBe(Meridiem::AM);
});
