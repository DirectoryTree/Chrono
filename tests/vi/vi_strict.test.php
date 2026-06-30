<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese strict mode like upstream', function () {
    $strict = Chrono::strictVietnamese();

    expect($strict->parseText('hôm nay', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('hôm qua', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('ngày mai', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('ngày kia', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('buổi sáng', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('buổi trưa', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('buổi chiều', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('buổi tối', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('tuần này', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('tháng trước', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('năm sau', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('thứ hai', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseText('chủ nhật', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parseDateText('ngày 30 tháng 4 năm 1975', '2012-08-10 12:00')?->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($strict->parseDateText('lúc 7 giờ 30 phút', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 07:30:00')
        ->and($strict->parseDateText('30/4/1975', '2012-08-10 12:00')?->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($strict->parseDateText('15/3', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-03-15 12:00:00')
        ->and($strict->parseDateText('3 ngày trước', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-07 12:00:00')
        ->and($strict->parseDateText('2 tuần sau', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-24 12:00:00');
});
