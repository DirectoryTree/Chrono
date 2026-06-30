<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Configuration;
use DirectoryTree\Chrono\ConfiguredChronoEngine;
use DirectoryTree\Chrono\Locales\Sv\Parsers\SvTimeUnitCasualRelativeFormatParser;

it('parses swedish casual relative time units', function () {
    $swedish = Chrono::sv();
    $swedishWithoutAbbreviations = new Chrono(new ConfiguredChronoEngine(new Configuration(
        parsers: [
            new SvTimeUnitCasualRelativeFormatParser(allowAbbreviations: false),
        ],
    )));
    $next = $swedish->parseText('nästa 2 dagar', '2012-08-10 09:30')[0];
    $weeks = $swedish->parseText('nästa 2 veckor', '2016-10-01 12:00')[0];
    $compound = $swedish->parseText('nästa 2 veckor 3 dagar', '2016-10-01 12:00')[0];
    $previous = $swedish->parseText('förra 3 veckor', '2012-08-10 09:30')[0];
    $previousWords = $swedish->parseText('förra två veckor', '2016-10-01 12:00')[0];
    $passed = $swedish->parseText('passerade 2 dagar', '2016-10-01 12:00')[0];
    $plusMinutes = $swedish->parseText('+15 minuter', '2012-07-10 12:14')[0];
    $plusOneCompactMinute = $swedish->parseText('+1min', '2012-07-10 12:14')[0];
    $compactPast = $swedish->parseText('-2tim5min', '2016-10-01 12:00')[0];
    $minusYears = $swedish->parseText('-3år', '2015-07-10 12:14')[0];

    expect($next->start->date()->toDateTimeString())->toBe('2012-08-12 09:30:00')
        ->and($next->start->tags())->toContain('parser/SVTimeUnitCasualRelativeFormatParser')
        ->and($next->tags())->toContain('result/relativeDate')
        ->and($weeks->start->date()->toDateTimeString())->toBe('2016-10-15 12:00:00')
        ->and($compound->start->date()->toDateTimeString())->toBe('2016-10-18 12:00:00')
        ->and($previous->start->date()->toDateTimeString())->toBe('2012-07-20 09:30:00')
        ->and($previousWords->start->date()->toDateTimeString())->toBe('2016-09-17 12:00:00')
        ->and($passed->start->date()->toDateTimeString())->toBe('2016-09-29 12:00:00')
        ->and($swedish->parseText('nästa två år', '2016-10-01 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2018-10-01 12:00:00')
        ->and($swedish->parseText('efter ett år', '2016-10-01 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2017-10-01 12:00:00')
        ->and($swedish->parseText('efter en timme', '2016-10-01 15:00')[0]->start->date()->toDateTimeString())
        ->toBe('2016-10-01 16:00:00')
        ->and($swedish->parseText('förra 2 veckor', '2016-10-01 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2016-09-17 12:00:00')
        ->and($swedish->parseText('+2 månader, 5 dagar', '2016-10-01 12:00')[0]->start->date()->toDateTimeString())
        ->toBe('2016-12-06 12:00:00')
        ->and($plusMinutes->text)->toBe('+15 minuter')
        ->and($plusMinutes->start->get('hour'))->toBe(12)
        ->and($plusMinutes->start->get('minute'))->toBe(29)
        ->and($swedish->parseText('+15min', '2012-07-10 12:14')[0]->start->date()->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and($swedish->parseText('+1 dag 2 timmar', '2012-07-10 12:14')[0]->start->date()->toDateTimeString())
        ->toBe('2012-07-11 14:14:00')
        ->and($plusOneCompactMinute->text)->toBe('+1min')
        ->and($plusOneCompactMinute->start->get('hour'))->toBe(12)
        ->and($plusOneCompactMinute->start->get('minute'))->toBe(15)
        ->and($compactPast->text)->toBe('-2tim5min')
        ->and($compactPast->start->date()->toDateTimeString())->toBe('2016-10-01 09:55:00')
        ->and($minusYears->text)->toBe('-3år')
        ->and($minusYears->start->get('year'))->toBe(2012)
        ->and($minusYears->start->get('month'))->toBe(7)
        ->and($minusYears->start->get('day'))->toBe(10)
        ->and($minusYears->start->get('hour'))->toBe(12)
        ->and($minusYears->start->get('minute'))->toBe(14)
        ->and($swedish->parseText('3år', '2016-10-01 12:00'))->toBe([])
        ->and($swedish->parseText('1 månad', '2016-10-01 12:00'))->toBe([])
        ->and($swedishWithoutAbbreviations->parseText('+15 minuter', '2012-07-10 12:14')[0]->start->date()->toDateTimeString())
        ->toBe('2012-07-10 12:29:00')
        ->and($swedishWithoutAbbreviations->parseText('+15min', '2012-07-10 12:14'))
        ->toBe([]);
});
