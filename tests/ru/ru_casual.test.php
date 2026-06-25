<?php

use Chrono\Chrono;

it('parses russian casual dates and times', function () {
    $tomorrow = Chrono::ru()->parseText('завтра', '2012-08-10 17:10')[0];
    $beforeYesterday = Chrono::ru()->parseText('позавчера', '2012-08-10 17:10')[0];
    $now = Chrono::ru()->parseText('сейчас', '2012-08-10 08:09:10.011')[0];
    $evening = Chrono::ru()->parseText('вечером', '2012-08-10 09:30')[0];
    $lastNight = Chrono::ru()->parseText('прошлой ночью', '2012-08-10 08:09:10.011')[0];
    $earlyLastNight = Chrono::ru()->parseText('прошлой ночью', '2012-08-10 02:09:10.011')[0];
    $tomorrowMorning = Chrono::ru()->parseText('Дедлайн завтра утром', '2012-08-10 17:10')[0];
    $casualRange = Chrono::ru()->parseText('Событие сегодня-завтра', '2012-08-10 12:00')[0];

    expect($tomorrow->start->date()->toDateTimeString())->toBe('2012-08-11 17:10:00')
        ->and($tomorrow->start->tags())->toContain('parser/RUCasualDateParser')
        ->and($beforeYesterday->start->date()->toDateTimeString())->toBe('2012-08-08 17:10:00')
        ->and($beforeYesterday->start->tags())->toContain('parser/RUCasualDateParser')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->isCertain('year'))->toBeTrue()
        ->and($now->start->isCertain('millisecond'))->toBeTrue()
        ->and($evening->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($evening->start->tags())->toContain('parser/RUCasualTimeParser')
        ->and($lastNight->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($earlyLastNight->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($tomorrowMorning->text)->toBe('завтра утром')
        ->and($tomorrowMorning->start->date()->toDateTimeString())->toBe('2012-08-11 06:00:00')
        ->and($casualRange->text)->toBe('сегодня-завтра')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-11 12:00:00');
});
