<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses german casual dates and times', function () {
    $german = Chrono::de();
    $now = $german->parseText('Die Deadline ist jetzt', '2012-08-10 08:09:10.011')[0];

    expect($now->text)->toBe('jetzt')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->tags())->toContain('parser/DECasualDateParser')
        ->and($german->parseDateText('Die Deadline ist heute', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($german->parseDateText('Die Deadline ist morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00')
        ->and($german->parseDateText('Die Deadline war gestern', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($german->parseDateText('Die Deadline war letzte Nacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 00:00:00')
        ->and($german->parseDateText('Die Deadline war gestern Nacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-09 22:00:00')
        ->and($german->parseDateText('Die Deadline war heute Morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($german->parseDateText('Die Deadline war heute Nachmittag', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($german->parseDateText('Die Deadline war heute Abend', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($german->parseDateText('Die Deadline ist mittags', '2012-08-10 08:09:10.011')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($german->parseDateText('um Mitternacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($german->parseDateText('um Mitternacht', '2012-08-10 01:00')?->toDateTimeString())
        ->toBe('2012-08-10 00:00:00')
        ->and($german->parseDateText('Die Deadline ist heute 17 Uhr', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00')
        ->and($german->parseDateText('Die Deadline ist heute um 17 Uhr', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:00:00');
});

it('parses german casual dates with upstream-shaped components', function () {
    $german = Chrono::de();
    $now = $german->parseText('Die Deadline ist jetzt', '2012-08-10 08:09:10.011')[0];
    $today = $german->parseText('Die Deadline ist heute', '2012-08-10 12:00')[0];
    $tomorrow = $german->parseText('Die Deadline ist morgen', '2012-08-10 01:00')[0];
    $yesterday = $german->parseText('Die Deadline war gestern', '2012-08-10 12:00')[0];
    $lastNight = $german->parseText('Die Deadline war letzte Nacht ', '2012-08-10 12:00')[0];
    $yesterdayNight = $german->parseText('Die Deadline war gestern Nacht ', '2012-08-10 12:00')[0];
    $todayMorning = $german->parseText('Die Deadline war heute Morgen ', '2012-08-10 12:00')[0];
    $todayAfternoon = $german->parseText('Die Deadline war heute Nachmittag ', '2012-08-10 12:00')[0];
    $todayEvening = $german->parseText('Die Deadline war heute Abend ', '2012-08-10 12:00')[0];
    $midday = $german->parseText('Die Deadline ist mittags', '2012-08-10 08:09:10.011')[0];
    $midnight = $german->parseText('um Mitternacht', '2012-08-10 12:00')[0];
    $todayAtFive = $german->parseText('Die Deadline ist heute 17 Uhr', '2012-08-10 12:00')[0];
    $todayAtFiveWithPrefix = $german->parseText('Die Deadline ist heute um 17 Uhr', '2012-08-10 12:00')[0];

    expect($now->index)->toBe(17)
        ->and($now->text)->toBe('jetzt')
        ->and($now->start->get('year'))->toBe(2012)
        ->and($now->start->get('month'))->toBe(8)
        ->and($now->start->get('day'))->toBe(10)
        ->and($now->start->get('hour'))->toBe(8)
        ->and($now->start->get('minute'))->toBe(9)
        ->and($now->start->get('second'))->toBe(10)
        ->and($now->start->get('millisecond'))->toBe(11)
        ->and($today->index)->toBe(17)
        ->and($today->text)->toBe('heute')
        ->and($today->start->get('year'))->toBe(2012)
        ->and($today->start->get('month'))->toBe(8)
        ->and($today->start->get('day'))->toBe(10)
        ->and($tomorrow->index)->toBe(17)
        ->and($tomorrow->text)->toBe('morgen')
        ->and($tomorrow->start->get('year'))->toBe(2012)
        ->and($tomorrow->start->get('month'))->toBe(8)
        ->and($tomorrow->start->get('day'))->toBe(11)
        ->and($tomorrow->start->get('hour'))->toBe(1)
        ->and($yesterday->index)->toBe(17)
        ->and($yesterday->text)->toBe('gestern')
        ->and($yesterday->start->get('year'))->toBe(2012)
        ->and($yesterday->start->get('month'))->toBe(8)
        ->and($yesterday->start->get('day'))->toBe(9)
        ->and($lastNight->index)->toBe(17)
        ->and($lastNight->text)->toBe('letzte Nacht')
        ->and($lastNight->start->get('day'))->toBe(9)
        ->and($lastNight->start->get('hour'))->toBe(0)
        ->and($yesterdayNight->text)->toBe('gestern Nacht')
        ->and($yesterdayNight->start->get('day'))->toBe(9)
        ->and($yesterdayNight->start->get('hour'))->toBe(22)
        ->and($yesterdayNight->start->get('meridiem')->value)->toBe(Meridiem::PM->value)
        ->and($todayMorning->text)->toBe('heute Morgen')
        ->and($todayMorning->start->get('hour'))->toBe(6)
        ->and($todayMorning->start->get('meridiem')->value)->toBe(Meridiem::AM->value)
        ->and($todayAfternoon->text)->toBe('heute Nachmittag')
        ->and($todayAfternoon->start->get('hour'))->toBe(15)
        ->and($todayAfternoon->start->get('meridiem')->value)->toBe(Meridiem::PM->value)
        ->and($todayEvening->text)->toBe('heute Abend')
        ->and($todayEvening->start->get('hour'))->toBe(18)
        ->and($todayEvening->start->get('meridiem')->value)->toBe(Meridiem::PM->value)
        ->and($midday->index)->toBe(17)
        ->and($midday->text)->toBe('mittags')
        ->and($midday->start->get('hour'))->toBe(12)
        ->and($midday->start->get('minute'))->toBe(0)
        ->and($midday->start->get('second'))->toBe(0)
        ->and($midday->start->get('meridiem')->value)->toBe(Meridiem::AM->value)
        ->and($midnight->text)->toBe('Mitternacht')
        ->and($midnight->start->get('day'))->toBe(11)
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($midnight->start->get('meridiem')->value)->toBe(Meridiem::AM->value)
        ->and($todayAtFive->index)->toBe(17)
        ->and($todayAtFive->text)->toBe('heute 17 Uhr')
        ->and($todayAtFive->start->get('hour'))->toBe(17)
        ->and($todayAtFiveWithPrefix->index)->toBe(17)
        ->and($todayAtFiveWithPrefix->text)->toBe('heute um 17 Uhr')
        ->and($todayAtFiveWithPrefix->start->get('hour'))->toBe(17)
        ->and($german->parseText('nicheute'))->toBe([])
        ->and($german->parseText('heutenicht'))->toBe([])
        ->and($german->parseText('angestern'))->toBe([])
        ->and($german->parseText('jetztig'))->toBe([])
        ->and($german->parseText('ljetztlich'))->toBe([]);
});

it('parses german casual time references', function () {
    $german = Chrono::de();

    expect($german->parseText('Treffen wir uns vormittag', '2012-08-10 12:00')[0]->text)
        ->toBe('vormittag')
        ->and($german->parseText('Treffen wir uns vormittag', '2012-08-10 12:00')[0]->start->tags())
        ->toContain('parser/DECasualTimeParser')
        ->and($german->parseDateText('Treffen wir uns vormittag', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 09:00:00')
        ->and($german->parseDateText('Treffen wir uns nachmittag', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 15:00:00')
        ->and($german->parseDateText('Treffen wir uns abend', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($german->parseDateText('Treffen wir uns nacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 22:00:00')
        ->and($german->parseDateText('Treffen wir uns diesen morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($german->parseDateText('Treffen wir uns mitternacht', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 00:00:00')
        ->and($german->parseDateText('Die Deadline ist morgen', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-11 12:00:00');
});
