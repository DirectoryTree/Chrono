<?php

use Chrono\Chrono;

it('parses finnish within time unit expressions', function () {
    $finnish = Chrono::fi();

    $withinDays = $finnish->parseText('pitää tehdä jotain 5 päivää sisällä', '2012-08-10')[0];
    $withinMinutes = $finnish->parseText('5 minuuttia sisällä', '2012-08-10 12:14')[0];
    $withinHours = $finnish->parseText('1 tuntia sisällä', '2012-08-10 12:14')[0];
    $withinWeeks = $finnish->parseText('2 viikkoa sisällä', '2012-08-10 12:14')[0];
    $duringDays = $finnish->parseText('5 päivää kuluessa', '2012-08-10')[0];
    $duringYears = $finnish->parseText('yksi vuotta kuluessa', '2012-08-10 12:14')[0];
    $fromNowMinutes = $finnish->parseText('5 minuuttia päästä', '2012-08-10 12:14')[0];
    $fromNowDays = $finnish->parseText('3 päivää päästä', '2012-08-10 12:14')[0];
    $fromNowWeeks = $finnish->parseText('2 viikkoa päästä', '2016-10-01')[0];

    expect($withinDays->text)->toBe('5 päivää sisällä')
        ->and($withinDays->start->date()->toDateString())->toBe('2012-08-15')
        ->and($withinMinutes->index)->toBe(0)
        ->and($withinMinutes->text)->toBe('5 minuuttia sisällä')
        ->and($withinMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($withinHours->index)->toBe(0)
        ->and($withinHours->text)->toBe('1 tuntia sisällä')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($withinWeeks->text)->toBe('2 viikkoa sisällä')
        ->and($withinWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:14:00')
        ->and($duringDays->text)->toBe('5 päivää kuluessa')
        ->and($duringDays->start->date()->toDateString())->toBe('2012-08-15')
        ->and($duringYears->text)->toBe('yksi vuotta kuluessa')
        ->and($duringYears->start->date()->toDateTimeString())->toBe('2013-08-10 12:14:00')
        ->and($fromNowMinutes->text)->toBe('5 minuuttia päästä')
        ->and($fromNowMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($fromNowDays->text)->toBe('3 päivää päästä')
        ->and($fromNowDays->start->date()->toDateString())->toBe('2012-08-13')
        ->and($fromNowWeeks->text)->toBe('2 viikkoa päästä')
        ->and($fromNowWeeks->start->date()->toDateString())->toBe('2016-10-15');
});
