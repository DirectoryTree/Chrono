<?php

use DirectoryTree\Chrono\Chrono;

it('parses russian weekdays', function () {
    $weekday = Chrono::ru()->parseText('среда', '2012-08-10 09:30')[0];
    $nextWeekday = Chrono::ru()->parseText('следующий понедельник', '2012-08-10 09:30')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/RUWeekdayParser')
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($nextWeekday->start->tags())->toContain('parser/RUWeekdayParser');
});

it('matches upstream russian weekday examples', function (string $text, string $reference, string $expectedText, string $expectedDate, array $options = []) {
    $result = Chrono::ru()->parseText($text, $reference, $options)[0];

    expect($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate)
        ->and($result->start->tags())->toContain('parser/RUWeekdayParser');
})->with([
    ['понедельник', '2012-08-09', 'понедельник', '2012-08-06 12:00:00'],
    ['Дедлайн в пятницу...', '2012-08-09', 'в пятницу', '2012-08-10 12:00:00'],
    ['Дедлайн в прошлый четверг!', '2012-08-09', 'в прошлый четверг', '2012-08-02 12:00:00'],
    ['Дедлайн в следующий вторник', '2015-04-18', 'в следующий вторник', '2015-04-21 12:00:00'],
    ['Позвони в среду утром', '2015-04-18', 'в среду утром', '2015-04-15 06:00:00'],
    ['воскресенье, 7 декабря 2014', '2012-08-09', 'воскресенье, 7 декабря 2014', '2014-12-07 12:00:00'],
    ['В понедельник?', '2012-08-09', 'В понедельник', '2012-08-13 12:00:00', ['forwardDate' => true]],
]);
