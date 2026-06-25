<?php

use Chrono\Chrono;

it('parses ukrainian weekdays', function () {
    $weekday = Chrono::uk()->parseText('середа', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::uk()->parseText('наступний понеділок', '2012-08-10 09:30')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/UKWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/UKWeekdayParser');
});

it('matches upstream ukrainian weekday examples', function (string $text, string $reference, string $expectedText, string $expectedDate, array $options = []) {
    $result = Chrono::uk()->parseText($text, $reference, $options)[0];

    expect($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate)
        ->and($result->start->tags())->toContain('parser/UKWeekdayParser');
})->with([
    ['понеділок', '2012-08-09', 'понеділок', '2012-08-06 12:00:00'],
    ["Дедлайн у п'ятницю...", '2012-08-09', "у п'ятницю", '2012-08-10 12:00:00'],
    ['Дедлайн в минулий четвер!', '2012-08-09', 'в минулий четвер', '2012-08-02 12:00:00'],
    ['Дедлайн в наступний вівторок!', '2015-04-18', 'в наступний вівторок', '2015-04-21 12:00:00'],
    ['Подзвони в середу вранці', '2015-04-18', 'в середу вранці', '2015-04-15 06:00:00'],
    ['неділя, 7 грудня 2014', '2012-08-09', 'неділя, 7 грудня 2014', '2014-12-07 12:00:00'],
    ['У понеділок?', '2012-08-09', 'У понеділок', '2012-08-13 12:00:00', ['forwardDate' => true]],
]);
