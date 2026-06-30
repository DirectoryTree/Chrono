<?php

use DirectoryTree\Chrono\Chrono;

it('parses ukrainian month expressions', function () {
    $dateTime = Chrono::uk()->parseText('10 серпня 2012 о 6:30 вечора', '2012-08-10 09:30')[0];
    $range = Chrono::uk()->parseText('10 серпня - 12 серпня', '2012-08-10 09:30')[0];
    $monthYear = Chrono::uk()->parseText('Вересень 2012', '2020-11-22')[0];
    $shortMonthYear = Chrono::uk()->parseText('верес 2012', '2020-11-22')[0];
    $dottedMonthYear = Chrono::uk()->parseText('верес. 2012', '2020-11-22')[0];
    $hyphenatedMonthYear = Chrono::uk()->parseText('верес-2012', '2020-11-22')[0];
    $monthOnly = Chrono::uk()->parseText('травень', '2020-11-22')[0];
    $monthOnlyWithPreposition = Chrono::uk()->parseText('у січні', '2020-11-22')[0];
    $shortMonthOnlyWithPreposition = Chrono::uk()->parseText('в січ', '2020-11-22')[0];
    $contextMonth = Chrono::uk()->parseText('Це було у вересні 2012 перед новим роком', '2020-11-22')[0];
    $abbreviatedYear = Chrono::uk()->parseText('сер 96', '2012-08-10')[0];
    $abbreviatedYearWithPrefix = Chrono::uk()->parseText('96 сер 96', '2012-08-10')[0];

    expect($dateTime->text)->toBe('10 серпня 2012 о 6:30 вечора')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($monthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($shortMonthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($dottedMonthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($hyphenatedMonthYear->text)->toBe('верес-2012')
        ->and($hyphenatedMonthYear->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($monthOnly->start->date()->toDateTimeString())->toBe('2021-05-01 12:00:00')
        ->and($monthOnlyWithPreposition->start->date()->toDateTimeString())->toBe('2021-01-01 12:00:00')
        ->and($shortMonthOnlyWithPreposition->start->date()->toDateTimeString())->toBe('2021-01-01 12:00:00')
        ->and($contextMonth->text)->toBe('у вересні 2012')
        ->and($contextMonth->index)->toBe(8)
        ->and($contextMonth->start->date()->toDateTimeString())->toBe('2012-09-01 12:00:00')
        ->and($abbreviatedYear->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00')
        ->and($abbreviatedYearWithPrefix->text)->toBe('сер 96')
        ->and($abbreviatedYearWithPrefix->index)->toBe(3)
        ->and($abbreviatedYearWithPrefix->start->date()->toDateTimeString())->toBe('1996-08-01 12:00:00');
});
