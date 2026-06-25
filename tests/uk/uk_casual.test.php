<?php

use Chrono\Chrono;

it('parses ukrainian casual dates and times', function () {
    $tomorrow = Chrono::uk()->parseText('завтра', '2012-08-10 17:10')[0];
    $afterAfterTomorrow = Chrono::uk()->parseText('післяпіслязавтра', '2012-08-10 17:10')[0];
    $beforeYesterday = Chrono::uk()->parseText('позавчора', '2012-08-10 17:10')[0];
    $beforeBeforeYesterday = Chrono::uk()->parseText('позапозавчора', '2012-08-10 17:10')[0];
    $now = Chrono::uk()->parseText('зараз', '2012-08-10 08:09:10.011')[0];
    $previousNight = Chrono::uk()->parseText('минулої ночі', '2012-08-10 08:09:10')[0];
    $earlyPreviousNight = Chrono::uk()->parseText('минулої ночі', '2012-08-10 02:09:10')[0];
    $evening = Chrono::uk()->parseText('ввечері', '2012-08-10 09:30')[0];
    $casualRange = Chrono::uk()->parseText('Подія від сьогодні і до післязавтра', '2012-08-04 12:00')[0];
    $dashRange = Chrono::uk()->parseText('Подія сьогодні-завтра', '2012-08-10 12:00')[0];
    $tomorrowMorning = Chrono::uk()->parseText('Дедлайн завтра вранці', '2012-09-10 14:00')[0];

    expect($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 17:10:00')
        ->and($tomorrow->start->tags())->toContain('parser/UKCasualDateParser')
        ->and($afterAfterTomorrow->start->date()->toDateTimeString())->toBe('2012-08-13 17:10:00')
        ->and($beforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 17:10:00')
        ->and($beforeYesterday->start->tags())->toContain('parser/UKCasualDateParser')
        ->and($beforeBeforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-07 17:10:00')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->isCertain('millisecond'))->toBeTrue()
        ->and($previousNight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($earlyPreviousNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($evening->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($evening->start->tags())->toContain('parser/UKCasualTimeParser')
        ->and($casualRange->text)->toBe('від сьогодні і до післязавтра')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($dashRange->text)->toBe('сьогодні-завтра')
        ->and($dashRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dashRange->end?->date()->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($tomorrowMorning->text)->toBe('завтра вранці')
        ->and($tomorrowMorning->start->date()->toDateTimeString())->toBe('2012-09-11 06:00:00')
        ->and(Chrono::uk()->parseText('несьогодні', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('звтра', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('ввчора', '2012-08-10'))->toBe([])
        ->and(Chrono::uk()->parseText('січен', '2012-08-10'))->toBe([]);
});
