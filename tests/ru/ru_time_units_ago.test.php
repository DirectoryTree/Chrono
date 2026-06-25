<?php

use Chrono\Chrono;

it('matches upstream russian ago time unit expressions', function (string $text, string $expectedText, string $expectedDate) {
    $result = Chrono::ru()->parseText($text, '2012-07-10 00:00')[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate)
        ->and($result->start->tags())->toContain('parser/RUTimeUnitAgoFormatParser');
})->with([
    ['5 дней назад что-то было', '5 дней назад', '2012-07-05 00:00:00'],
    ['5 минут назад что-то было', '5 минут назад', '2012-07-09 23:55:00'],
    ['полчаса назад что-то было', 'полчаса назад', '2012-07-09 23:30:00'],
    ['5 дней 2 часа назад что-то было', '5 дней 2 часа назад', '2012-07-04 22:00:00'],
    ['5 минут 20 секунд назад что-то было', '5 минут 20 секунд назад', '2012-07-09 23:54:40'],
    ['2 часа 5 минут назад что-то было', '2 часа 5 минут назад', '2012-07-09 21:55:00'],
]);

it('does not parse incomplete russian ago time unit expressions', function (string $text) {
    expect(Chrono::ru()->parseText($text, '2012-07-10 00:00'))->toBe([]);
})->with([
    '15 часов 29 мин',
    'несколько часов',
    '5 дней',
]);
