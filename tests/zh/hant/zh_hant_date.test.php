<?php

use Chrono\Chrono;

it('parses traditional chinese dates', function () {
    $chinese = Chrono::zhHant();
    $date = $chinese->parseText('二零一四年七月十二日', '2012-08-10')[0];
    $hanDate = $chinese->parseText('雞二零一六年，九月三號全部都係雞', '2012-08-10')[0];

    expect($date->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHantDateParser')
        ->and($hanDate->text)->toBe('二零一六年，九月三號')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00');
});
