<?php

use Chrono\Chrono;

it('parses default chinese engine international and simplified traditional input', function () {
    $chinese = Chrono::zh();
    $iso = $chinese->parseText('1994-11-05T08:15:30-05:30', '2012-08-08')[0];
    $simplified = $chinese->parseText('明天早上8点', '2012-08-08 12:00')[0];
    $traditional = $chinese->parseText('明天早上8點', '2012-08-08 12:00')[0];

    expect($iso->text)->toBe('1994-11-05T08:15:30-05:30')
        ->and($iso->start->get('year'))->toBe(1994)
        ->and($iso->start->get('month'))->toBe(11)
        ->and($iso->start->get('day'))->toBe(5)
        ->and($iso->start->get('hour'))->toBe(8)
        ->and($iso->start->get('minute'))->toBe(15)
        ->and($iso->start->get('second'))->toBe(30)
        ->and($iso->start->get('timezoneOffset'))->toBe(-330)
        ->and($iso->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 -05:30')
        ->and($simplified->text)->toBe('明天早上8点')
        ->and($simplified->start->get('year'))->toBe(2012)
        ->and($simplified->start->get('month'))->toBe(8)
        ->and($simplified->start->get('day'))->toBe(9)
        ->and($simplified->start->get('hour'))->toBe(8)
        ->and($simplified->start->date()->toDateTimeString())->toBe('2012-08-09 08:00:00')
        ->and($traditional->text)->toBe('明天早上8點')
        ->and($traditional->start->get('year'))->toBe(2012)
        ->and($traditional->start->get('month'))->toBe(8)
        ->and($traditional->start->get('day'))->toBe(9)
        ->and($traditional->start->get('hour'))->toBe(8)
        ->and($traditional->start->date()->toDateTimeString())->toBe('2012-08-09 08:00:00');
});

it('parses chinese time expressions and merges date ranges', function () {
    $chinese = Chrono::zh();
    $iso = $chinese->parseText('1994-11-05T08:15:30-05:30', '2012-08-08')[0];
    $simplifiedDateTime = $chinese->parseText('明天早上8点', '2012-08-08 12:00')[0];
    $traditionalDateTime = $chinese->parseText('明天早上8點', '2012-08-08 12:00')[0];
    $time = $chinese->parseText('下午3点半到5点', '2012-08-10')[0];
    $dateTime = $chinese->parseText('2014年7月12日下午3点', '2012-08-10')[0];
    $range = $chinese->parseText('7月12日到7月14日', '2012-08-10')[0];
    $endYearRange = $chinese->parseText('7月12日到2014年7月14日', '2012-08-10')[0];
    $explicitEndDayRange = Chrono::zhHans()->parseText('今晚10点 - 明天早上6点', '2012-08-10')[0];
    $explicitEndDayRangeWithShortDay = Chrono::zhHans()->parseText('今晚10点 - 明早6点', '2012-08-10 12:00')[0];
    $multiDayTimeRange = Chrono::zhHans()->parseText('今天早上9点 - 后天凌晨3点', '2012-08-10')[0];
    $hantCantoneseRange = Chrono::zhHant()->parseText('聽晚10點到聽晚11點', '2012-08-10 12:00')[0];
    $hantYesterdayMorning = Chrono::zhHant()->parseText('尋日朝早六點正', '2012-08-10')[0];

    expect($iso->text)->toBe('1994-11-05T08:15:30-05:30')
        ->and($iso->start->timezoneOffset())->toBe(-330)
        ->and($iso->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 -05:30')
        ->and($simplifiedDateTime->text)->toBe('明天早上8点')
        ->and($simplifiedDateTime->start->date()->toDateTimeString())->toBe('2012-08-09 08:00:00')
        ->and($traditionalDateTime->text)->toBe('明天早上8點')
        ->and($traditionalDateTime->start->date()->toDateTimeString())->toBe('2012-08-09 08:00:00')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($time->start->tags())->toContain('parser/ZHHansTimeExpressionParser')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2014-07-12 15:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($range->end?->date()->format('m-d H:i:s'))->toBe('07-14 12:00:00')
        ->and($endYearRange->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($endYearRange->end?->date()->toDateTimeString())->toBe('2014-07-14 12:00:00')
        ->and($explicitEndDayRange->text)->toBe('今晚10点 - 明天早上6点')
        ->and($explicitEndDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($explicitEndDayRange->end?->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($explicitEndDayRangeWithShortDay->text)->toBe('今晚10点 - 明早6点')
        ->and($explicitEndDayRangeWithShortDay->end?->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($multiDayTimeRange->text)->toBe('今天早上9点 - 后天凌晨3点')
        ->and($multiDayTimeRange->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($multiDayTimeRange->end?->date()->toDateTimeString())->toBe('2012-08-12 03:00:00')
        ->and($hantCantoneseRange->text)->toBe('聽晚10點到聽晚11點')
        ->and($hantCantoneseRange->start->date()->toDateTimeString())->toBe('2012-08-11 22:00:00')
        ->and($hantCantoneseRange->end?->date()->toDateTimeString())->toBe('2012-08-11 23:00:00')
        ->and($hantYesterdayMorning->text)->toBe('尋日朝早六點正')
        ->and($hantYesterdayMorning->start->date()->toDateTimeString())->toBe('2012-08-09 06:00:00')
        ->and($range->tags())->toContain('refiner/mergeDateRange');
});

it('parses simplified chinese casual dates dates weekdays and deadlines', function () {
    $chinese = Chrono::zhHans();
    $today = $chinese->parseText('我今天要打游戏', '2012-08-10 12:00')[0];
    $tomorrowLateNight = $chinese->parseText('我明天要打游戏', '2012-08-10 01:00')[0];
    $dayAfterTomorrow = $chinese->parseText('我后天凌晨要打游戏', '2012-08-10 00:00')[0];
    $threeDaysAgo = $chinese->parseText('我大前天凌晨要打游戏', '2012-08-10 00:00')[0];
    $lastNight = $chinese->parseText('我昨天晚上要打游戏', '2012-08-10 12:00')[0];
    $casual = $chinese->parseText('明天上午', '2012-08-10 09:30')[0];
    $combined = $chinese->parseText('我今天下午5点要打游戏', '2012-08-10 12:00')[0];
    $casualRange = $chinese->parseText('我今天 - 下周五要打游戏', '2012-08-04 12:00')[0];
    $night = $chinese->parseText('今日夜晚', '2012-01-01 12:00')[0];
    $date = $chinese->parseText('2014年7月12日', '2012-08-10')[0];
    $prefixedDate = $chinese->parseText('我2016年9月3号要打游戏', '2012-08-10')[0];
    $hanDate = $chinese->parseText('我二零一六年，九月三号要打游戏', '2012-08-10')[0];
    $yearlessDate = $chinese->parseText('我九月三号要打游戏', '2014-08-10')[0];
    $dateRange = $chinese->parseText('2016年9月3号-2017年10月24号', '2012-08-10')[0];
    $weekday = $chinese->parseText('下个星期一', '2012-08-10')[0];
    $lastWeekday = $chinese->parseText('我上个礼拜三在打游戏', '2016-09-02')[0];
    $nextSunday = $chinese->parseText('我下星期天打游戏', '2016-09-02')[0];
    $thisMonday = $chinese->parseText('我这个星期一要打游戏', '2012-08-10')[0];
    $weekdayRange = $chinese->parseText('星期六至星期一', '2016-09-02', ['forwardDate' => true])[0];
    $weekdayRangeTo = $chinese->parseText('星期六到星期一', '2016-09-02', ['forwardDate' => true])[0];
    $weekdayRangeTilde = $chinese->parseText('星期六~星期一', '2016-09-02', ['forwardDate' => true])[0];
    $weekdayRangeFullWidthTilde = $chinese->parseText('星期六～星期一', '2016-09-02', ['forwardDate' => true])[0];
    $weekdayRangeFullWidthDash = $chinese->parseText('星期六－星期一', '2016-09-02', ['forwardDate' => true])[0];
    $weekdayRangeJapaneseDash = $chinese->parseText('星期六ー星期一', '2016-09-02', ['forwardDate' => true])[0];
    $deadline = $chinese->parseText('3天后', '2012-08-10 09:30')[0];
    $daysWithin = $chinese->parseText('五日内我要通关游戏', '2012-08-10')[0];
    $digitsDaysWithin = $chinese->parseText('5日之内我要通关游戏', '2012-08-10')[0];
    $tenDaysWithin = $chinese->parseText('十日内我要通关游戏', '2012-08-10')[0];
    $minutesLater = $chinese->parseText('五分钟后', '2012-08-10 12:14')[0];
    $clockWithin = $chinese->parseText('一个钟之内', '2012-08-10 12:14')[0];
    $digitsMinutesLater = $chinese->parseText('5分钟之后出门', '2012-08-10 12:14')[0];
    $secondsLater = $chinese->parseText('我要5秒之后出门', '2012-08-10 12:14')[0];
    $halfHour = $chinese->parseText('半小时之内', '2012-08-10 12:14')[0];
    $twoWeeksWithin = $chinese->parseText('两个礼拜内答复我', '2012-08-10 12:14')[0];
    $oneMonthWithin = $chinese->parseText('1个月之内答复我', '2012-08-10 12:14')[0];
    $monthsWithin = $chinese->parseText('几个月之内答复我', '2012-08-10 12:14')[0];
    $yearWithin = $chinese->parseText('一年内答复我', '2012-08-10 12:14')[0];
    $digitsYearWithin = $chinese->parseText('1年之内答复我', '2012-08-10 12:14')[0];
    $secondsClockLater = $chinese->parseText('5秒钟后', '2012-08-10 12:14')[0];
    $hoursLater = $chinese->parseText('2小时后', '2012-08-10 12:14')[0];
    $weeksLater = $chinese->parseText('2星期后', '2012-08-10 12:14')[0];
    $minutesAfter = $chinese->parseText('5分钟过后', '2012-08-10 12:14')[0];

    expect($today->index)->toBe(3)
        ->and($today->text)->toBe('今天')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($tomorrowLateNight->text)->toBe('明天')
        ->and($tomorrowLateNight->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dayAfterTomorrow->text)->toBe('后天凌晨')
        ->and($dayAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($threeDaysAgo->text)->toBe('大前天凌晨')
        ->and($threeDaysAgo->start->date()->toDateTimeString())->toBe('2012-08-07 00:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00')
        ->and($casual->start->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($casual->start->tags())->toContain('parser/ZHHansCasualDateParser')
        ->and($combined->text)->toBe('今天下午5点')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($casualRange->text)->toBe('今天 - 下周五')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($night->text)->toBe('今日夜晚')
        ->and($night->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($date->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHansDateParser')
        ->and($prefixedDate->text)->toBe('2016年9月3号')
        ->and($prefixedDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($hanDate->text)->toBe('二零一六年，九月三号')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($yearlessDate->text)->toBe('九月三号')
        ->and($yearlessDate->start->date()->toDateTimeString())->toBe('2014-09-03 12:00:00')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ZHHansRelationWeekdayParser')
        ->and($lastWeekday->text)->toBe('上个礼拜三')
        ->and($lastWeekday->start->date()->toDateTimeString())->toBe('2016-08-24 12:00:00')
        ->and($lastWeekday->start->isCertain('day'))->toBeTrue()
        ->and($nextSunday->text)->toBe('下星期天')
        ->and($nextSunday->start->date()->toDateTimeString())->toBe('2016-09-04 12:00:00')
        ->and($thisMonday->text)->toBe('这个星期一')
        ->and($thisMonday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($thisMonday->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->text)->toBe('星期六至星期一')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00')
        ->and($weekdayRangeTo->text)->toBe('星期六到星期一')
        ->and($weekdayRangeTo->start->get('day'))->toBe(3)
        ->and($weekdayRangeTo->end?->get('day'))->toBe(5)
        ->and($weekdayRangeTilde->text)->toBe('星期六~星期一')
        ->and($weekdayRangeTilde->start->get('day'))->toBe(3)
        ->and($weekdayRangeTilde->end?->get('day'))->toBe(5)
        ->and($weekdayRangeFullWidthTilde->text)->toBe('星期六～星期一')
        ->and($weekdayRangeFullWidthTilde->start->get('day'))->toBe(3)
        ->and($weekdayRangeFullWidthTilde->end?->get('day'))->toBe(5)
        ->and($weekdayRangeFullWidthDash->text)->toBe('星期六－星期一')
        ->and($weekdayRangeFullWidthDash->start->get('day'))->toBe(3)
        ->and($weekdayRangeFullWidthDash->end?->get('day'))->toBe(5)
        ->and($weekdayRangeJapaneseDash->text)->toBe('星期六ー星期一')
        ->and($weekdayRangeJapaneseDash->start->get('day'))->toBe(3)
        ->and($weekdayRangeJapaneseDash->end?->get('day'))->toBe(5)
        ->and($weekdayRange->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('weekday'))->toBeTrue()
        ->and($weekdayRange->end?->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($deadline->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($deadline->start->tags())->toContain('parser/ZHHansDeadlineFormatParser')
        ->and($daysWithin->text)->toBe('五日内')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($digitsDaysWithin->text)->toBe('5日之内')
        ->and($digitsDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($tenDaysWithin->text)->toBe('十日内')
        ->and($tenDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($minutesLater->text)->toBe('五分钟后')
        ->and($minutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($clockWithin->text)->toBe('一个钟之内')
        ->and($clockWithin->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($digitsMinutesLater->text)->toBe('5分钟之后')
        ->and($digitsMinutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($secondsLater->index)->toBe(6)
        ->and($secondsLater->text)->toBe('5秒之后')
        ->and($secondsLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($halfHour->text)->toBe('半小时之内')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($twoWeeksWithin->text)->toBe('两个礼拜内')
        ->and($twoWeeksWithin->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($oneMonthWithin->text)->toBe('1个月之内')
        ->and($oneMonthWithin->start->date()->toDateTimeString())->toBe('2012-09-10 12:00:00')
        ->and($monthsWithin->text)->toBe('几个月之内')
        ->and($monthsWithin->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00')
        ->and($yearWithin->text)->toBe('一年内')
        ->and($yearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($digitsYearWithin->text)->toBe('1年之内')
        ->and($digitsYearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($secondsClockLater->text)->toBe('5秒钟后')
        ->and($secondsClockLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($hoursLater->text)->toBe('2小时后')
        ->and($hoursLater->start->date()->toDateTimeString())->toBe('2012-08-10 14:14:00')
        ->and($weeksLater->text)->toBe('2星期后')
        ->and($weeksLater->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($minutesAfter->text)->toBe('5分钟过后')
        ->and($minutesAfter->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00');
});

it('parses traditional chinese casual dates dates weekdays and deadlines', function () {
    $chinese = Chrono::zhHant();
    $now = $chinese->parseText('雞而家全部都係雞', '2012-08-10 08:09:10.011')[0];
    $today = $chinese->parseText('雞今日全部都係雞', '2012-08-10 12:00')[0];
    $tomorrowLateNight = $chinese->parseText('雞明天全部都係雞', '2012-08-10 01:00')[0];
    $dayAfterTomorrow = $chinese->parseText('雞後天凌晨全部都係雞', '2012-08-10 00:00')[0];
    $threeDaysAgo = $chinese->parseText('雞大前天凌晨全部都係雞', '2012-08-10 00:00')[0];
    $lastNight = $chinese->parseText('雞昨天晚上全部都係雞', '2012-08-10 12:00')[0];
    $casual = $chinese->parseText('聽日下午', '2012-08-10 09:30')[0];
    $combined = $chinese->parseText('雞今日晏晝5點全部都係雞', '2012-08-10 12:00')[0];
    $casualRange = $chinese->parseText('雞今日 - 下禮拜五全部都係雞', '2012-08-04 12:00')[0];
    $date = $chinese->parseText('二零一四年七月十二日', '2012-08-10')[0];
    $prefixedDate = $chinese->parseText('雞2016年9月3號全部都係雞', '2012-08-10')[0];
    $hanDate = $chinese->parseText('雞二零一六年，九月三號全部都係雞', '2012-08-10')[0];
    $yearlessDate = $chinese->parseText('雞九月三號全部都係雞', '2014-08-10')[0];
    $dateRange = $chinese->parseText('二零一六年九月三號ー2017年10月24號', '2012-08-10')[0];
    $weekday = $chinese->parseText('下個星期一', '2012-08-10')[0];
    $lastWeekday = $chinese->parseText('雞上個禮拜三全部都係雞', '2016-09-02')[0];
    $thisMonday = $chinese->parseText('我這個星期一要打遊戲', '2012-08-10')[0];
    $weekdayRange = $chinese->parseText('星期六-星期一', '2016-09-02', ['forwardDate' => true])[0];
    $deadline = $chinese->parseText('三天後', '2012-08-10 09:30')[0];
    $daysWithin = $chinese->parseText('五日內我地有d野做', '2012-08-10')[0];
    $digitsDaysWithin = $chinese->parseText('5日之內我地有d野做', '2012-08-10')[0];
    $tenDaysWithin = $chinese->parseText('十日內我地有d野做', '2012-08-10')[0];
    $minutesLater = $chinese->parseText('五分鐘後', '2012-08-10 12:14')[0];
    $clockWithin = $chinese->parseText('一個鐘之內', '2012-08-10 12:14')[0];
    $digitsMinutesLater = $chinese->parseText('5分鐘之後我就收皮', '2012-08-10 12:14')[0];
    $secondsLater = $chinese->parseText('係5秒之後你就會收皮', '2012-08-10 12:14')[0];
    $halfHour = $chinese->parseText('半小時之內', '2012-08-10 12:14')[0];
    $twoWeeksWithin = $chinese->parseText('兩個禮拜內答覆我', '2012-08-10 12:14')[0];
    $oneMonthWithin = $chinese->parseText('1個月之內答覆我', '2012-08-10 12:14')[0];
    $monthsWithin = $chinese->parseText('幾個月之內答覆我', '2012-08-10 12:14')[0];
    $yearWithin = $chinese->parseText('一年內答覆我', '2012-08-10 12:14')[0];
    $digitsYearWithin = $chinese->parseText('1年之內答覆我', '2012-08-10 12:14')[0];

    expect($now->index)->toBe(3)
        ->and($now->text)->toBe('而家')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($today->text)->toBe('今日')
        ->and($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($tomorrowLateNight->text)->toBe('明天')
        ->and($tomorrowLateNight->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dayAfterTomorrow->text)->toBe('後天凌晨')
        ->and($dayAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-12 00:00:00')
        ->and($threeDaysAgo->text)->toBe('大前天凌晨')
        ->and($threeDaysAgo->start->date()->toDateTimeString())->toBe('2012-08-07 00:00:00')
        ->and($lastNight->text)->toBe('昨天晚上')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00')
        ->and($casual->start->date()->toDateTimeString())->toBe('2012-08-11 15:00:00')
        ->and($casual->start->tags())->toContain('parser/ZHHantCasualDateParser')
        ->and($combined->text)->toBe('今日晏晝5點')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($casualRange->text)->toBe('今日 - 下禮拜五')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($date->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHantDateParser')
        ->and($prefixedDate->text)->toBe('2016年9月3號')
        ->and($prefixedDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($hanDate->text)->toBe('二零一六年，九月三號')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($yearlessDate->text)->toBe('九月三號')
        ->and($yearlessDate->start->date()->toDateTimeString())->toBe('2014-09-03 12:00:00')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/ZHHantRelationWeekdayParser')
        ->and($lastWeekday->text)->toBe('上個禮拜三')
        ->and($lastWeekday->start->date()->toDateTimeString())->toBe('2016-08-24 12:00:00')
        ->and($thisMonday->text)->toBe('這個星期一')
        ->and($thisMonday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($weekdayRange->text)->toBe('星期六-星期一')
        ->and($weekdayRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($weekdayRange->end?->date()->toDateTimeString())->toBe('2016-09-05 12:00:00')
        ->and($weekdayRange->start->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->start->isCertain('weekday'))->toBeTrue()
        ->and($weekdayRange->end?->isCertain('day'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('month'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('year'))->toBeFalse()
        ->and($weekdayRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($deadline->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($deadline->start->tags())->toContain('parser/ZHHantDeadlineFormatParser')
        ->and($daysWithin->text)->toBe('五日內')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($digitsDaysWithin->text)->toBe('5日之內')
        ->and($digitsDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($tenDaysWithin->text)->toBe('十日內')
        ->and($tenDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($minutesLater->text)->toBe('五分鐘後')
        ->and($minutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($clockWithin->text)->toBe('一個鐘之內')
        ->and($clockWithin->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($digitsMinutesLater->text)->toBe('5分鐘之後')
        ->and($digitsMinutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($secondsLater->index)->toBe(3)
        ->and($secondsLater->text)->toBe('5秒之後')
        ->and($secondsLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($halfHour->text)->toBe('半小時之內')
        ->and($halfHour->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($twoWeeksWithin->text)->toBe('兩個禮拜內')
        ->and($twoWeeksWithin->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($oneMonthWithin->text)->toBe('1個月之內')
        ->and($oneMonthWithin->start->date()->toDateTimeString())->toBe('2012-09-10 12:00:00')
        ->and($monthsWithin->text)->toBe('幾個月之內')
        ->and($monthsWithin->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00')
        ->and($yearWithin->text)->toBe('一年內')
        ->and($yearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($digitsYearWithin->text)->toBe('1年之內')
        ->and($digitsYearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00');
});
