<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses portuguese casual date and time references', function () {
    $portuguese = Chrono::pt();
    $prefixedNow = $portuguese->parseText('O prazo é agora', '2012-08-10 08:09:10.011')[0];
    $prefixedToday = $portuguese->parseText('O prazo é hoje', '2012-08-10 12:00')[0];
    $prefixedTomorrow = $portuguese->parseText('O prazo é Amanhã', '2012-08-10 12:00')[0];
    $prefixedYesterday = $portuguese->parseText('O prazo foi ontem', '2012-08-10 12:00')[0];
    $lastNight = $portuguese->parseText('O prazo foi ontem à noite ', '2012-08-10 12:00')[0];
    $morning = $portuguese->parseText('O prazo foi esta manhã ', '2012-08-10 12:00')[0];
    $afternoon = $portuguese->parseText('O prazo foi esta tarde ', '2012-08-10 12:00')[0];
    $combined = $portuguese->parseText('O prazo é hoje às 5PM', '2012-08-10 12:00')[0];
    $tonightOnly = $portuguese->parseText('esta noite', '2012-01-01 12:00')[0];
    $tonightWithMeridiem = $portuguese->parseText('esta noite 8pm', '2012-01-01 12:00')[0];
    $tonight = $portuguese->parseText('esta noite às 8', '2012-01-01 12:00')[0];
    $weekdayThursday = $portuguese->parseText('quinta', '2012-08-10')[0];
    $weekdayFriday = $portuguese->parseText('sexta', '2012-08-10')[0];
    $noon = $portuguese->parseText('ao meio-dia', '2020-09-01 11:00')[0];
    $midnight = $portuguese->parseText('a meia-noite', '2020-09-01 11:00')[0];

    expect($prefixedNow->index)->toBe(10)
        ->and($prefixedNow->text)->toBe('agora')
        ->and($prefixedNow->start->get('year'))->toBe(2012)
        ->and($prefixedNow->start->get('month'))->toBe(8)
        ->and($prefixedNow->start->get('day'))->toBe(10)
        ->and($prefixedNow->start->get('hour'))->toBe(8)
        ->and($prefixedNow->start->get('minute'))->toBe(9)
        ->and($prefixedNow->start->get('second'))->toBe(10)
        ->and($prefixedNow->start->get('millisecond'))->toBe(11)
        ->and($prefixedNow->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($prefixedNow->start->tags())->toContain('parser/PTCasualDateParser')
        ->and($prefixedToday->index)->toBe(10)
        ->and($prefixedToday->text)->toBe('hoje')
        ->and($prefixedToday->start->get('year'))->toBe(2012)
        ->and($prefixedToday->start->get('month'))->toBe(8)
        ->and($prefixedToday->start->get('day'))->toBe(10)
        ->and($prefixedToday->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedTomorrow->index)->toBe(10)
        ->and($prefixedTomorrow->text)->toBe('Amanhã')
        ->and($prefixedTomorrow->start->get('year'))->toBe(2012)
        ->and($prefixedTomorrow->start->get('month'))->toBe(8)
        ->and($prefixedTomorrow->start->get('day'))->toBe(11)
        ->and($prefixedTomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($portuguese->parseText('O prazo é Amanhã', '2012-08-10 01:00')[0]->start->date()->toDateTimeString())
        ->toBe('2012-08-11 01:00:00')
        ->and($prefixedYesterday->index)->toBe(12)
        ->and($prefixedYesterday->text)->toBe('ontem')
        ->and($prefixedYesterday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($lastNight->text)->toBe('ontem à noite')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-09 22:00:00')
        ->and($portuguese->parseDateText('hoje', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-10 09:30:00')
        ->and($portuguese->parseDateText('amanhã', '2012-08-10 01:00')?->toDateTimeString())->toBe('2012-08-11 01:00:00')
        ->and($portuguese->parseDateText('ontem', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 09:30:00')
        ->and($morning->text)->toBe('esta manhã')
        ->and($morning->index)->toBe(12)
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 06:00:00')
        ->and($morning->start->tags())->toContain('parser/PTCasualTimeParser')
        ->and($afternoon->text)->toBe('esta tarde')
        ->and($afternoon->index)->toBe(12)
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($combined->text)->toBe('hoje às 5PM')
        ->and($combined->start->date()->toDateTimeString())->toBe('2012-08-10 17:00:00')
        ->and($tonightOnly->text)->toBe('esta noite')
        ->and($tonightOnly->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonightOnly->start->date()->toDateTimeString())->toBe('2012-01-01 22:00:00')
        ->and($tonightWithMeridiem->text)->toBe('esta noite 8pm')
        ->and($tonightWithMeridiem->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($tonight->text)->toBe('esta noite às 8')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-01-01 20:00:00')
        ->and($tonight->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($weekdayThursday->text)->toBe('quinta')
        ->and($weekdayThursday->start->get('weekday'))->toBe(4)
        ->and($weekdayFriday->text)->toBe('sexta')
        ->and($weekdayFriday->start->get('weekday'))->toBe(5)
        ->and($noon->text)->toBe('meio-dia')
        ->and($noon->start->date()->toDateTimeString())->toBe('2020-09-01 12:00:00')
        ->and($midnight->text)->toBe('meia-noite')
        ->and($midnight->start->date()->toDateTimeString())->toBe('2020-09-02 00:00:00')
        ->and($portuguese->parseText('naohoje', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('hyamanhã', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('xontem', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('porhora', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('agoraxsd', '2012-08-10'))->toBe([]);
});
