<?php

use Chrono\Chrono;

it('parses simplified chinese time expressions', function () {
    $chinese = Chrono::zhHans();
    $single = $chinese->parseText('我上午6点13分打游戏', '2012-08-10')[0];
    $relativeDay = $chinese->parseText('我明天上午8点要打游戏', '2012-08-10 12:00')[0];
    $morning = $chinese->parseText('早上8点', '2012-08-10 12:00')[0];
    $time = $chinese->parseText('下午3点半到5点', '2012-08-10')[0];
    $range = $chinese->parseText('我从今早八点十分至下午11点32分打游戏', '2012-08-10')[0];
    $pmRange = $chinese->parseText('6点30pm-11点pm', '2012-08-10')[0];
    $dateTime = $chinese->parseText('我二零一八年十一月二十六日下午三点半五十九秒打游戏', '2012-08-10')[0];
    $meridiemCarry = $chinese->parseText('1点pm到3点', '2012-08-10')[0];

    expect($single->text)->toBe('上午6点13分')
        ->and($single->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($relativeDay->text)->toBe('明天上午8点')
        ->and($relativeDay->start->date()->toDateTimeString())->toBe('2012-08-11 08:00:00')
        ->and($morning->text)->toBe('早上8点')
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 08:00:00')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($time->start->tags())->toContain('parser/ZHHansTimeExpressionParser')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($range->text)->toBe('从今早八点十分至下午11点32分')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 23:32:00')
        ->and($pmRange->text)->toBe('6点30pm-11点pm')
        ->and($pmRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($pmRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($dateTime->text)->toBe('二零一八年十一月二十六日下午三点半五十九秒')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2018-11-26 15:30:59')
        ->and($dateTime->start->isCertain('millisecond'))->toBeFalse()
        ->and($meridiemCarry->text)->toBe('1点pm到3点')
        ->and($meridiemCarry->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($meridiemCarry->end?->date()->toDateTimeString())->toBe('2012-08-11 03:00:00')
        ->and($meridiemCarry->end?->isCertain('meridiem'))->toBeFalse();
});

it('parses simplified chinese random date time expressions', function () {
    $chinese = Chrono::zhHans();

    expect($chinese->parseText('2014年, 3月5日早上 6 点至 7 点', '2012-08-10')[0]->text)
        ->toBe('2014年, 3月5日早上 6 点至 7 点')
        ->and($chinese->parseText('下星期六凌晨1点30分二十九秒', '2012-08-10')[0]->text)
        ->toBe('下星期六凌晨1点30分二十九秒')
        ->and($chinese->parseText('昨天早上六点正', '2012-08-10')[0]->text)
        ->toBe('昨天早上六点正')
        ->and($chinese->parseText('六月四日3:00am', '2012-08-10')[0]->text)
        ->toBe('六月四日3:00am')
        ->and($chinese->parseText('上个礼拜五16时', '2012-08-10')[0]->text)
        ->toBe('上个礼拜五16时')
        ->and($chinese->parseText('3月17日 20点15', '2012-08-10')[0]->text)
        ->toBe('3月17日 20点15')
        ->and($chinese->parseText('10点', '2012-08-10')[0]->text)
        ->toBe('10点')
        ->and($chinese->parseText('中午12点', '2012-08-10')[0]->start->get('hour'))
        ->toBe(12)
        ->and($chinese->parseText('今晚10时', '2012-08-10')[0]->start->get('hour'))
        ->toBe(22)
        ->and($chinese->parseText('昨晚8点', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-09 20:00:00')
        ->and($chinese->parseText('前天下午三点', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-08 15:00:00')
        ->and($chinese->parseText('大后天晚上9点30分', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-13 21:30:00')
        ->and($chinese->parseText('三点', '2012-08-10 01:00')[0]->start->get('hour'))
        ->toBe(3)
        ->and($chinese->parseText('晚上11点 ~ 凌晨2点', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 02:00:00');
});

it('parses simplified chinese iso-like date time expressions', function () {
    $result = Chrono::zhHans()->parseText('2023-10-26 10:30:00', '2012-08-10')[0];

    expect($result->text)->toBe('2023-10-26 10:30:00')
        ->and($result->start->date()->toDateTimeString())->toBe('2023-10-26 10:30:00');
});

it('parses simplified chinese time ranges with relative days', function () {
    $chinese = Chrono::zhHans();

    expect($chinese->parseText('今晚10点 - 明天早上6点', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-10 22:00:00')
        ->and($chinese->parseText('今晚10点 - 明天早上6点', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 06:00:00')
        ->and($chinese->parseText('今天早上9点 - 后天凌晨3点', '2012-08-10')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-10 09:00:00')
        ->and($chinese->parseText('今天早上9点 - 后天凌晨3点', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-12 03:00:00')
        ->and($chinese->parseText('今晚10点 - 明早6点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 06:00:00')
        ->and($chinese->parseText('明天10点到明天11点', '2012-08-10 12:00')[0]->text)
        ->toBe('明天10点到明天11点')
        ->and($chinese->parseText('明天10点到明天11点', '2012-08-10 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-11 10:00:00')
        ->and($chinese->parseText('明天10点到明天11点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 11:00:00');
});

it('parses simplified chinese time ranges with seconds and am pm variations', function () {
    $chinese = Chrono::zhHans();
    $secondsRange = $chinese->parseText('9:00:00 - 9:00:30', '2012-08-10')[0];
    $afternoonRange = $chinese->parseText('下午2点 - 晚上8点', '2012-08-10')[0];
    $pmSuffixRange = $chinese->parseText('3点 - 5点PM', '2012-08-10 12:00')[0];
    $crossDayRange = $chinese->parseText('晚上10点 - 2点', '2012-08-10')[0];

    expect($secondsRange->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($secondsRange->end?->date()->toDateTimeString())->toBe('2012-08-10 09:00:30')
        ->and($afternoonRange->start->date()->toDateTimeString())->toBe('2012-08-10 14:00:00')
        ->and($afternoonRange->end?->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($pmSuffixRange->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($pmSuffixRange->end?->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($crossDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($crossDayRange->end?->date()->toDateTimeString())->toBe('2012-08-11 02:00:00');
});

it('parses simplified chinese time ranges with relative day variations', function () {
    $chinese = Chrono::zhHans();

    expect($chinese->parseText('今晚10点 - 昨晚10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-09 22:00:00')
        ->and($chinese->parseText('今晚10点 - 前天晚上10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-08 22:00:00')
        ->and($chinese->parseText('今晚10点 - 大前天晚上10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-07 22:00:00')
        ->and($chinese->parseText('今晚10点 - 前晚10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-08 22:00:00')
        ->and($chinese->parseText('今晚10点 - 大前晚10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-07 22:00:00')
        ->and($chinese->parseText('今晚10点 - 后天晚上10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-12 22:00:00')
        ->and($chinese->parseText('今晚10点 - 大后天晚上10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-13 22:00:00')
        ->and($chinese->parseText('今晚10点 - 后早10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-12 10:00:00')
        ->and($chinese->parseText('今晚10点 - 大后早10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-13 10:00:00')
        ->and($chinese->parseText('今早10点 - 明早10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 10:00:00')
        ->and($chinese->parseText('今早10点 - 明天上午10点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 10:00:00')
        ->and($chinese->parseText('今早10点 - 明天凌晨2点', '2012-08-10 12:00')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 02:00:00')
        ->and($chinese->parseText('下午2点 - 明天下午3点', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 15:00:00');
});
