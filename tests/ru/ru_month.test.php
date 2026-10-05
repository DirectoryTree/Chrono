<?php

use DirectoryTree\Chrono\Chrono;

it('parses russian month expressions', function () {
    $dateTime = Chrono::ru()->parse('10 августа 2012 в 6:30 вечера', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parse('10 августа - 12 августа', '2012-08-10 09:30')[0];
    $monthYear = Chrono::ru()->parse('Сентябрь 2012', '2020-11-22')[0];
    $shortMonthYear = Chrono::ru()->parse('сен 2012', '2020-11-22')[0];
    $dottedMonthYear = Chrono::ru()->parse('сен. 2012', '2020-11-22')[0];
    $hyphenatedMonthYear = Chrono::ru()->parse('сен-2012', '2020-11-22')[0];
    $monthOnly = Chrono::ru()->parse('май', '2020-11-22')[0];
    $monthOnlyWithPreposition = Chrono::ru()->parse('в январе', '2020-11-22')[0];
    $shortMonthOnlyWithPreposition = Chrono::ru()->parse('в янв', '2020-11-22')[0];
    $contextMonth = Chrono::ru()->parse('Это было в сентябре 2012 перед новым годом', '2020-11-22')[0];
    $abbreviatedYear = Chrono::ru()->parse('авг 96', '2012-08-10')[0];
    $abbreviatedYearWithPrefix = Chrono::ru()->parse('96 авг 96', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 августа 2012 в 6:30 вечера')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($monthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($shortMonthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($dottedMonthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($hyphenatedMonthYear->text)->toBe('сен-2012')
        ->and($hyphenatedMonthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($monthOnly->start->date()->toDateTimeString())->toBe('2021-05-01 12:00:00')
        ->and($monthOnlyWithPreposition->start->date()->toDateTimeString())->toBe('2021-01-01 12:00:00')
        ->and($shortMonthOnlyWithPreposition->start->date()->toDateTimeString())->toBe('2021-01-01 12:00:00')
        ->and($contextMonth->text)->toBe('в сентябре 2012')
        ->and($contextMonth->index)->toBe(9)
        ->and($contextMonth->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($abbreviatedYear->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and($abbreviatedYearWithPrefix->text)->toBe('авг 96')
        ->and($abbreviatedYearWithPrefix->index)->toBe(3)
        ->and($abbreviatedYearWithPrefix->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00');
});
