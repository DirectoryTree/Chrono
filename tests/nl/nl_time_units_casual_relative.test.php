<?php

use Chrono\Chrono;

it('parses dutch time unit relative expressions', function () {
    $dutch = Chrono::nl();
    $strictDutch = Chrono::strictDutch();
    $withinDays = $dutch->parseText('we have to make something binnen 5 dagen.', '2012-08-10')[0];
    $withinMinutes = $dutch->parseText('binnen 2 minuten', '2016-10-01 14:52')[0];
    $withinHours = $dutch->parseText('binnen 2 uur', '2016-10-01 14:52')[0];
    $withinMonths = $dutch->parseText('binnen de 12 maand', '2016-10-01 14:52')[0];
    $withinThreeDays = $dutch->parseText('binnen de 3 dagen', '2016-10-01 14:52')[0];
    $withinMinutesWithPrefix = $dutch->parseText('Binnen de 5 minuten moet een auto zich verzetten', '2012-08-10 12:14')[0];
    $withinSeconds = $dutch->parseText('Binnen 5 seconden moet een auto zich verzetten', '2012-08-10 12:14')[0];
    $withinMonth = $dutch->parseText('Binnen een maand', '2012-08-10 12:14')[0];
    $withinYear = $dutch->parseText('Binnen een jaar', '2012-08-10 12:14')[0];
    $halfHourAgo = $dutch->parseText('   half uur geleden', '2012-08-10 12:14')[0];
    $threeSecondsAgo = $dutch->parseText('drie seconden geleden deed ik iets', '2012-08-10 12:14')[0];
    $nestedAgo = $dutch->parseText('15 uur 29 minuten geleden', '2012-08-10 22:30')[0];
    $nestedAgoWithDay = $dutch->parseText('1 dag 21 uur geleden ', '2012-08-10 22:30')[0];
    $nestedAgoWithSeconds = $dutch->parseText('3 min 49 sec geleden ', '2012-08-10 22:30')[0];
    $decimalHour = $dutch->parseText('over 1,5 uur', '2012-08-10 12:40')[0];
    $fromNow = $dutch->parseText('5 dagen vanaf nu we hebben iets gedaan', '2012-08-10')[0];
    $minutesFromNow = $dutch->parseText('15 minuten vanaf nu', '2012-08-10 12:14')[0];
    $minutesOut = $dutch->parseText('15 minuten uit', '2012-08-10 12:14')[0];
    $secondsFromNow = $dutch->parseText('Over 12 seconden heb ik iets gedaan', '2012-08-10 12:14')[0];
    $spelledSecondsFromNow = $dutch->parseText('over drie seconden heb ik iets gedaan', '2012-08-10 12:14')[0];
    $minuteOut = $dutch->parseText('een minuutje uit', '2012-08-10 12:14')[0];
    $minusCompact = $dutch->parseText('-2u5min', '2016-10-01 12:00')[0];

    expect($dutch->parseText('Afspraak in 2 dagen', '2012-08-10 09:30')[0]->text)
        ->toBe('in 2 dagen')
        ->and($dutch->parseDateText('Afspraak in 2 dagen', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-12 09:30:00')
        ->and($dutch->parseText('wait voor 5 minuten', '2012-08-10 12:14')[0]->text)
        ->toBe('voor 5 minuten')
        ->and($dutch->parseDateText('wait voor 5 minuten', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($dutch->parseDateText('Afspraak binnen de 3 uur', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 12:30:00')
        ->and($dutch->parseDateText('Afspraak twee dagen geleden', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-08 09:30:00')
        ->and($dutch->parseDateText('Afspraak 4 uur later', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-10 13:30:00')
        ->and($dutch->parseDateText('Afspraak over 2 weken', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-24 09:30:00')
        ->and($dutch->parseDateText('Afspraak afgelopen 1 week', '2012-08-10 09:30')?->toDateTimeString())
        ->toBe('2012-08-03 09:30:00')
        ->and($strictDutch->parseDateText('15 minuten vanaf nu', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:29:00')
        ->and($strictDutch->parseDateText('25 minuten later', '2012-08-10 12:40')?->toDateTimeString())
        ->toBe('2012-08-10 13:05:00')
        ->and(Chrono::parse('15 uur 29 min', '2012-08-10 12:14'))
        ->toBe([])
        ->and(Chrono::parse('een paar uur', '2012-08-10 12:14'))
        ->toBe([])
        ->and(Chrono::parse('5 dagen', '2012-08-10 12:14'))
        ->toBe([])
        ->and($withinDays->index)->toBe(26)
        ->and($withinDays->text)->toBe('binnen 5 dagen')
        ->and($withinDays->start->get('year'))->toBe(2012)
        ->and($withinDays->start->get('month'))->toBe(8)
        ->and($withinDays->start->get('day'))->toBe(15)
        ->and($withinMinutes->start->date()->toDateTimeString())->toBe('2016-10-01 14:54:00')
        ->and($withinMinutes->start->isCertain('year'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('month'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('day'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('hour'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('minute'))->toBeTrue()
        ->and($withinMinutes->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2016-10-01 16:52:00')
        ->and($withinHours->start->isCertain('year'))->toBeTrue()
        ->and($withinHours->start->isCertain('month'))->toBeTrue()
        ->and($withinHours->start->isCertain('day'))->toBeTrue()
        ->and($withinHours->start->isCertain('hour'))->toBeTrue()
        ->and($withinHours->start->isCertain('minute'))->toBeTrue()
        ->and($withinHours->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($withinMonths->start->date()->toDateTimeString())->toBe('2017-10-01 14:52:00')
        ->and($withinMonths->start->isCertain('year'))->toBeTrue()
        ->and($withinMonths->start->isCertain('month'))->toBeTrue()
        ->and($withinMonths->start->isCertain('day'))->toBeFalse()
        ->and($withinMonths->start->isCertain('hour'))->toBeFalse()
        ->and($withinMonths->start->isCertain('minute'))->toBeFalse()
        ->and($withinThreeDays->start->date()->toDateTimeString())->toBe('2016-10-04 14:52:00')
        ->and($withinThreeDays->start->isCertain('year'))->toBeTrue()
        ->and($withinThreeDays->start->isCertain('month'))->toBeTrue()
        ->and($withinThreeDays->start->isCertain('day'))->toBeTrue()
        ->and($withinThreeDays->start->isCertain('hour'))->toBeFalse()
        ->and($withinThreeDays->start->isCertain('minute'))->toBeFalse()
        ->and($withinMinutesWithPrefix->text)->toBe('Binnen de 5 minuten')
        ->and($withinMinutesWithPrefix->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($withinSeconds->text)->toBe('Binnen 5 seconden')
        ->and($withinSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($withinSeconds->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($withinMonth->start->date()->toDateTimeString())->toBe('2012-09-10 12:14:00')
        ->and($withinYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($halfHourAgo->index)->toBe(3)
        ->and($halfHourAgo->text)->toBe('half uur geleden')
        ->and($halfHourAgo->start->get('hour'))->toBe(11)
        ->and($halfHourAgo->start->get('minute'))->toBe(44)
        ->and($halfHourAgo->start->date()->toDateTimeString())->toBe('2012-08-10 11:44:00')
        ->and($threeSecondsAgo->text)->toBe('drie seconden geleden')
        ->and($threeSecondsAgo->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:57')
        ->and($nestedAgo->text)->toBe('15 uur 29 minuten geleden')
        ->and($nestedAgo->start->date()->toDateTimeString())->toBe('2012-08-10 07:01:00')
        ->and($nestedAgoWithDay->text)->toBe('1 dag 21 uur geleden')
        ->and($nestedAgoWithDay->start->date()->toDateTimeString())->toBe('2012-08-09 01:30:00')
        ->and($nestedAgoWithSeconds->text)->toBe('3 min 49 sec geleden')
        ->and($nestedAgoWithSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 22:26:11')
        ->and($decimalHour->text)->toBe('over 1,5 uur')
        ->and($decimalHour->start->get('hour'))->toBe(14)
        ->and($decimalHour->start->get('minute'))->toBe(10)
        ->and($decimalHour->start->date()->toDateTimeString())->toBe('2012-08-10 14:10:00')
        ->and($fromNow->text)->toBe('5 dagen vanaf nu')
        ->and($fromNow->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00')
        ->and($minutesFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($minutesOut->text)->toBe('15 minuten uit')
        ->and($minutesOut->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($secondsFromNow->text)->toBe('Over 12 seconden')
        ->and($secondsFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:12')
        ->and($spelledSecondsFromNow->text)->toBe('over drie seconden')
        ->and($spelledSecondsFromNow->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:03')
        ->and($minuteOut->text)->toBe('een minuutje uit')
        ->and($minuteOut->start->date()->toDateTimeString())->toBe('2012-08-10 12:15:00')
        ->and($minusCompact->text)->toBe('-2u5min')
        ->and($minusCompact->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00');
});

it('parses dutch upstream time unit relative edge cases', function () {
    $dutch = Chrono::nl();

    $laterDays = $dutch->parseText('10 dagen vanaf nu we hebben iets gedaan', '2012-08-10 12:14')[0];
    $laterMinutes = $dutch->parseText('15 minuten eerder', '2012-08-10 12:14')[0];
    $laterHours = $dutch->parseText('   12 uur vanaf nu', '2012-08-10 12:14')[0];
    $prefixedHours = $dutch->parseText('Over 12 uur heb ik iets gedaan', '2012-08-10 12:14')[0];
    $spelledWithin = $dutch->parseText('we have to make something binnen vijf dagen.', '2012-08-10 12:14')[0];
    $withinTenDays = $dutch->parseText('we have to make something binnen de 10 dagen', '2012-08-10 12:14')[0];
    $withinOneHour = $dutch->parseText('binnen 1 uur', '2012-08-10 12:14')[0];
    $withinTwoWeeks = $dutch->parseText('Binnen de 2 weken', '2012-08-10 12:14')[0];
    $withinMinuteShort = $dutch->parseText('Binnen 5 min a car need to move', '2012-08-10 12:14')[0];
    $agoDays = $dutch->parseText('10 dagen geleden, hebben we wat gedaan', '2012-08-10 12:14')[0];
    $agoShortHour = $dutch->parseText('1u geleden', '2012-08-10 12:14')[0];
    $agoSeconds = $dutch->parseText('12 seconden geleden deed ik iets', '2012-08-10 12:14')[0];
    $agoMonths = $dutch->parseText('5 maanden geleden', '2012-08-10 12:14')[0];
    $agoYears = $dutch->parseText('5 jaar geleden', '2012-08-10 12:14')[0];
    $agoPair = $dutch->parseText('paar dagen geleden', '2012-08-10 12:14')[0];
    $upcomingWeeks = $dutch->parseText('komende 2 weken', '2012-08-10 12:14')[0];
    $upcomingCompound = $dutch->parseText('komende 2 weken 3 dagen', '2012-08-10 12:14')[0];
    $pastSpelledWeeks = $dutch->parseText('afgelopen twee weken', '2012-08-10 12:14')[0];
    $signedCompound = $dutch->parseText('+2 maanden 5 dagen', '2012-08-10 12:14')[0];
    $signedCompact = $dutch->parseText('+15min', '2012-08-10 12:14')[0];
    $signedNegative = $dutch->parseText('-3jr', '2012-08-10 12:14')[0];

    expect($laterDays->text)->toBe('10 dagen vanaf nu')
        ->and($laterDays->start->date()->toDateTimeString())->toBe('2012-08-20 12:14:00')
        ->and($laterMinutes->text)->toBe('15 minuten eerder')
        ->and($laterMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 11:59:00')
        ->and($laterHours->index)->toBe(3)
        ->and($laterHours->start->date()->toDateTimeString())->toBe('2012-08-11 00:14:00')
        ->and($prefixedHours->text)->toBe('Over 12 uur')
        ->and($prefixedHours->start->date()->toDateTimeString())->toBe('2012-08-11 00:14:00')
        ->and($spelledWithin->index)->toBe(26)
        ->and($spelledWithin->text)->toBe('binnen vijf dagen')
        ->and($spelledWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:14:00')
        ->and($withinTenDays->text)->toBe('binnen de 10 dagen')
        ->and($withinTenDays->start->date()->toDateTimeString())->toBe('2012-08-20 12:14:00')
        ->and($withinOneHour->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($withinTwoWeeks->text)->toBe('Binnen de 2 weken')
        ->and($withinTwoWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($withinMinuteShort->text)->toBe('Binnen 5 min')
        ->and($withinMinuteShort->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($agoDays->text)->toBe('10 dagen geleden')
        ->and($agoDays->start->date()->toDateTimeString())->toBe('2012-07-31 12:14:00')
        ->and($agoShortHour->text)->toBe('1u geleden')
        ->and($agoShortHour->start->date()->toDateTimeString())->toBe('2012-08-10 11:14:00')
        ->and($agoSeconds->text)->toBe('12 seconden geleden')
        ->and($agoSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 12:13:48')
        ->and($agoMonths->start->date()->toDateTimeString())->toBe('2012-03-10 12:14:00')
        ->and($agoYears->start->date()->toDateTimeString())->toBe('2007-08-10 12:14:00')
        ->and($agoPair->text)->toBe('paar dagen geleden')
        ->and($agoPair->start->date()->toDateTimeString())->toBe('2012-08-08 12:14:00')
        ->and($upcomingWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($upcomingCompound->start->date()->toDateTimeString())->toBe('2012-08-27 12:14:00')
        ->and($pastSpelledWeeks->text)->toBe('afgelopen twee weken')
        ->and($pastSpelledWeeks->start->date()->toDateTimeString())->toBe('2012-07-27 12:14:00')
        ->and($signedCompound->start->date()->toDateTimeString())->toBe('2012-10-15 12:14:00')
        ->and($signedCompact->text)->toBe('+15min')
        ->and($signedCompact->start->date()->toDateTimeString())->toBe('2012-08-10 12:29:00')
        ->and($signedNegative->text)->toBe('-3jr')
        ->and($signedNegative->start->date()->toDateTimeString())->toBe('2009-08-10 12:14:00');
});
