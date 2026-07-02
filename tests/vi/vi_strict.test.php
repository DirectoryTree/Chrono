<?php

use DirectoryTree\Chrono\Chrono;

it('parses vietnamese strict mode like upstream', function () {
    $strict = Chrono::strictVietnamese();

    expect($strict->parse('hôm nay', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('hôm qua', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('ngày mai', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('ngày kia', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('buổi sáng', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('buổi trưa', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('buổi chiều', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('buổi tối', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('tuần này', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('tháng trước', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('năm sau', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('thứ hai', '2012-08-10 12:00'))->toBe([])
        ->and($strict->parse('chủ nhật', '2012-08-10 12:00'))->toBe([])
        ->and($strict->date('ngày 30 tháng 4 năm 1975', '2012-08-10 12:00')?->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($strict->date('lúc 7 giờ 30 phút', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 07:30:00')
        ->and($strict->date('30/4/1975', '2012-08-10 12:00')?->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($strict->date('15/3', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-03-15 12:00:00')
        ->and($strict->date('3 ngày trước', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-07 12:00:00')
        ->and($strict->date('2 tuần sau', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-24 12:00:00');
});
