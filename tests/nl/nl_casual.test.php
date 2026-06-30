<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses dutch casual dates and times', function () {
    $dutch = Chrono::nl();
    $now = $dutch->parseText('De deadline is nu', '2012-08-10 08:09:10.011')[0];
    $today = $dutch->parseText('De deadline is vandaag', '2012-08-10 14:12')[0];
    $tomorrow = $dutch->parseText('De deadline is morgen', '2012-08-10 17:10')[0];
    $yesterday = $dutch->parseText('De deadline was gisteren', '2012-08-10 12:00')[0];
    $thisMorning = $dutch->parseText('De Deadline was deze ochtend', '2012-08-10 12:00')[0];
    $thisAfternoon = $dutch->parseText('De Deadline was deze namiddag ', '2012-08-10 12:00')[0];
    $thisEvening = $dutch->parseText('De Deadline was deze avond ', '2012-08-10 12:00')[0];
    $tonight = $dutch->parseText('De deadline is vanavond', '2012-08-10 12:00')[0];
    $midnight = $dutch->parseText('The Deadline is om middernacht ', '2012-08-10 01:00')[0];
    $todayAtFive = $dutch->parseText('De deadline is vandaag om 17:00', '2012-08-10 12:00')[0];
    $yesterdayMorning = $dutch->parseText('gisterenochtend', '2012-08-10 14:00')[0];
    $yesterdayNoon = $dutch->parseText('gisterenmiddag', '2012-08-10 14:00')[0];
    $yesterdayEvening = $dutch->parseText('gisterenavond', '2012-08-10 14:00')[0];
    $thisMorningCompact = $dutch->parseText('vanochtend', '2012-08-10 14:00')[0];
    $thisNoonCompact = $dutch->parseText('vanmiddag', '2012-08-10 14:00')[0];
    $tonightCompact = $dutch->parseText('vanavond', '2012-08-10 14:00')[0];
    $tomorrowMorning = $dutch->parseText('morgenochtend', '2012-08-10 14:00')[0];
    $tomorrowNoon = $dutch->parseText('morgenmiddag', '2012-08-10 14:00')[0];
    $tomorrowEvening = $dutch->parseText('morgenavond', '2012-08-10 14:00')[0];
    $casualRange = $dutch->parseText('Het evenement is vandaag - volgende vrijdag', '2012-08-04 12:00')[0];
    $casualRangeNextWeek = $dutch->parseText('Het evenement is vandaag - volgende vrijdag', '2012-08-10 12:00')[0];
    $casualTimeRange = $dutch->parseText('jaarlijks verlof vanaf vandaag tot morgennamiddag', '2012-08-04 12:00')[0];
    $casualStartTimeRange = $dutch->parseText('jaarlijks verlof vanaf deze ochtend tot morgen', '2012-08-04 12:00')[0];
    $tonightWithTime = $dutch->parseText('vanavond 22:00', '2012-01-01 12:00')[0];
    $tonightWithPrefixedTime = $dutch->parseText('vanavond om 21:00', '2012-01-01 12:00')[0];
    $tomorrowBeforeTime = $dutch->parseText('morgen voor 16:00', '2012-01-01 12:00')[0];
    $tomorrowAfterTime = $dutch->parseText('morgen na 16:00', '2012-01-01 12:00')[0];
    $casualTimeWithExplicitTime = $dutch->parseText('deze namiddag om 15:00', '2016-10-01 08:00')[0];

    expect($now->text)->toBe('nu')
        ->and($now->index)->toBe(15)
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->get('timezoneOffset'))->toBe($now->start->date()->offsetMinutes)
        ->and($now->start->tags())->toContain('parser/NLCasualDateParser')
        ->and($today->index)->toBe(15)
        ->and($today->text)->toBe('vandaag')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->index)->toBe(15)
        ->and($tomorrow->text)->toBe('morgen')
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($yesterday->index)->toBe(16)
        ->and($yesterday->text)->toBe('gisteren')
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($thisMorning->index)->toBe(16)
        ->and($thisMorning->text)->toBe('deze ochtend')
        ->and($thisMorning->start->get('year'))->toBe(2012)
        ->and($thisMorning->start->get('month'))->toBe(8)
        ->and($thisMorning->start->get('day'))->toBe(10)
        ->and($thisMorning->start->get('hour'))->toBe(6)
        ->and($thisAfternoon->index)->toBe(16)
        ->and($thisAfternoon->text)->toBe('deze namiddag')
        ->and($thisAfternoon->start->get('hour'))->toBe(15)
        ->and($thisEvening->index)->toBe(16)
        ->and($thisEvening->text)->toBe('deze avond')
        ->and($thisEvening->start->get('hour'))->toBe(20)
        ->and($tonight->text)->toBe('vanavond')
        ->and($tonight->start->get('year'))->toBe(2012)
        ->and($tonight->start->get('month'))->toBe(8)
        ->and($tonight->start->get('day'))->toBe(10)
        ->and($tonight->start->get('hour'))->toBe(20)
        ->and($midnight->text)->toBe('middernacht')
        ->and($midnight->start->get('year'))->toBe(2012)
        ->and($midnight->start->get('month'))->toBe(8)
        ->and($midnight->start->get('day'))->toBe(11)
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($todayAtFive->index)->toBe(15)
        ->and($todayAtFive->text)->toBe('vandaag om 17:00')
        ->and($todayAtFive->start->get('year'))->toBe(2012)
        ->and($todayAtFive->start->get('month'))->toBe(8)
        ->and($todayAtFive->start->get('day'))->toBe(10)
        ->and($todayAtFive->start->get('hour'))->toBe(17)
        ->and($yesterdayMorning->start->get('year'))->toBe(2012)
        ->and($yesterdayMorning->start->get('month'))->toBe(8)
        ->and($yesterdayMorning->start->get('day'))->toBe(9)
        ->and($yesterdayMorning->start->get('hour'))->toBe(6)
        ->and($yesterdayNoon->start->get('day'))->toBe(9)
        ->and($yesterdayNoon->start->get('hour'))->toBe(12)
        ->and($yesterdayEvening->start->get('day'))->toBe(9)
        ->and($yesterdayEvening->start->get('hour'))->toBe(20)
        ->and($thisMorningCompact->start->get('day'))->toBe(10)
        ->and($thisMorningCompact->start->get('hour'))->toBe(6)
        ->and($thisNoonCompact->start->get('day'))->toBe(10)
        ->and($thisNoonCompact->start->get('hour'))->toBe(12)
        ->and($tonightCompact->start->get('day'))->toBe(10)
        ->and($tonightCompact->start->get('hour'))->toBe(20)
        ->and($tomorrowMorning->start->get('day'))->toBe(11)
        ->and($tomorrowMorning->start->get('hour'))->toBe(6)
        ->and($tomorrowNoon->start->get('day'))->toBe(11)
        ->and($tomorrowNoon->start->get('hour'))->toBe(12)
        ->and($tomorrowEvening->start->get('day'))->toBe(11)
        ->and($tomorrowEvening->start->get('hour'))->toBe(20)
        ->and($casualRange->text)->toBe('vandaag - volgende vrijdag')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($casualRangeNextWeek->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($casualRangeNextWeek->end?->date()->toDateTimeString())->toBe('2012-08-17 12:00:00')
        ->and($casualTimeRange->text)->toBe('vandaag tot morgennamiddag')
        ->and($casualTimeRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualTimeRange->start->isCertain('hour'))->toBeFalse()
        ->and($casualTimeRange->end?->date()->toDateTimeString())->toBe('2012-08-05 15:00:00')
        ->and($casualTimeRange->end?->isCertain('hour'))->toBeFalse()
        ->and($casualStartTimeRange->text)->toBe('deze ochtend tot morgen')
        ->and($casualStartTimeRange->start->date()->toDateTimeString())->toBe('2012-08-04 06:00:00')
        ->and($casualStartTimeRange->start->isCertain('hour'))->toBeFalse()
        ->and($casualStartTimeRange->end?->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($casualStartTimeRange->end?->isCertain('hour'))->toBeFalse()
        ->and($tonightWithTime->text)->toBe('vanavond 22:00')
        ->and($tonightWithTime->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($tonightWithTime->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonightWithPrefixedTime->text)->toBe('vanavond om 21:00')
        ->and($tonightWithPrefixedTime->start->date()->toDateTimeString())->toBe('2012-01-01 21:00:00')
        ->and($tomorrowBeforeTime->text)->toBe('morgen voor 16:00')
        ->and($tomorrowBeforeTime->start->date()->toDateTimeString())->toBe('2012-01-02 16:00:00')
        ->and($tomorrowAfterTime->text)->toBe('morgen na 16:00')
        ->and($tomorrowAfterTime->start->date()->toDateTimeString())->toBe('2012-01-02 16:00:00')
        ->and($casualTimeWithExplicitTime->text)->toBe('deze namiddag om 15:00')
        ->and($casualTimeWithExplicitTime->start->date()->toDateTimeString())->toBe('2016-10-01 15:00:00')
        ->and($dutch->parseText('notoday', '2012-08-10'))->toBe([])
        ->and($dutch->parseText('tdtmr', '2012-08-10'))->toBe([])
        ->and($dutch->parseText('xyesterday', '2012-08-10'))->toBe([])
        ->and($dutch->parseText('nowhere', '2012-08-10'))->toBe([])
        ->and($dutch->parseText('noway', '2012-08-10'))->toBe([])
        ->and($dutch->parseText('knowledge', '2012-08-10'))->toBe([])
        ->and($dutch->parseDateText('Deadline is vandaag', '2012-08-10 14:12')?->toDateTimeString())
        ->toBe('2012-08-10 14:12:00')
        ->and($dutch->parseDateText('Deadline is morgen', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 17:10:00')
        ->and($dutch->parseDateText('Deadline was gisteren', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 17:10:00')
        ->and($dutch->parseDateText('Afspraak deze ochtend', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($dutch->parseText('Afspraak deze ochtend', '2012-08-10 17:10')[0]->start->tags())->toContain('parser/NLCasualTimeParser')
        ->and($dutch->parseDateText('Afspraak avond', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($dutch->parseDateText('Afspraak middernacht', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($dutch->parseDateText('Afspraak morgenochtend', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-11 06:00:00')
        ->and($dutch->parseText('Afspraak morgenochtend', '2012-08-10 17:10')[0]->start->tags())->toContain('parser/NLCasualDateTimeParser')
        ->and($dutch->parseText('Afspraak morgenochtend', '2012-08-10 17:10')[0]->start->isCertain('day'))->toBeTrue()
        ->and($dutch->parseDateText('Afspraak vanavond', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-10 20:00:00')
        ->and($dutch->parseDateText('Afspraak gisterenmiddag', '2012-08-10 17:10')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00');
});
