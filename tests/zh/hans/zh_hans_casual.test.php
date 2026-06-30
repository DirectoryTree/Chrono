<?php

use DirectoryTree\Chrono\Chrono;

it('parses simplified chinese casual dates', function () {
    $chinese = Chrono::zhHans();
    $today = $chinese->parseText('我今天要打游戏', '2012-08-10 12:00')[0];
    $tomorrow = $chinese->parseText('我明日要打游戏', '2012-08-10 12:00')[0];
    $lateNightTomorrow = $chinese->parseText('我明天要打游戏', '2012-08-10 01:00')[0];
    $dayAfterTomorrowMorning = $chinese->parseText('我后天凌晨要打游戏', '2012-08-10 00:00')[0];
    $threeDaysAgoMorning = $chinese->parseText('我大前天凌晨要打游戏', '2012-08-10 00:00')[0];
    $dayBeforeYesterday = $chinese->parseText('我前天要打游戏', '2012-08-10 12:00')[0];
    $yesterday = $chinese->parseText('我昨日要打游戏', '2012-08-10 12:00')[0];
    $morning = $chinese->parseText('我今天早上要打游戏', '2012-08-10 12:00')[0];
    $afternoon = $chinese->parseText('我下午要打游戏', '2012-08-10 12:00')[0];
    $tonight = $chinese->parseText('我今晚要打游戏', '2012-08-10 12:00')[0];
    $lastNight = $chinese->parseText('我昨天晚上要打游戏', '2012-08-10 12:00')[0];

    expect($today->text)->toBe('今天')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($tomorrow->text)->toBe('明日')
        ->and($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($lateNightTomorrow->text)->toBe('明天')
        ->and($lateNightTomorrow->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dayAfterTomorrowMorning->text)->toBe('后天凌晨')
        ->and($dayAfterTomorrowMorning->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($threeDaysAgoMorning->text)->toBe('大前天凌晨')
        ->and($threeDaysAgoMorning->start->date()->toDateTimeString())->toBe('2012-08-07 00:00:00')
        ->and($dayBeforeYesterday->text)->toBe('前天')
        ->and($dayBeforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($yesterday->text)->toBe('昨日')
        ->and($yesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($morning->text)->toBe('今天早上')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($afternoon->text)->toBe('下午')
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($tonight->text)->toBe('今晚')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00');
});

it('parses simplified chinese casual date ranges and random casual text', function () {
    $chinese = Chrono::zhHans();
    $combined = $chinese->parseText('我今天下午5点要打游戏', '2012-08-10 12:00')[0];
    $earlyRange = $chinese->parseText('我今天 - 下周五要打游戏', '2012-08-04 12:00')[0];
    $sameDayRange = $chinese->parseText('我今日 - 下周五要打游戏', '2012-08-10 12:00')[0];
    $night = $chinese->parseText('今日夜晚', '2012-01-01 12:00')[0];
    $eveningTime = $chinese->parseText('今晚8点正', '2012-01-01 12:00')[0];
    $evening = $chinese->parseText('晚上8点', '2012-01-01 12:00')[0];
    $weekday = $chinese->parseText('星期四')[0];

    expect($combined->text)->toBe('今天下午5点')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($earlyRange->text)->toBe('今天 - 下周五')
        ->and($earlyRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($earlyRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->text)->toBe('今日 - 下周五')
        ->and($sameDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->end?->date()->toDateTimeString())->toBe('2012-08-17 12:00:00')
        ->and($night->text)->toBe('今日夜晚')
        ->and($night->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($eveningTime->text)->toBe('今晚8点正')
        ->and($eveningTime->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($evening->text)->toBe('晚上8点')
        ->and($evening->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($weekday->text)->toBe('星期四')
        ->and($weekday->start->get('weekday'))->toBe(4);
});
