<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese year expressions', function () {
    $vietnamese = Chrono::vi();
    $year = $vietnamese->parse('Việt Nam thống nhất vào năm 1976.', '2012-08-10')[0];
    $revolution = $vietnamese->parse('Cách mạng năm 1789.', '2012-08-10')[0];
    $bcYear = $vietnamese->parse('Năm 179 TCN, triều Điệt bị diệt.', '2012-08-10')[0];
    $ancientYear = $vietnamese->parse('Văn minh có từ năm 3000 TCN.', '2012-08-10')[0];
    $threeDigitYear = $vietnamese->parse('năm 938 là năm độc lập.', '2012-08-10')[0];
    $dateWithYear = $vietnamese->parse('ngày 2 tháng 9 năm 1945', '2012-08-10')[0];

    expect($year->text)->toBe('năm 1976')
        ->and($year->start->get('year'))->toBe(1976)
        ->and($year->start->tags())->toContain('parser/VIYearParser')
        ->and($revolution->start->get('year'))->toBe(1789)
        ->and($bcYear->text)->toBe('Năm 179 TCN')
        ->and($bcYear->start->get('year'))->toBe(-179)
        ->and($ancientYear->start->get('year'))->toBe(-3000)
        ->and($threeDigitYear->start->get('year'))->toBe(938)
        ->and($dateWithYear->start->get('year'))->toBe(1945)
        ->and($dateWithYear->start->isCertain('year'))->toBeTrue()
        ->and($vietnamese->parse('Có 1975 người tham gia.', '2012-08-10'))->toBe([]);
});
