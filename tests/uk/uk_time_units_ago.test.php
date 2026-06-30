<?php

use DirectoryTree\Chrono\Chrono;

it('matches upstream ukrainian ago time unit expressions', function (string $text, string $expectedText, string $expectedDate) {
    $result = Chrono::uk()->parseText($text, '2012-07-10 00:00')[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe($expectedText)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate)
        ->and($result->start->tags())->toContain('parser/UKTimeUnitAgoFormatParser');
})->with([
    ['5 днів тому щось відбулось', '5 днів тому', '2012-07-05 00:00:00'],
    ['5 хвилин тому щось відбулось', '5 хвилин тому', '2012-07-09 23:55:00'],
    ['півгодини тому щось відбулось', 'півгодини тому', '2012-07-09 23:30:00'],
    ['5 днів 2 години тому щось відбулось', '5 днів 2 години тому', '2012-07-04 22:00:00'],
    ['5 хвилин 20 секунд тому щось сталось', '5 хвилин 20 секунд тому', '2012-07-09 23:54:40'],
    ['2 години 5 хвилин тому щось сталось', '2 години 5 хвилин тому', '2012-07-09 21:55:00'],
]);

it('does not parse incomplete ukrainian ago time unit expressions', function (string $text) {
    expect(Chrono::uk()->parseText($text, '2012-07-10 00:00'))->toBe([]);
})->with([
    '15 годин 29 хв.',
    'декілька годин',
    '5 днів',
]);
