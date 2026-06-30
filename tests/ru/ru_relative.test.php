<?php

use DirectoryTree\Chrono\Chrono;

it('matches upstream russian relative date expressions', function (string $text, string $reference, string $expectedDate) {
    $result = Chrono::ru()->parseText($text, $reference)[0];

    expect($result->index)->toBe(0)
        ->and($result->text)->toBe($text)
        ->and($result->start->date()->toDateTimeString())->toBe($expectedDate)
        ->and($result->start->tags())->toContain('parser/RURelativeDateFormatParser');
})->with([
    ['на этой неделе', '2017-11-19 12:00', '2017-11-19 12:00:00'],
    ['в этом месяце', '2017-11-19 12:00', '2017-11-01 12:00:00'],
    ['в этом месяце', '2017-11-01 12:00', '2017-11-01 12:00:00'],
    ['в этом году', '2017-11-19 12:00', '2017-01-01 12:00:00'],
    ['на прошлой неделе', '2016-10-01 12:00', '2016-09-24 12:00:00'],
    ['в прошлом месяце', '2016-10-01 12:00', '2016-09-01 12:00:00'],
    ['в прошлом году', '2016-10-01 12:00', '2015-10-01 12:00:00'],
    ['на следующей неделе', '2016-10-01 12:00', '2016-10-08 12:00:00'],
    ['в следующем месяце', '2016-10-01 12:00', '2016-11-01 12:00:00'],
    ['в следующем квартале', '2016-10-01 12:00', '2017-01-01 12:00:00'],
    ['в следующем году', '2016-10-01 12:00', '2017-10-01 12:00:00'],
]);
