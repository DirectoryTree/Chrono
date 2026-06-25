<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses japanese time expressions and ranges', function () {
    $japanese = Chrono::ja();
    $prefixedMinuteTime = $japanese->parseText('私は午前6時13分に起きた', '2012-08-10')[0];
    $prefixedHourTime = $japanese->parseText('私は午前8時に起きる', '2012-08-10 12:00')[0];
    $time = $japanese->parseText('午後3時半', '2012-08-10')[0];
    $eveningTime = $japanese->parseText('午後8時', '2012-08-10 12:00')[0];
    $fullWidthDateTime = $japanese->parseText('１２月９日の１６：３０', '2025-12-10 12:00')[0];
    $range = $japanese->parseText('午後10時から1時', '2012-08-10')[0];
    $japaneseNumeralRange = $japanese->parseText('私は本日午前八時十分から午後11時32分までゲームをした', '2012-08-10')[0];
    $asciiMeridiemRange = $japanese->parseText('6時30分PM-11時PM', '2012-08-10')[0];
    $dateTimeWithSeconds = $japanese->parseText('僕は2018年11月26日午後三時半五十九秒にゲームを始めた', '2012-08-10')[0];
    $impliedMeridiemRange = $japanese->parseText('午後1時30分から3時10分', '2012-08-10')[0];
    $dottedMeridiemRange = $japanese->parseText('1時20分P.M.から3時', '2012-08-10')[0];
    $fullWidthMeridiemRange = $japanese->parseText('午後６時半－１１時', '2012-08-10')[0];
    $overnightMeridiemRange = $japanese->parseText('午後１１時半－１時', '2012-08-10')[0];
    $overnightTwentyFourHourRange = $japanese->parseText('23時20分から2時', '2012-08-10')[0];
    $randomDateRange = $japanese->parseText('2014年3月5日午前 6 時から 7 時', '2012-08-10')[0];
    $randomWeekdayTime = $japanese->parseText('次の土曜日1時30分二十九秒', '2012-08-10')[0];
    $randomCasualTime = $japanese->parseText('昨日午前六時', '2012-08-10')[0];
    $randomMonthTime = $japanese->parseText('６月４日3:00am', '2012-08-10')[0];
    $randomPreviousWeekdayTime = $japanese->parseText('前の金曜日16時', '2012-08-10')[0];
    $randomStandardTime = $japanese->parseText('3月17日 20時15', '2012-08-10')[0];
    $weekdayTime = $japanese->parseText('水曜日 22時', '2012-08-10')[0];

    expect($prefixedMinuteTime->index)->toBe(6)
        ->and($prefixedMinuteTime->text)->toBe('午前6時13分')
        ->and($prefixedMinuteTime->start->get('hour'))->toBe(6)
        ->and($prefixedMinuteTime->start->get('minute'))->toBe(13)
        ->and($prefixedMinuteTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($prefixedHourTime->index)->toBe(6)
        ->and($prefixedHourTime->text)->toBe('午前8時')
        ->and($prefixedHourTime->start->get('year'))->toBe(2012)
        ->and($prefixedHourTime->start->get('month'))->toBe(8)
        ->and($prefixedHourTime->start->get('day'))->toBe(10)
        ->and($prefixedHourTime->start->get('hour'))->toBe(8)
        ->and($prefixedHourTime->start->date()->toDateTimeString())->toBe('2012-08-10 08:00:00')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($time->start->tags())->toContain('parser/JPTimeExpressionParser')
        ->and($eveningTime->text)->toBe('午後8時')
        ->and($eveningTime->start->get('year'))->toBe(2012)
        ->and($eveningTime->start->get('month'))->toBe(8)
        ->and($eveningTime->start->get('day'))->toBe(10)
        ->and($eveningTime->start->get('hour'))->toBe(20)
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($fullWidthDateTime->text)->toBe('１２月９日の１６：３０')
        ->and($fullWidthDateTime->start->date()->toDateTimeString())->toBe('2025-12-09 16:30:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($range->end?->tags())->toContain('parser/JPTimeExpressionParser')
        ->and($japaneseNumeralRange->index)->toBe(6)
        ->and($japaneseNumeralRange->text)->toBe('本日午前八時十分から午後11時32分')
        ->and($japaneseNumeralRange->start->get('hour'))->toBe(8)
        ->and($japaneseNumeralRange->start->get('minute'))->toBe(10)
        ->and($japaneseNumeralRange->start->isCertain('year'))->toBeTrue()
        ->and($japaneseNumeralRange->start->isCertain('month'))->toBeTrue()
        ->and($japaneseNumeralRange->start->isCertain('day'))->toBeTrue()
        ->and($japaneseNumeralRange->start->isCertain('hour'))->toBeTrue()
        ->and($japaneseNumeralRange->start->isCertain('minute'))->toBeTrue()
        ->and($japaneseNumeralRange->start->isCertain('second'))->toBeFalse()
        ->and($japaneseNumeralRange->start->isCertain('millisecond'))->toBeFalse()
        ->and($japaneseNumeralRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($japaneseNumeralRange->end?->get('hour'))->toBe(23)
        ->and($japaneseNumeralRange->end?->get('minute'))->toBe(32)
        ->and($japaneseNumeralRange->end?->isCertain('year'))->toBeTrue()
        ->and($japaneseNumeralRange->end?->isCertain('month'))->toBeTrue()
        ->and($japaneseNumeralRange->end?->isCertain('day'))->toBeTrue()
        ->and($japaneseNumeralRange->end?->isCertain('hour'))->toBeTrue()
        ->and($japaneseNumeralRange->end?->isCertain('minute'))->toBeTrue()
        ->and($japaneseNumeralRange->end?->isCertain('second'))->toBeFalse()
        ->and($japaneseNumeralRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($japaneseNumeralRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:32:00')
        ->and($asciiMeridiemRange->index)->toBe(0)
        ->and($asciiMeridiemRange->text)->toBe('6時30分PM-11時PM')
        ->and($asciiMeridiemRange->start->get('hour'))->toBe(18)
        ->and($asciiMeridiemRange->start->get('minute'))->toBe(30)
        ->and($asciiMeridiemRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($asciiMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($asciiMeridiemRange->end?->get('hour'))->toBe(23)
        ->and($asciiMeridiemRange->end?->get('minute'))->toBe(0)
        ->and($asciiMeridiemRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($asciiMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($dateTimeWithSeconds->text)->toBe('2018年11月26日午後三時半五十九秒')
        ->and($dateTimeWithSeconds->start->get('year'))->toBe(2018)
        ->and($dateTimeWithSeconds->start->get('month'))->toBe(11)
        ->and($dateTimeWithSeconds->start->get('day'))->toBe(26)
        ->and($dateTimeWithSeconds->start->get('hour'))->toBe(15)
        ->and($dateTimeWithSeconds->start->get('minute'))->toBe(30)
        ->and($dateTimeWithSeconds->start->get('second'))->toBe(59)
        ->and($dateTimeWithSeconds->start->get('millisecond'))->toBe(0)
        ->and($dateTimeWithSeconds->start->date()->toDateTimeString())->toBe('2018-11-26 15:30:59')
        ->and($dateTimeWithSeconds->start->isCertain('millisecond'))->toBeFalse()
        ->and($impliedMeridiemRange->start->get('hour'))->toBe(13)
        ->and($impliedMeridiemRange->start->get('minute'))->toBe(30)
        ->and($impliedMeridiemRange->start->get('second'))->toBe(0)
        ->and($impliedMeridiemRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($impliedMeridiemRange->start->isCertain('meridiem'))->toBeTrue()
        ->and($impliedMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:30:00')
        ->and($impliedMeridiemRange->end?->get('hour'))->toBe(15)
        ->and($impliedMeridiemRange->end?->get('minute'))->toBe(10)
        ->and($impliedMeridiemRange->end?->get('second'))->toBe(0)
        ->and($impliedMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:10:00')
        ->and($impliedMeridiemRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($dottedMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:20:00')
        ->and($dottedMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($fullWidthMeridiemRange->text)->toBe('午後６時半－１１時')
        ->and($fullWidthMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($overnightMeridiemRange->start->date()->toDateTimeString())->toBe('2012-08-10 23:30:00')
        ->and($overnightMeridiemRange->end?->date()->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($overnightMeridiemRange->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($overnightTwentyFourHourRange->start->date()->toDateTimeString())->toBe('2012-08-10 23:20:00')
        ->and($overnightTwentyFourHourRange->end?->date()->toDateTimeString())->toBe('2012-08-11 02:00:00')
        ->and($randomDateRange->text)->toBe('2014年3月5日午前 6 時から 7 時')
        ->and($randomWeekdayTime->text)->toBe('次の土曜日1時30分二十九秒')
        ->and($randomCasualTime->text)->toBe('昨日午前六時')
        ->and($randomMonthTime->text)->toBe('６月４日3:00am')
        ->and($randomPreviousWeekdayTime->text)->toBe('前の金曜日16時')
        ->and($randomStandardTime->text)->toBe('3月17日 20時15')
        ->and($weekdayTime->text)->toBe('水曜日 22時')
        ->and($weekdayTime->start->date()->toDateTimeString())->toBe('2012-08-08 22:00:00')
        ->and($weekdayTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($japanese->parseText('10時', '2012-08-10')[0]->text)->toBe('10時')
        ->and($japanese->parseText('12時', '2012-08-10')[0]->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($japanese->parseText('午後１3時', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('25時', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('5時70分', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('5時30分65秒', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('23時-25時', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('3時-5時70分', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('3時-5時30分65秒', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('1', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('12', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('12a', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('1時間', '2012-08-10'))->toBe([])
        ->and($japanese->parseText('25時間', '2012-08-10'))->toBe([]);
});
