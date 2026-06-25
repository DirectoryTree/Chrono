<?php

use Chrono\Chrono;

it('parses vietnamese year expressions', function () {
    $year = Chrono::vi()->parseText('năm 1975', '2012-08-10')[0];
    $bcYear = Chrono::vi()->parseText('Năm 179 TCN, triều Điệt bị diệt.', '2012-08-10')[0];

    expect($year->start->date()->toDateTimeString())->toBe('1975-01-01 12:00:00')
        ->and($year->start->tags())->toContain('parser/VIYearParser')
        ->and($bcYear->text)->toBe('Năm 179 TCN')
        ->and($bcYear->start->get('year'))->toBe(-179);
});
