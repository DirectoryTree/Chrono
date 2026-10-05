<?php

use DirectoryTree\Chrono\Chrono;

it('rejects vietnamese negative cases', function () {
    $vietnamese = Chrono::vi();

    expect($vietnamese->parse('ngày 0 tháng 4 năm 2000', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('tháng 0', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('ngày 1 tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('32/13/2020', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('Có 1975 người tham gia.', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('0912345678', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('11', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('0.5', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('35.49', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('12.53%', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('$1,194.09', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('at 6.5 kilograms', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('1.1.3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('1.10.30', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('1-2', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('1-2-3', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('%e7%b7%8a', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('7 giờ 61 phút', '2012-08-10'))->toBe([])
        ->and($vietnamese->parse('7 giờ 99 phút', '2012-08-10'))->toBe([]);
});
