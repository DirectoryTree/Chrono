<?php

use Chrono\Chrono;

it('rejects vietnamese negative cases', function () {
    $vietnamese = Chrono::vi();

    expect($vietnamese->parseText('ngày 0 tháng 4 năm 2000', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('Có 1975 người tham gia.', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('0912345678', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('7 giờ 61 phút', '2012-08-10'))->toBe([]);
});
