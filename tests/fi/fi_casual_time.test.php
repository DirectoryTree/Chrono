<?php

use Chrono\Chrono;

it('parses standalone finnish casual time expressions', function () {
    $finnish = Chrono::fi();

    $morning = $finnish->parseText('aamulla', '2012-08-10 14:00')[0];
    $lateMorning = $finnish->parseText('aamupäivällä', '2012-08-10 14:00')[0];
    $noon = $finnish->parseText('päivällä', '2012-08-10 14:00')[0];
    $afternoon = $finnish->parseText('iltapäivällä', '2012-08-10 14:00')[0];
    $evening = $finnish->parseText('illalla', '2012-08-10 14:00')[0];
    $night = $finnish->parseText('yöllä', '2012-08-10 14:00')[0];
    $midnight = $finnish->parseText('keskiyöllä', '2012-08-10 14:00')[0];

    expect($morning->text)->toBe('aamulla')
        ->and($morning->start->get('hour'))->toBe(6)
        ->and($morning->start->get('minute'))->toBe(0)
        ->and($lateMorning->text)->toBe('aamupäivällä')
        ->and($lateMorning->start->get('hour'))->toBe(9)
        ->and($lateMorning->start->get('minute'))->toBe(0)
        ->and($noon->text)->toBe('päivällä')
        ->and($noon->start->get('hour'))->toBe(12)
        ->and($noon->start->get('minute'))->toBe(0)
        ->and($afternoon->text)->toBe('iltapäivällä')
        ->and($afternoon->start->get('hour'))->toBe(15)
        ->and($afternoon->start->get('minute'))->toBe(0)
        ->and($evening->text)->toBe('illalla')
        ->and($evening->start->get('hour'))->toBe(18)
        ->and($evening->start->get('minute'))->toBe(0)
        ->and($night->text)->toBe('yöllä')
        ->and($night->start->get('hour'))->toBe(22)
        ->and($night->start->get('minute'))->toBe(0)
        ->and($midnight->text)->toBe('keskiyöllä')
        ->and($midnight->start->get('hour'))->toBe(0)
        ->and($midnight->start->get('minute'))->toBe(0);
});

it('parses finnish last night', function () {
    $result = Chrono::fi()->parseText('viime yönä', '2012-08-10 14:00')[0];

    expect($result->text)->toBe('viime yönä')
        ->and($result->start->get('year'))->toBe(2012)
        ->and($result->start->get('month'))->toBe(8)
        ->and($result->start->get('day'))->toBe(9)
        ->and($result->start->get('hour'))->toBe(0);
});
