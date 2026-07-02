<?php

use DirectoryTree\Chrono\Chrono;

it('parses finnish casual dates and times', function () {
    $finnish = Chrono::fi();
    $now = $finnish->parse('Määräaika on nyt', '2012-08-10 08:09:10.011')[0];
    $today = $finnish->parse('tänään', '2012-08-10')[0];
    $tomorrow = $finnish->parse('huomenna', '2012-08-10')[0];
    $yesterday = $finnish->parse('eilen', '2012-08-10')[0];
    $dayAfterTomorrow = $finnish->parse('ylihuomenna', '2012-08-10')[0];
    $dayBeforeYesterday = $finnish->parse('toissapäivänä', '2012-08-10')[0];
    $todayMorning = $finnish->parse('tänään aamulla', '2012-08-10')[0];
    $todayLateMorning = $finnish->parse('tänään aamupäivällä', '2012-08-10')[0];
    $todayNoon = $finnish->parse('tänään päivällä', '2012-08-10')[0];
    $todayAfternoon = $finnish->parse('tänään iltapäivällä', '2012-08-10')[0];
    $todayEvening = $finnish->parse('tänään illalla', '2012-08-10')[0];
    $todayNight = $finnish->parse('tänään yöllä', '2012-08-10')[0];
    $todayMidnight = $finnish->parse('tänään keskiyöllä', '2012-08-10')[0];
    $morning = $finnish->parse('aamulla', '2012-08-10 14:00')[0];
    $casualTime = $finnish->parse('aamupäivällä', '2012-08-10 14:00')[0];
    $noon = $finnish->parse('päivällä', '2012-08-10 14:00')[0];
    $afternoon = $finnish->parse('iltapäivällä', '2012-08-10 14:00')[0];
    $evening = $finnish->parse('illalla', '2012-08-10 14:00')[0];
    $night = $finnish->parse('yöllä', '2012-08-10 14:00')[0];
    $midnight = $finnish->parse('keskiyöllä', '2012-08-10 14:00')[0];
    $lastNight = $finnish->parse('viime yönä', '2012-08-10 14:00')[0];

    expect($now->text)->toBe('nyt')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/FICasualDateParser')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($dayAfterTomorrow->start->get('year'))->toBe(2012)
        ->and($dayAfterTomorrow->start->get('month'))->toBe(8)
        ->and($dayAfterTomorrow->start->get('day'))->toBe(12)
        ->and($dayBeforeYesterday->start->get('year'))->toBe(2012)
        ->and($dayBeforeYesterday->start->get('month'))->toBe(8)
        ->and($dayBeforeYesterday->start->get('day'))->toBe(8)
        ->and($todayMorning->start->get('year'))->toBe(2012)
        ->and($todayMorning->start->get('month'))->toBe(8)
        ->and($todayMorning->start->get('day'))->toBe(10)
        ->and($todayMorning->start->get('hour'))->toBe(6)
        ->and($todayLateMorning->start->get('hour'))->toBe(9)
        ->and($todayNoon->start->get('hour'))->toBe(12)
        ->and($todayAfternoon->start->get('hour'))->toBe(15)
        ->and($todayEvening->start->get('hour'))->toBe(18)
        ->and($todayNight->start->get('hour'))->toBe(22)
        ->and($todayMidnight->start->get('hour'))->toBe(0)
        ->and($todayMidnight->start->get('day'))->toBe(10)
        ->and($morning->text)->toBe('aamulla')
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($morning->start->get('minute'))->toBe(0)
        ->and($casualTime->text)->toBe('aamupäivällä')
        ->and($casualTime->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($casualTime->start->tags())->toContain('parser/FICasualTimeParser')
        ->and($casualTime->start->get('minute'))->toBe(0)
        ->and($noon->text)->toBe('päivällä')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($noon->start->get('minute'))->toBe(0)
        ->and($afternoon->text)->toBe('iltapäivällä')
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($afternoon->start->get('minute'))->toBe(0)
        ->and($evening->text)->toBe('illalla')
        ->and($evening->start->get('hour'))->toBe(18)
        ->and($evening->start->get('minute'))->toBe(0)
        ->and($night->text)->toBe('yöllä')
        ->and($night->start->get('hour'))->toBe(22)
        ->and($night->start->get('minute'))->toBe(0)
        ->and($midnight->text)->toBe('keskiyöllä')
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($midnight->start->get('minute'))->toBe(0)
        ->and($midnight->start->get('day'))->toBe(11)
        ->and($lastNight->text)->toBe('viime yönä')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($lastNight->start->get('year'))->toBe(2012)
        ->and($lastNight->start->get('month'))->toBe(8)
        ->and($lastNight->start->get('day'))->toBe(9)
        ->and($lastNight->start->get('hour'))->toBe(0)
        ->and($finnish->date('Määräaika on tänään', '2012-08-10 14:12')?->toDateTimeString())
        ->toBe('2012-08-10 14:12:00')
        ->and($finnish->date('Määräaika on huomenna', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($finnish->date('Määräaika on ylihuomenna', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-12 17:10:00')
        ->and($finnish->date('Määräaika oli eilen', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 17:10:00')
        ->and($finnish->date('Määräaika oli toissapäivänä', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-08 17:10:00')
        ->and($finnish->date('Määräaika oli viime yönä', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and($finnish->date('Määräaika on huomenna illalla', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 18:00:00')
        ->and($finnish->date('Määräaika on tänä aamuna', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($finnish->date('Määräaika on keskiyöllä', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00');
});
