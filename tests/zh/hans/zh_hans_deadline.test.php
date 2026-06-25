<?php

use Chrono\Chrono;

it('parses simplified chinese deadline expressions', function () {
    $chinese = Chrono::zhHans();
    $daysWithin = $chinese->parseText('五日内我要通关游戏', '2012-08-10')[0];
    $numericDaysWithin = $chinese->parseText('5日之内我要通关游戏', '2012-08-10')[0];
    $tenDaysWithin = $chinese->parseText('十日内我要通关游戏', '2012-08-10')[0];
    $fiveMinutesLater = $chinese->parseText('五分钟后', '2012-08-10 12:14')[0];
    $oneHourWithin = $chinese->parseText('一个钟之内', '2012-08-10 12:14')[0];
    $numericMinutesLater = $chinese->parseText('5分钟之后出门', '2012-08-10 12:14')[0];
    $secondsLater = $chinese->parseText('我要5秒之后出门', '2012-08-10 12:14')[0];
    $halfHourWithin = $chinese->parseText('半小时之内', '2012-08-10 12:14')[0];
    $weeksWithin = $chinese->parseText('两个礼拜内答复我', '2012-08-10 12:14')[0];
    $monthWithin = $chinese->parseText('1个月之内答复我', '2012-08-10 12:14')[0];
    $fewMonthsWithin = $chinese->parseText('几个月之内答复我', '2012-08-10 12:14')[0];
    $yearWithin = $chinese->parseText('一年内答复我', '2012-08-10 12:14')[0];
    $numericYearWithin = $chinese->parseText('1年之内答复我', '2012-08-10 12:14')[0];
    $secondsWithMeasureWord = $chinese->parseText('5秒钟后', '2012-08-10 12:14')[0];
    $hoursLater = $chinese->parseText('2小时后', '2012-08-10 12:14')[0];
    $daysLater = $chinese->parseText('3天后', '2012-08-10 12:14')[0];
    $weeksLater = $chinese->parseText('2星期后', '2012-08-10 12:14')[0];
    $minutesAfterward = $chinese->parseText('5分钟过后', '2012-08-10 12:14')[0];

    expect($daysWithin->text)->toBe('五日内')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($daysWithin->start->tags())->toContain('parser/ZHHansDeadlineFormatParser')
        ->and($numericDaysWithin->text)->toBe('5日之内')
        ->and($numericDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($tenDaysWithin->text)->toBe('十日内')
        ->and($tenDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($fiveMinutesLater->text)->toBe('五分钟后')
        ->and($fiveMinutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($oneHourWithin->text)->toBe('一个钟之内')
        ->and($oneHourWithin->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($numericMinutesLater->text)->toBe('5分钟之后')
        ->and($numericMinutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($secondsLater->text)->toBe('5秒之后')
        ->and($secondsLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($halfHourWithin->text)->toBe('半小时之内')
        ->and($halfHourWithin->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($weeksWithin->text)->toBe('两个礼拜内')
        ->and($weeksWithin->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($monthWithin->text)->toBe('1个月之内')
        ->and($monthWithin->start->date()->toDateTimeString())->toBe('2012-09-10 12:00:00')
        ->and($fewMonthsWithin->text)->toBe('几个月之内')
        ->and($fewMonthsWithin->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00')
        ->and($yearWithin->text)->toBe('一年内')
        ->and($yearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($numericYearWithin->text)->toBe('1年之内')
        ->and($numericYearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($secondsWithMeasureWord->text)->toBe('5秒钟后')
        ->and($secondsWithMeasureWord->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($hoursLater->text)->toBe('2小时后')
        ->and($hoursLater->start->date()->toDateTimeString())->toBe('2012-08-10 14:14:00')
        ->and($daysLater->text)->toBe('3天后')
        ->and($daysLater->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weeksLater->text)->toBe('2星期后')
        ->and($weeksLater->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($minutesAfterward->text)->toBe('5分钟过后')
        ->and($minutesAfterward->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00');
});
