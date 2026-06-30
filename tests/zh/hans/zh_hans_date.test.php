<?php

use DirectoryTree\Chrono\Chrono;

it('parses simplified chinese dates', function () {
    $chinese = Chrono::zhHans();
    $date = $chinese->parseText('我2016年9月3号要打游戏', '2012-08-10')[0];
    $hanDate = $chinese->parseText('我二零一六年，九月三号要打游戏', '2012-08-10')[0];
    $impliedYear = $chinese->parseText('我九月三号要打游戏', '2014-08-10')[0];
    $zeroPadded = $chinese->parseText('2016年09月03号', '2012-08-10')[0];

    expect($date->text)->toBe('2016年9月3号')
        ->and($date->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($date->start->tags())->toContain('parser/ZHHansDateParser')
        ->and($hanDate->text)->toBe('二零一六年，九月三号')
        ->and($hanDate->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($impliedYear->text)->toBe('九月三号')
        ->and($impliedYear->start->date()->toDateTimeString())->toBe('2014-09-03 12:00:00')
        ->and($zeroPadded->text)->toBe('2016年09月03号')
        ->and($zeroPadded->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00');
});

it('parses simplified chinese date ranges', function () {
    $chinese = Chrono::zhHans();
    $range = $chinese->parseText('2016年9月3号-2017年10月24号', '2012-08-10')[0];
    $hanRange = $chinese->parseText('二零一六年九月三号ー2017年10月24号', '2012-08-10')[0];

    expect($range->text)->toBe('2016年9月3号-2017年10月24号')
        ->and($range->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00')
        ->and($hanRange->text)->toBe('二零一六年九月三号ー2017年10月24号')
        ->and($hanRange->start->date()->toDateTimeString())->toBe('2016-09-03 12:00:00')
        ->and($hanRange->end?->date()->toDateTimeString())->toBe('2017-10-24 12:00:00');
});
