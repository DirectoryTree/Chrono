<?php

use Chrono\Chrono;

it('rejects vietnamese negative cases', function () {
    $vietnamese = Chrono::vi();

    expect($vietnamese->parseText('ngày 0 tháng 4 năm 2000', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 0', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('ngày 1 tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('32/13/2020', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('Có 1975 người tham gia.', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('0912345678', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('11', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('0.5', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('35.49', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('12.53%', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('$1,194.09', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('at 6.5 kilograms', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('1.1.3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('1.10.30', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('1-2', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('1-2-3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('%e7%b7%8a', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('7 giờ 61 phút', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('7 giờ 99 phút', '2012-08-10'))->toBe([]);
});
