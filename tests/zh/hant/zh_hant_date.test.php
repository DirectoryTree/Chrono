<?php

use DirectoryTree\Chrono\Chrono;

it('parses traditional chinese dates', function () {
    $chinese = Chrono::zhHant();
    $date = $chinese->parseText('雞2016年9月3號全部都係雞', '2012-08-10')[0];
    $hanDate = $chinese->parseText('雞二零一六年，九月三號全部都係雞', '2012-08-10')[0];
    $impliedYear = $chinese->parseText('雞九月三號全部都係雞', '2014-08-10')[0];
    $zeroPadded = $chinese->parseText('2016年09月03日', '2012-08-10')[0];

    expect($date->text)->toBe('2016年9月3號')
        ->and($date->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHantDateParser')
        ->and($hanDate->text)->toBe('二零一六年，九月三號')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($impliedYear->text)->toBe('九月三號')
        ->and($impliedYear->start->date()->toDateTimeString())->toBe('2014-09-03 12:00:00')
        ->and($zeroPadded->text)->toBe('2016年09月03日')
        ->and($zeroPadded->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00');
});

it('parses traditional chinese date ranges', function () {
    $chinese = Chrono::zhHant();
    $range = $chinese->parseText('2016年9月3號-2017年10月24號', '2012-08-10')[0];
    $hanRange = $chinese->parseText('二零一六年九月三號ー2017年10月24號', '2012-08-10')[0];

    expect($range->text)->toBe('2016年9月3號-2017年10月24號')
        ->and($range->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00')
        ->and($hanRange->text)->toBe('二零一六年九月三號ー2017年10月24號')
        ->and($hanRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($hanRange->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00');
});
