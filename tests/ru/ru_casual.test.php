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
        ->and($now->index)->toBe(0)
        ->and($now->text)->toBe('сейчас')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:09:10.011')
        ->and($now->start->date()->format('Y-m-d H:i:s.v'))->toBe($now->refDate?->format('Y-m-d H:i:s.v'))
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

it('matches upstream russian casual date and time examples', function (string $text, string $reference, string $expectedText, string $expectedDate) {
    $result = Chrono::ru()->parseText($text, $reference)[0];

    expect($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate);
})->with([
    ['Дедлайн сегодня', '2012-08-10 17:10', 'сегодня', '2012-08-10 17:10:00'],
    ['Дедлайн завтра', '2012-08-10 17:10', 'завтра', '2012-08-11 17:10:00'],
    ['Дедлайн послезавтра', '2012-08-10 17:10', 'послезавтра', '2012-08-12 17:10:00'],
    ['Дедлайн послепослезавтра', '2012-08-10 17:10', 'послепослезавтра', '2012-08-13 17:10:00'],
    ['Дедлайн вчера', '2012-08-10 17:10', 'вчера', '2012-08-09 17:10:00'],
    ['Дедлайн позавчера', '2012-08-10 17:10', 'позавчера', '2012-08-08 17:10:00'],
    ['Дедлайн позапозавчера', '2012-08-10 17:10', 'позапозавчера', '2012-08-07 17:10:00'],
    ['Дедлайн утром', '2012-08-10 08:09:10.011', 'утром', '2012-08-10 06:00:00'],
    ['Дедлайн этим утром', '2012-08-10 08:09:10.011', 'этим утром', '2012-08-10 06:00:00'],
    ['Дедлайн в полдень', '2012-08-10 08:09:10.011', 'в полдень', '2012-08-10 12:00:00'],
    ['Дедлайн прошлым вечером', '2012-08-10 08:09:10.011', 'прошлым вечером', '2012-08-09 20:00:00'],
    ['Дедлайн вечером', '2012-08-10 08:09:10.011', 'вечером', '2012-08-10 20:00:00'],
    ['Дедлайн прошлой ночью', '2012-08-10 08:09:10.011', 'прошлой ночью', '2012-08-10 00:00:00'],
    ['Дедлайн прошлой ночью', '2012-08-10 02:09:10.011', 'прошлой ночью', '2012-08-09 00:00:00'],
    ['Дедлайн сегодня ночью', '2012-08-10 02:09:10.011', 'сегодня ночью', '2012-08-10 00:00:00'],
    ['Дедлайн этой ночью', '2012-08-10 02:09:10.011', 'этой ночью', '2012-08-10 00:00:00'],
    ['Дедлайн ночью', '2012-08-10 02:09:10.011', 'ночью', '2012-08-10 00:00:00'],
    ['Дедлайн в полночь', '2012-08-10 02:09:10.011', 'в полночь', '2012-08-10 00:00:00'],
    ['Дедлайн вчера вечером', '2012-08-10 12:00', 'вчера вечером', '2012-08-09 20:00:00'],
    ['Дедлайн завтра утром', '2012-09-10 14:00', 'завтра утром', '2012-09-11 06:00:00'],
]);

it('matches upstream russian casual date ranges', function (string $text, string $reference, string $expectedText, string $expectedStart, string $expectedEnd) {
    $result = Chrono::ru()->parseText($text, $reference)[0];

    expect($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedStart)
        ->and($result->end?->date()->toDateTimeString())->toBe($expectedEnd);
})->with([
    ['Событие с сегодня и до послезавтра', '2012-08-04 12:00', 'с сегодня и до послезавтра', '2012-08-04 12:00:00', '2012-08-06 12:00:00'],
    ['Событие сегодня-завтра', '2012-08-10 12:00', 'сегодня-завтра', '2012-08-10 12:00:00', '2012-08-11 12:00:00'],
]);

it('does not parse invalid russian casual text', function (string $text) {
    expect(Chrono::ru()->parseText($text, '2012-08-10'))->toBe([]);
})->with([
    'несегодня',
    'зявтра',
    'вчеера',
    'январ',
]);
