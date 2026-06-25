<?php

use Chrono\Chrono;

it('parses german month-name dates and ranges', function () {
    $german = Chrono::de();
    $ancient = $german->parseText('10. August 113 v. Chr.', '2012-08-10')[0];
    $commonEra = $german->parseText('10. August 85 n. Chr.', '2012-08-10')[0];
    $prefixed = $german->parseText('Die Deadline ist am Dienstag, den 10. Januar', '2012-08-10')[0];
    $abbreviatedWeekday = $german->parseText('Die Deadline ist Di, 10. Januar', '2012-08-10')[0];
    $sameMonthRange = $german->parseText('10. - 22. August 2012', '2012-08-10')[0];
    $crossMonthRange = $german->parseText('10. Oktober - 12. Dezember', '2012-08-10')[0];

    expect($german->parseText('10. August 2012', '2012-08-10')[0]->text)
        ->toBe('10. August 2012')
        ->and($german->parseText('10. August 2012', '2012-08-10')[0]->index)
        ->toBe(0)
        ->and($german->parseDateText('10. August 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($ancient->text)->toBe('10. August 113 v. Chr.')
        ->and($ancient->index)->toBe(0)
        ->and($ancient->start->get('year'))->toBe(-113)
        ->and($ancient->start->get('month'))->toBe(8)
        ->and($ancient->start->get('day'))->toBe(10)
        ->and($commonEra->text)->toBe('10. August 85 n. Chr.')
        ->and($commonEra->start->get('year'))->toBe(85)
        ->and($german->parseText('So 15.Sep', '2013-08-10')[0]->text)
        ->toBe('So 15.Sep')
        ->and($german->parseDateText('So 15.Sep', '2013-08-10')?->toDateTimeString())
        ->toBe('2013-09-15 12:00:00')
        ->and($german->parseText('SO 15.SEPT', '2013-08-10')[0]->text)
        ->toBe('SO 15.SEPT')
        ->and($german->parseDateText('SO 15.SEPT', '2013-08-10')?->toDateTimeString())
        ->toBe('2013-09-15 12:00:00')
        ->and($german->parseText('Die Deadline ist am 10. August', '2012-08-10')[0]->text)
        ->toBe('am 10. August')
        ->and($german->parseText('Die Deadline ist am 10. August', '2012-08-10')[0]->index)
        ->toBe(17)
        ->and($prefixed->text)
        ->toBe('am Dienstag, den 10. Januar')
        ->and($prefixed->index)->toBe(17)
        ->and($prefixed->start->get('weekday'))->toBe(2)
        ->and($prefixed->start->date()->toDateTimeString())
        ->toBe('2013-01-10 12:00:00')
        ->and($prefixed->start->tags())->toContain('parser/DEMonthNameParser')
        ->and($abbreviatedWeekday->text)
        ->toBe('Di, 10. Januar')
        ->and($abbreviatedWeekday->index)->toBe(17)
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(2)
        ->and($abbreviatedWeekday->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($german->parseDateText('31. März 2016', '2012-08-10')?->toDateTimeString())
        ->toBe('2016-03-31 12:00:00')
        ->and($german->parseDateText('31.Maerz 2016', '2012-08-10')?->toDateTimeString())
        ->toBe('2016-03-31 12:00:00')
        ->and($german->parseDateText('10. jänner 2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-01-10 12:00:00')
        ->and($sameMonthRange->text)->toBe('10. - 22. August 2012')
        ->and($sameMonthRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($german->parseText('10. bis 22. Oktober 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-10-22 12:00:00')
        ->and($german->parseText('10. bis zum 22. Oktober 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-10-22 12:00:00')
        ->and($crossMonthRange->text)->toBe('10. Oktober - 12. Dezember')
        ->and($crossMonthRange->start->date()->toDateTimeString())->toBe('2012-10-10 12:00:00')
        ->and($crossMonthRange->end?->date()->toDateTimeString())->toBe('2012-12-12 12:00:00')
        ->and($german->parseText('10. August - 12. Oktober 2013', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2013-10-12 12:00:00')
        ->and($german->parseDateText('12. Juli um 19:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:00:00')
        ->and($german->parseDateText('12. Juli um 19 Uhr', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:00:00')
        ->and($german->parseDateText('12. Juli um 19:53 Uhr', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-07-12 19:53:00')
        ->and($german->parseDateText('5. Juni 12:00', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-06-05 12:00:00')
        ->and($german->parseText('32. Oktober 2015', '2012-08-10'))
        ->toBe([]);
});

it('parses german little-endian month names with upstream-shaped components', function () {
    $german = Chrono::de();
    $explicit = $german->parseText('10. August 2012', '2012-08-10')[0];
    $ancient = $german->parseText('10. August 113 v. Chr.', '2012-08-10')[0];
    $commonEra = $german->parseText('10. August 85 n. Chr.', '2012-08-10')[0];
    $weekdayShort = $german->parseText('So 15.Sep', '2013-08-10')[0];
    $weekdayUpper = $german->parseText('SO 15.SEPT', '2013-08-10')[0];
    $contextual = $german->parseText('Die Deadline ist am 10. August', '2012-08-10')[0];
    $weekdayLong = $german->parseText('Die Deadline ist am Dienstag, den 10. Januar', '2012-08-10')[0];
    $weekdayAbbr = $german->parseText('Die Deadline ist Di, 10. Januar', '2012-08-10')[0];
    $sameMonthRange = $german->parseText('10. - 22. August 2012', '2012-08-10')[0];
    $bisRange = $german->parseText('10. bis 22. Oktober 2012', '2012-08-10')[0];
    $crossMonthRange = $german->parseText('10. Oktober - 12. Dezember', '2012-08-10')[0];
    $explicitEndYearRange = $german->parseText('10. August - 12. Oktober 2013', '2012-08-10')[0];
    $austrianMonth = $german->parseText('10. jänner 2012', '2012-08-10')[0];

    expect($explicit->index)->toBe(0)
        ->and($explicit->text)->toBe('10. August 2012')
        ->and($explicit->start->get('year'))->toBe(2012)
        ->and($explicit->start->get('month'))->toBe(8)
        ->and($explicit->start->get('day'))->toBe(10)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($ancient->text)->toBe('10. August 113 v. Chr.')
        ->and($ancient->start->get('year'))->toBe(-113)
        ->and($ancient->start->get('month'))->toBe(8)
        ->and($ancient->start->get('day'))->toBe(10)
        ->and($commonEra->text)->toBe('10. August 85 n. Chr.')
        ->and($commonEra->start->get('year'))->toBe(85)
        ->and($commonEra->start->get('month'))->toBe(8)
        ->and($commonEra->start->get('day'))->toBe(10)
        ->and($weekdayShort->index)->toBe(0)
        ->and($weekdayShort->text)->toBe('So 15.Sep')
        ->and($weekdayShort->start->get('year'))->toBe(2013)
        ->and($weekdayShort->start->get('month'))->toBe(9)
        ->and($weekdayShort->start->get('day'))->toBe(15)
        ->and($weekdayShort->start->get('weekday'))->toBe(0)
        ->and($weekdayUpper->text)->toBe('SO 15.SEPT')
        ->and($weekdayUpper->start->get('year'))->toBe(2013)
        ->and($weekdayUpper->start->get('month'))->toBe(9)
        ->and($weekdayUpper->start->get('day'))->toBe(15)
        ->and($contextual->index)->toBe(17)
        ->and($contextual->text)->toBe('am 10. August')
        ->and($contextual->start->get('year'))->toBe(2012)
        ->and($contextual->start->isCertain('year'))->toBeFalse()
        ->and($weekdayLong->index)->toBe(17)
        ->and($weekdayLong->text)->toBe('am Dienstag, den 10. Januar')
        ->and($weekdayLong->start->get('weekday'))->toBe(2)
        ->and($weekdayLong->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($weekdayAbbr->index)->toBe(17)
        ->and($weekdayAbbr->text)->toBe('Di, 10. Januar')
        ->and($weekdayAbbr->start->get('weekday'))->toBe(2)
        ->and($sameMonthRange->index)->toBe(0)
        ->and($sameMonthRange->text)->toBe('10. - 22. August 2012')
        ->and($sameMonthRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameMonthRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($sameMonthRange->end?->get('month'))->toBe(8)
        ->and($sameMonthRange->end?->get('day'))->toBe(22)
        ->and($bisRange->text)->toBe('10. bis 22. Oktober 2012')
        ->and($bisRange->start->date()->toDateTimeString())->toBe('2012-10-10 12:00:00')
        ->and($bisRange->end?->date()->toDateTimeString())->toBe('2012-10-22 12:00:00')
        ->and($crossMonthRange->text)->toBe('10. Oktober - 12. Dezember')
        ->and($crossMonthRange->start->date()->toDateTimeString())->toBe('2012-10-10 12:00:00')
        ->and($crossMonthRange->end?->date()->toDateTimeString())->toBe('2012-12-12 12:00:00')
        ->and($explicitEndYearRange->text)->toBe('10. August - 12. Oktober 2013')
        ->and($explicitEndYearRange->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($explicitEndYearRange->end?->date()->toDateTimeString())->toBe('2013-10-12 12:00:00')
        ->and($austrianMonth->index)->toBe(0)
        ->and($austrianMonth->text)->toBe('10. jänner 2012')
        ->and($austrianMonth->start->get('month'))->toBe(1)
        ->and($austrianMonth->start->date()->toDateTimeString())->toBe('2012-01-10 12:00:00');
});

it('parses german month-name dates with alternative era labels', function () {
    $german = Chrono::de();
    $beforeCommonEra = $german->parseText('10. August 234 v.u.Z.', '2012-08-10')[0];
    $commonEra = $german->parseText('10. August 88 nuZ', '2012-08-10')[0];
    $commonEraShort = $german->parseText('10. August 88 uZ', '2012-08-10')[0];
    $commonEraDotted = $german->parseText('10. August 88 d.g.Z.', '2012-08-10')[0];
    $beforeChrist = $german->parseText('10. August 234 v.Chr.', '2012-08-10')[0];
    $afterChrist = $german->parseText('10. August 88 nC', '2012-08-10')[0];
    $beforeCurrentEra = $german->parseText('10. August 234 v.d.Z.', '2012-08-10')[0];
    $afterCurrentEra = $german->parseText('10. August 88 ndZ', '2012-08-10')[0];
    $beforeCurrentEraDotted = $german->parseText('10. August 234 v.d.g.Z.', '2012-08-10')[0];
    $afterCurrentEraDotted = $german->parseText('10. August 88 ndgZ', '2012-08-10')[0];

    expect($beforeCommonEra->index)->toBe(0)
        ->and($beforeCommonEra->text)->toBe('10. August 234 v.u.Z.')
        ->and($beforeCommonEra->start->get('year'))->toBe(-234)
        ->and($beforeCommonEra->start->get('month'))->toBe(8)
        ->and($beforeCommonEra->start->get('day'))->toBe(10)
        ->and($commonEra->index)->toBe(0)
        ->and($commonEra->text)->toBe('10. August 88 nuZ')
        ->and($commonEra->start->get('year'))->toBe(88)
        ->and($commonEra->start->get('month'))->toBe(8)
        ->and($commonEra->start->get('day'))->toBe(10)
        ->and($commonEraShort->text)->toBe('10. August 88 uZ')
        ->and($commonEraShort->start->get('year'))->toBe(88)
        ->and($commonEraShort->start->get('month'))->toBe(8)
        ->and($commonEraShort->start->get('day'))->toBe(10)
        ->and($commonEraDotted->text)->toBe('10. August 88 d.g.Z.')
        ->and($commonEraDotted->start->get('year'))->toBe(88)
        ->and($commonEraDotted->start->get('month'))->toBe(8)
        ->and($commonEraDotted->start->get('day'))->toBe(10)
        ->and($beforeChrist->text)->toBe('10. August 234 v.Chr.')
        ->and($beforeChrist->start->get('year'))->toBe(-234)
        ->and($beforeChrist->start->get('month'))->toBe(8)
        ->and($beforeChrist->start->get('day'))->toBe(10)
        ->and($afterChrist->text)->toBe('10. August 88 nC')
        ->and($afterChrist->start->get('year'))->toBe(88)
        ->and($afterChrist->start->get('month'))->toBe(8)
        ->and($afterChrist->start->get('day'))->toBe(10)
        ->and($beforeCurrentEra->text)->toBe('10. August 234 v.d.Z.')
        ->and($beforeCurrentEra->start->get('year'))->toBe(-234)
        ->and($beforeCurrentEra->start->get('month'))->toBe(8)
        ->and($beforeCurrentEra->start->get('day'))->toBe(10)
        ->and($afterCurrentEra->text)->toBe('10. August 88 ndZ')
        ->and($afterCurrentEra->start->get('year'))->toBe(88)
        ->and($afterCurrentEra->start->get('month'))->toBe(8)
        ->and($afterCurrentEra->start->get('day'))->toBe(10)
        ->and($beforeCurrentEraDotted->text)->toBe('10. August 234 v.d.g.Z.')
        ->and($beforeCurrentEraDotted->start->get('year'))->toBe(-234)
        ->and($beforeCurrentEraDotted->start->get('month'))->toBe(8)
        ->and($beforeCurrentEraDotted->start->get('day'))->toBe(10)
        ->and($afterCurrentEraDotted->text)->toBe('10. August 88 ndgZ')
        ->and($afterCurrentEraDotted->start->get('year'))->toBe(88)
        ->and($afterCurrentEraDotted->start->get('month'))->toBe(8)
        ->and($afterCurrentEraDotted->start->get('day'))->toBe(10);
});
