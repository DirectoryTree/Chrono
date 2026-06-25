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
        ->and($chinese->parseText('晚上11点 ~ 凌晨2点', '2012-08-10')[0]->end?->date()->toDateTimeString())
        ->toBe('2012-08-11 02:00:00');
});

it('parses simplified chinese iso-like date time expressions', function () {
    $result = Chrono::zhHans()->parseText('2023-10-26 10:30:00', '2012-08-10')[0];

    expect($result->text)->toBe('2023-10-26 10:30:00')
        ->and($result->start->date()->toDateTimeString())->toBe('2023-10-26 10:30:00');
});
