<?php

use Chrono\Chrono;

it('parses russian month expressions', function () {
    $dateTime = Chrono::ru()->parseText('10 августа 2012 в 6:30 вечера', '2012-08-10 09:30')[0];
    $range = Chrono::ru()->parseText('10 августа - 12 августа', '2012-08-10 09:30')[0];
    $monthYear = Chrono::ru()->parseText('Сентябрь 2012', '2020-11-22')[0];
    $shortMonthYear = Chrono::ru()->parseText('сен 2012', '2020-11-22')[0];
    $dottedMonthYear = Chrono::ru()->parseText('сен. 2012', '2020-11-22')[0];
    $hyphenatedMonthYear = Chrono::ru()->parseText('сен-2012', '2020-11-22')[0];
    $monthOnly = Chrono::ru()->parseText('май', '2020-11-22')[0];
    $monthOnlyWithPreposition = Chrono::ru()->parseText('в январе', '2020-11-22')[0];
    $shortMonthOnlyWithPreposition = Chrono::ru()->parseText('в янв', '2020-11-22')[0];
    $contextMonth = Chrono::ru()->parseText('Это было в сентябре 2012 перед новым годом', '2020-11-22')[0];
    $abbreviatedYear = Chrono::ru()->parseText('авг 96', '2012-08-10')[0];
    $abbreviatedYearWithPrefix = Chrono::ru()->parseText('96 авг 96', '2012-08-10')[0];

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
        ->and($contextMonth->index)->toBe(16)
        ->and($contextMonth->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($abbreviatedYear->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and($abbreviatedYearWithPrefix->text)->toBe('авг 96')
        ->and($abbreviatedYearWithPrefix->index)->toBe(3)
        ->and($abbreviatedYearWithPrefix->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00');
});
