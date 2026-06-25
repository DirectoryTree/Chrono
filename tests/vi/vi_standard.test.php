<?php

use Chrono\Chrono;

it('parses vietnamese standard month year and year expressions', function () {
    $vietnamese = Chrono::vi();
    $standard = $vietnamese->parseText('ngày 15 tháng 3 năm 1975', '2012-08-10')[0];
    $prefixedStandard = $vietnamese->parseText('Ngày 30 tháng 4 năm 1975 là ngày giải phóng.', '2012-08-10 12:00')[0];
    $embeddedStandard = $vietnamese->parseText('Hiệp định được ký ngày 27 tháng 1 năm 1973.', '2012-08-10 12:00')[0];
    $noPrefixStandard = $vietnamese->parseText('7 tháng 5 năm 1954 là ngày chấm dứt trận Điện Biên Phủ.', '2012-08-10 12:00')[0];
    $impliedYear = $vietnamese->parseText('ngày 15 tháng 3', '2012-08-10 12:00')[0];
    $positionedStandard = $vietnamese->parseText('Sự kiện ngày 30 tháng 4 năm 1975 quan trọng.', '2012-08-10 12:00')[0];
    $bcStandard = $vietnamese->parseText('ngày 1 tháng 1 năm 300 TCN', '2012-08-10')[0];
    $month = $vietnamese->parseText('tháng chạp năm 1975', '2012-08-10')[0];
    $numberedMonth = $vietnamese->parseText('tháng 4 năm 1975', '2012-08-10')[0];
    $slashMonth = $vietnamese->parseText('tháng 3/1975', '2012-08-10')[0];
    $impliedYearMonth = $vietnamese->parseText('tháng 3', '2012-08-10')[0];
    $year = $vietnamese->parseText('năm 1975', '2012-08-10')[0];
    $embeddedYear = $vietnamese->parseText('Việt Nam thống nhất vào năm 1976.', '2012-08-10')[0];
    $accentedEmbeddedYear = $vietnamese->parseText('Cách mạng năm 1789.', '2012-08-10')[0];
    $bcYear = $vietnamese->parseText('Năm 179 TCN, triều Điệt bị diệt.', '2012-08-10')[0];
    $largeBcYear = $vietnamese->parseText('Văn minh có từ năm 3000 TCN.', '2012-08-10')[0];
    $threeDigitYear = $vietnamese->parseText('năm 938 là năm độc lập.', '2012-08-10')[0];
    $slash = $vietnamese->parseText('Ngày 30/04/1975.', '2012-08-10')[0];
    $embeddedSlash = $vietnamese->parseText('Hội nghị 01/01/1954', '2012-08-10')[0];
    $shortSlash = $vietnamese->parseText('3/5/1968', '2012-08-10')[0];
    $iso = $vietnamese->parseText('Ngày 2024-03-15 là quan trọng.', '2012-08-10')[0];

    expect($standard->start->date()->toDateTimeString())->toBe('1975-03-15 12:00:00')
        ->and($standard->start->tags())->toContain('parser/VIStandardParser')
        ->and($standard->start->isCertain('year'))->toBeTrue()
        ->and($prefixedStandard->text)->toBe('Ngày 30 tháng 4 năm 1975')
        ->and($prefixedStandard->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($embeddedStandard->index)->toBe(18)
        ->and($embeddedStandard->text)->toBe('ngày 27 tháng 1 năm 1973')
        ->and($embeddedStandard->start->date()->toDateTimeString())->toBe('1973-01-27 12:00:00')
        ->and($noPrefixStandard->text)->toBe('7 tháng 5 năm 1954')
        ->and($noPrefixStandard->start->date()->toDateTimeString())->toBe('1954-05-07 12:00:00')
        ->and($impliedYear->start->date()->toDateTimeString())->toBe('2012-03-15 12:00:00')
        ->and($impliedYear->start->isCertain('year'))->toBeFalse()
        ->and($positionedStandard->index)->toBe(8)
        ->and($positionedStandard->text)->toBe('ngày 30 tháng 4 năm 1975')
        ->and($bcStandard->start->get('year'))->toBe(-300)
        ->and($bcStandard->start->date()->format('Y-m-d H:i:s'))->toBe('-0300-01-01 12:00:00')
        ->and($month->start->date()->toDateTimeString())->toBe('1975-12-01 12:00:00')
        ->and($month->start->tags())->toContain('parser/VIMonthYearParser')
        ->and($numberedMonth->text)->toBe('tháng 4 năm 1975')
        ->and($numberedMonth->start->get('month'))->toBe(4)
        ->and($numberedMonth->start->get('year'))->toBe(1975)
        ->and($numberedMonth->start->date()->toDateTimeString())->toBe('1975-04-01 12:00:00')
        ->and($numberedMonth->start->isCertain('month'))->toBeTrue()
        ->and($numberedMonth->start->isCertain('year'))->toBeTrue()
        ->and($numberedMonth->start->isCertain('day'))->toBeFalse()
        ->and($slashMonth->text)->toBe('tháng 3/1975')
        ->and($slashMonth->start->get('month'))->toBe(3)
        ->and($slashMonth->start->get('year'))->toBe(1975)
        ->and($slashMonth->start->date()->toDateTimeString())->toBe('1975-03-01 12:00:00')
        ->and($impliedYearMonth->start->get('month'))->toBe(3)
        ->and($impliedYearMonth->start->date()->toDateTimeString())->toBe('2012-03-01 12:00:00')
        ->and($impliedYearMonth->start->isCertain('year'))->toBeFalse()
        ->and($year->start->date()->toDateTimeString())->toBe('1975-01-01 12:00:00')
        ->and($year->start->tags())->toContain('parser/VIYearParser')
        ->and($embeddedYear->text)->toBe('năm 1976')
        ->and($embeddedYear->start->date()->toDateTimeString())->toBe('1976-01-01 12:00:00')
        ->and($accentedEmbeddedYear->start->date()->toDateTimeString())->toBe('1789-01-01 12:00:00')
        ->and($bcYear->text)->toBe('Năm 179 TCN')
        ->and($bcYear->start->get('year'))->toBe(-179)
        ->and($bcYear->start->tags())->toContain('parser/VIYearParser')
        ->and($largeBcYear->text)->toBe('năm 3000 TCN')
        ->and($largeBcYear->start->get('year'))->toBe(-3000)
        ->and($threeDigitYear->text)->toBe('năm 938')
        ->and($threeDigitYear->start->date()->format('Y-m-d H:i:s'))->toBe('0938-01-01 12:00:00')
        ->and($slash->text)->toBe('30/04/1975')
        ->and($slash->index)->toBe(5)
        ->and($slash->start->get('day'))->toBe(30)
        ->and($slash->start->get('month'))->toBe(4)
        ->and($slash->start->get('year'))->toBe(1975)
        ->and($slash->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($slash->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($embeddedSlash->index)->toBe(9)
        ->and($embeddedSlash->text)->toBe('01/01/1954')
        ->and($embeddedSlash->start->get('day'))->toBe(1)
        ->and($embeddedSlash->start->get('month'))->toBe(1)
        ->and($embeddedSlash->start->get('year'))->toBe(1954)
        ->and($embeddedSlash->start->date()->toDateTimeString())->toBe('1954-01-01 12:00:00')
        ->and($shortSlash->text)->toBe('3/5/1968')
        ->and($shortSlash->start->get('day'))->toBe(3)
        ->and($shortSlash->start->get('month'))->toBe(5)
        ->and($shortSlash->start->get('year'))->toBe(1968)
        ->and($shortSlash->start->date()->toDateTimeString())->toBe('1968-05-03 12:00:00')
        ->and($iso->index)->toBe(5)
        ->and($iso->text)->toBe('2024-03-15')
        ->and($iso->start->get('day'))->toBe(15)
        ->and($iso->start->get('month'))->toBe(3)
        ->and($iso->start->get('year'))->toBe(2024)
        ->and($iso->start->date()->toDateTimeString())->toBe('2024-03-15 12:00:00')
        ->and($vietnamese->parseText('ngày 1 tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 13', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('tháng 0', '2012-08-10'))->toBe([])
        ->and($vietnamese->parseText('Có 1975 người tham gia.', '2012-08-10'))->toBe([]);
});
