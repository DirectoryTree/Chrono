<?php

use Chrono\Chrono;

it('parses traditional chinese casual dates', function () {
    $chinese = Chrono::zhHant();
    $now = $chinese->parseText('雞而家全部都係雞', '2012-08-10 08:09:10.011')[0];
    $today = $chinese->parseText('雞今日全部都係雞', '2012-08-10 12:00')[0];
    $tomorrow = $chinese->parseText('雞聽日全部都係雞', '2012-08-10 12:00')[0];
    $lateNightTomorrow = $chinese->parseText('雞明天全部都係雞', '2012-08-10 01:00')[0];
    $dayBeforeYesterday = $chinese->parseText('雞前日全部都係雞', '2012-08-10 12:00')[0];
    $yesterday = $chinese->parseText('雞琴日全部都係雞', '2012-08-10 12:00')[0];
    $morning = $chinese->parseText('雞今日朝早全部都係雞', '2012-08-10 12:00')[0];
    $afternoon = $chinese->parseText('雞晏晝全部都係雞', '2012-08-10 12:00')[0];
    $tonight = $chinese->parseText('雞今晚全部都係雞', '2012-08-10 12:00')[0];
    $lastNight = $chinese->parseText('雞昨天晚上全部都係雞', '2012-08-10 12:00')[0];

    expect($now->text)->toBe('而家')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($today->text)->toBe('今日')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($tomorrow->text)->toBe('聽日')
        ->and($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($lateNightTomorrow->text)->toBe('明天')
        ->and($lateNightTomorrow->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dayBeforeYesterday->text)->toBe('前日')
        ->and($dayBeforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($yesterday->text)->toBe('琴日')
        ->and($yesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($morning->text)->toBe('今日朝早')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($afternoon->text)->toBe('晏晝')
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($tonight->text)->toBe('今晚')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00');
});

it('parses traditional chinese casual date ranges and random casual text', function () {
    $chinese = Chrono::zhHant();
    $combined = $chinese->parseText('雞今日晏晝5點全部都係雞', '2012-08-10 12:00')[0];
    $earlyRange = $chinese->parseText('雞今日 - 下禮拜五全部都係雞', '2012-08-04 12:00')[0];
    $sameDayRange = $chinese->parseText('雞今日 - 下禮拜五全部都係雞', '2012-08-10 12:00')[0];
    $night = $chinese->parseText('今日夜晚', '2012-01-01 12:00')[0];
    $eveningTime = $chinese->parseText('今晚8點正', '2012-01-01 12:00')[0];

    expect($combined->text)->toBe('今日晏晝5點')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($earlyRange->text)->toBe('今日 - 下禮拜五')
        ->and($earlyRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($earlyRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->text)->toBe('今日 - 下禮拜五')
        ->and($sameDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->end?->date()->toDateTimeString())->toBe('2012-08-17 12:00:00')
        ->and($night->text)->toBe('今日夜晚')
        ->and($night->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($eveningTime->text)->toBe('今晚8點正')
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00');
});
