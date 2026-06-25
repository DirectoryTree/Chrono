<?php

use Chrono\Chrono;

it('parses traditional chinese time expressions', function () {
    $chinese = Chrono::zhHant();
    $single = $chinese->parseText('雞上午6點13分全部都係雞', '2012-08-10')[0];
    $relativeDay = $chinese->parseText('我明天上午8點要打遊戲', '2012-08-10 12:00')[0];
    $range = $chinese->parseText('雞由今朝八點十分至下午11點32分全部都係雞', '2012-08-10')[0];
    $pmRange = $chinese->parseText('6點30pm-11點pm', '2012-08-10')[0];
    $dateTime = $chinese->parseText('雞二零一八年十一月廿六日下午三時半五十九秒全部都係雞', '2012-08-10')[0];
    $meridiemCarry = $chinese->parseText('1點pm到3點', '2012-08-10')[0];
    $cantoneseFuture = $chinese->parseText('大後日下晝5點', '2012-08-10 12:00')[0];
    $time = $chinese->parseText('聽晚10點到聽晚11點', '2012-08-10 12:00')[0];

    expect($single->text)->toBe('上午6點13分')
        ->and($single->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($relativeDay->text)->toBe('明天上午8點')
        ->and($relativeDay->start->date()->toDateTimeString())->toBe('2012-08-11 08:00:00')
        ->and($range->text)->toBe('由今朝八點十分至下午11點32分')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 23:32:00')
        ->and($pmRange->text)->toBe('6點30pm-11點pm')
        ->and($pmRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($pmRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($dateTime->text)->toBe('二零一八年十一月廿六日下午三時半五十九秒')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2018-11-26 15:30:59')
        ->and($meridiemCarry->text)->toBe('1點pm到3點')
        ->and($meridiemCarry->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($meridiemCarry->end?->date()->toDateTimeString())->toBe('2012-08-11 03:00:00')
        ->and($cantoneseFuture->text)->toBe('大後日下晝5點')
        ->and($cantoneseFuture->start->date()->toDateTimeString())->toBe('2012-08-13 17:00:00')
        ->and($time->text)->toBe('聽晚10點到聽晚11點')
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-11 22:00:00')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-11 23:00:00');
});

it('parses traditional chinese random date time expressions', function () {
    $chinese = Chrono::zhHant();

    expect($chinese->parseText('2014年, 3月5日晏晝 6 點至 7 點', '2012-08-10')[0]->text)
        ->toBe('2014年, 3月5日晏晝 6 點至 7 點')
        ->and($chinese->parseText('下星期六凌晨1點30分廿九秒', '2012-08-10')[0]->text)
        ->toBe('下星期六凌晨1點30分廿九秒')
        ->and($chinese->parseText('尋日朝早六點正', '2012-08-10')[0]->text)
        ->toBe('尋日朝早六點正')
        ->and($chinese->parseText('六月四日3:00am', '2012-08-10')[0]->text)
        ->toBe('六月四日3:00am')
        ->and($chinese->parseText('上個禮拜五16時', '2012-08-10')[0]->text)
        ->toBe('上個禮拜五16時')
        ->and($chinese->parseText('3月17日 20點15', '2012-08-10')[0]->text)
        ->toBe('3月17日 20點15')
        ->and($chinese->parseText('10點', '2012-08-10')[0]->text)
        ->toBe('10點')
        ->and($chinese->parseText('中午12點', '2012-08-10')[0]->start->get('hour'))
        ->toBe(12);
});
