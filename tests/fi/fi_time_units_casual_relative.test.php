<?php

use Chrono\Chrono;

it('parses finnish time unit relative expressions', function () {
    $finnish = Chrono::fi();
    $fiveDaysAgo = $finnish->parseText('5 päivää sitten tehtiin jotain', '2012-08-10')[0];
    $tenDaysAgo = $finnish->parseText('10 päivää sitten tehtiin jotain', '2012-08-10 13:30')[0];
    $minutesAgo = $finnish->parseText('15 minuuttia sitten', '2012-08-10 12:14')[0];
    $prefixedHoursAgo = $finnish->parseText('   12 tuntia sitten', '2012-08-10 12:14')[0];
    $hoursAgo = $finnish->parseText('12 tuntia sitten tapahtui jotain', '2012-08-10 12:14')[0];
    $monthsAgo = $finnish->parseText('5 kuukautta sitten tehtiin jotain', '2012-10-10')[0];
    $yearsAgo = $finnish->parseText('5 vuotta sitten tehtiin jotain', '2012-08-10 22:22')[0];
    $weekAgo = $finnish->parseText('yksi viikkoa sitten tehtiin jotain', '2012-08-03 08:34')[0];
    $withinDays = $finnish->parseText('pitää tehdä jotain 5 päivää sisällä', '2012-08-10')[0];
    $withinMinutes = $finnish->parseText('5 minuuttia sisällä', '2012-08-10 12:14')[0];
    $withinHours = $finnish->parseText('1 tuntia sisällä', '2012-08-10 12:14')[0];
    $withinWeeks = $finnish->parseText('2 viikkoa sisällä', '2012-08-10 12:14')[0];
    $duringDays = $finnish->parseText('5 päivää kuluessa', '2012-08-10')[0];
    $duringYears = $finnish->parseText('yksi vuotta kuluessa', '2012-08-10 12:14')[0];
    $fromNowMinutes = $finnish->parseText('5 minuuttia päästä', '2012-08-10 12:14')[0];
    $fromNowDays = $finnish->parseText('3 päivää päästä', '2012-08-10 12:14')[0];
    $fromNowWeeks = $finnish->parseText('2 viikkoa päästä', '2016-10-01')[0];
    $nextTwoWeeks = $finnish->parseText('seuraavat 2 viikkoa', '2016-10-01 12:00')[0];
    $nextTwoDays = $finnish->parseText('seuraavat 2 päivää', '2016-10-01 12:00')[0];
    $nextTwoYears = $finnish->parseText('seuraavat kaksi vuotta', '2016-10-01 12:00')[0];
    $compoundFuture = $finnish->parseText('seuraavat 2 viikkoa 3 päivää', '2016-10-01 12:00')[0];
    $nextOneYear = $finnish->parseText('seuraava yksi vuotta', '2016-10-01 12:00')[0];
    $previousTwoWeeks = $finnish->parseText('edelliset 2 viikkoa', '2016-10-01 12:00')[0];
    $lastTwoDays = $finnish->parseText('viimeiset 2 päivää', '2016-10-01 12:00')[0];
    $pastTwoWeeks = $finnish->parseText('kuluneet kaksi viikkoa', '2016-10-01 12:00')[0];
    $compoundPlus = $finnish->parseText('+2 kuukautta 5 päivää', '2016-10-01 12:00')[0];
    $plusMinutes = $finnish->parseText('+15 minuuttia', '2012-07-10 12:14')[0];
    $plusCompactMinutes = $finnish->parseText('+15min', '2012-07-10 12:14')[0];
    $plusCompound = $finnish->parseText('+1 päivä 2 tuntia', '2012-07-10 12:14')[0];
    $minusYears = $finnish->parseText('-3vuotta', '2015-07-10 12:14')[0];

    expect($finnish->parseText('Nähdään 2 päivän päästä', '2012-08-10 09:30')[0]->text)
        ->toBe('2 päivän päästä')
        ->and($finnish->parseDateText('Nähdään 2 päivän päästä', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00')
        ->and($finnish->parseDateText('Nähtiin 3 päivää sitten', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-07 09:30:00')
        ->and($finnish->parseDateText('Nähdään seuraavat 2 viikkoa', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-24 09:30:00')
        ->and($finnish->parseDateText('Nähdään seuraava yksi vuotta', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2017-10-01 12:00:00')
        ->and($finnish->parseDateText('Nähtiin edelliset 2 viikkoa', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-07-27 09:30:00')
        ->and($finnish->parseDateText('Kuluneet kaksi viikkoa', '2016-10-01 12:00')?->toDateTimeString())
        ->toBe('2016-09-17 12:00:00')
        ->and($finnish->parseDateText('+15min', '2012-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and($finnish->parseDateText('-3vuotta', '2015-07-10 12:14')?->toDateTimeString())
        ->toBe('2012-07-10 12:14:00')
        ->and($finnish->parseDateText('Nähdään kahden tunnin päästä', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 11:30:00')
        ->and($fiveDaysAgo->index)->toBe(0)
        ->and($fiveDaysAgo->text)->toBe('5 päivää sitten')
        ->and($fiveDaysAgo->start->get('year'))->toBe(2012)
        ->and($fiveDaysAgo->start->get('month'))->toBe(8)
        ->and($fiveDaysAgo->start->get('day'))->toBe(5)
        ->and($tenDaysAgo->index)->toBe(0)
        ->and($tenDaysAgo->text)->toBe('10 päivää sitten')
        ->and($tenDaysAgo->start->get('year'))->toBe(2012)
        ->and($tenDaysAgo->start->get('month'))->toBe(7)
        ->and($tenDaysAgo->start->get('day'))->toBe(31)
        ->and($minutesAgo->index)->toBe(0)
        ->and($minutesAgo->text)->toBe('15 minuuttia sitten')
        ->and($minutesAgo->start->get('hour'))->toBe(11)
        ->and($minutesAgo->start->get('minute'))->toBe(59)
        ->and($prefixedHoursAgo->index)->toBe(3)
        ->and($prefixedHoursAgo->text)->toBe('12 tuntia sitten')
        ->and($prefixedHoursAgo->start->get('hour'))->toBe(0)
        ->and($prefixedHoursAgo->start->get('minute'))->toBe(14)
        ->and($hoursAgo->index)->toBe(0)
        ->and($hoursAgo->text)->toBe('12 tuntia sitten')
        ->and($hoursAgo->start->get('hour'))->toBe(0)
        ->and($hoursAgo->start->get('minute'))->toBe(14)
        ->and($monthsAgo->index)->toBe(0)
        ->and($monthsAgo->text)->toBe('5 kuukautta sitten')
        ->and($monthsAgo->start->get('year'))->toBe(2012)
        ->and($monthsAgo->start->get('month'))->toBe(5)
        ->and($monthsAgo->start->get('day'))->toBe(10)
        ->and($yearsAgo->index)->toBe(0)
        ->and($yearsAgo->text)->toBe('5 vuotta sitten')
        ->and($yearsAgo->start->get('year'))->toBe(2007)
        ->and($yearsAgo->start->get('month'))->toBe(8)
        ->and($yearsAgo->start->get('day'))->toBe(10)
        ->and($weekAgo->index)->toBe(0)
        ->and($weekAgo->text)->toBe('yksi viikkoa sitten')
        ->and($weekAgo->start->get('year'))->toBe(2012)
        ->and($weekAgo->start->get('month'))->toBe(7)
        ->and($weekAgo->start->get('day'))->toBe(27)
        ->and($withinDays->text)->toBe('5 päivää sisällä')
        ->and($withinDays->start->get('year'))->toBe(2012)
        ->and($withinDays->start->get('month'))->toBe(8)
        ->and($withinDays->start->get('day'))->toBe(15)
        ->and($withinMinutes->index)->toBe(0)
        ->and($withinMinutes->text)->toBe('5 minuuttia sisällä')
        ->and($withinMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($withinHours->index)->toBe(0)
        ->and($withinHours->text)->toBe('1 tuntia sisällä')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($withinWeeks->text)->toBe('2 viikkoa sisällä')
        ->and($withinWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($duringDays->text)->toBe('5 päivää kuluessa')
        ->and($duringDays->start->get('year'))->toBe(2012)
        ->and($duringDays->start->get('month'))->toBe(8)
        ->and($duringDays->start->get('day'))->toBe(15)
        ->and($duringYears->text)->toBe('yksi vuotta kuluessa')
        ->and($duringYears->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($fromNowMinutes->text)->toBe('5 minuuttia päästä')
        ->and($fromNowMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($fromNowDays->text)->toBe('3 päivää päästä')
        ->and($fromNowDays->start->get('year'))->toBe(2012)
        ->and($fromNowDays->start->get('month'))->toBe(8)
        ->and($fromNowDays->start->get('day'))->toBe(13)
        ->and($fromNowWeeks->text)->toBe('2 viikkoa päästä')
        ->and($fromNowWeeks->start->get('year'))->toBe(2016)
        ->and($fromNowWeeks->start->get('month'))->toBe(10)
        ->and($fromNowWeeks->start->get('day'))->toBe(15)
        ->and($nextTwoWeeks->text)->toBe('seuraavat 2 viikkoa')
        ->and($nextTwoWeeks->start->get('year'))->toBe(2016)
        ->and($nextTwoWeeks->start->get('month'))->toBe(10)
        ->and($nextTwoWeeks->start->get('day'))->toBe(15)
        ->and($nextTwoDays->text)->toBe('seuraavat 2 päivää')
        ->and($nextTwoDays->start->get('year'))->toBe(2016)
        ->and($nextTwoDays->start->get('month'))->toBe(10)
        ->and($nextTwoDays->start->get('day'))->toBe(3)
        ->and($nextTwoDays->start->get('hour'))->toBe(12)
        ->and($nextTwoYears->text)->toBe('seuraavat kaksi vuotta')
        ->and($nextTwoYears->start->get('year'))->toBe(2018)
        ->and($nextTwoYears->start->get('month'))->toBe(10)
        ->and($nextTwoYears->start->get('day'))->toBe(1)
        ->and($nextTwoYears->start->get('hour'))->toBe(12)
        ->and($compoundFuture->text)->toBe('seuraavat 2 viikkoa 3 päivää')
        ->and($compoundFuture->start->get('year'))->toBe(2016)
        ->and($compoundFuture->start->get('month'))->toBe(10)
        ->and($compoundFuture->start->get('day'))->toBe(18)
        ->and($compoundFuture->start->get('hour'))->toBe(12)
        ->and($nextOneYear->text)->toBe('seuraava yksi vuotta')
        ->and($nextOneYear->start->get('year'))->toBe(2017)
        ->and($nextOneYear->start->get('month'))->toBe(10)
        ->and($nextOneYear->start->get('day'))->toBe(1)
        ->and($previousTwoWeeks->text)->toBe('edelliset 2 viikkoa')
        ->and($previousTwoWeeks->start->get('year'))->toBe(2016)
        ->and($previousTwoWeeks->start->get('month'))->toBe(9)
        ->and($previousTwoWeeks->start->get('day'))->toBe(17)
        ->and($lastTwoDays->text)->toBe('viimeiset 2 päivää')
        ->and($lastTwoDays->start->get('year'))->toBe(2016)
        ->and($lastTwoDays->start->get('month'))->toBe(9)
        ->and($lastTwoDays->start->get('day'))->toBe(29)
        ->and($pastTwoWeeks->text)->toBe('kuluneet kaksi viikkoa')
        ->and($pastTwoWeeks->start->get('year'))->toBe(2016)
        ->and($pastTwoWeeks->start->get('month'))->toBe(9)
        ->and($pastTwoWeeks->start->get('day'))->toBe(17)
        ->and($compoundPlus->text)->toBe('+2 kuukautta 5 päivää')
        ->and($compoundPlus->start->get('year'))->toBe(2016)
        ->and($compoundPlus->start->get('month'))->toBe(12)
        ->and($compoundPlus->start->get('day'))->toBe(6)
        ->and($plusMinutes->text)->toBe('+15 minuuttia')
        ->and($plusMinutes->start->get('hour'))->toBe(12)
        ->and($plusMinutes->start->get('minute'))->toBe(29)
        ->and($plusCompactMinutes->text)->toBe('+15min')
        ->and($plusCompactMinutes->start->get('hour'))->toBe(12)
        ->and($plusCompactMinutes->start->get('minute'))->toBe(29)
        ->and($plusCompound->text)->toBe('+1 päivä 2 tuntia')
        ->and($plusCompound->start->get('day'))->toBe(11)
        ->and($plusCompound->start->get('hour'))->toBe(14)
        ->and($plusCompound->start->get('minute'))->toBe(14)
        ->and($minusYears->text)->toBe('-3vuotta')
        ->and($minusYears->start->get('year'))->toBe(2012)
        ->and($minusYears->start->get('month'))->toBe(7)
        ->and($minusYears->start->get('day'))->toBe(10)
        ->and($minusYears->start->get('hour'))->toBe(12)
        ->and($minusYears->start->get('minute'))->toBe(14);
});
