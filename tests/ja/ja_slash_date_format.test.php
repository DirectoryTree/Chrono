<?php

use DirectoryTree\Chrono\Chrono;

it('parses japanese slash dates', function () {
    $japanese = Chrono::ja();
    $slash = $japanese->parseText('2020/7/12', '2012-08-10')[0];
    $fullSlash = $japanese->parseText('2012/3/31', '2012-08-10')[0];
    $monthDaySlash = $japanese->parseText('12/31', '2012-08-10')[0];
    $earlyMonthDaySlash = $japanese->parseText('8/5', '2012-08-10')[0];
    $slashDateTime = $japanese->parseText('12/9の16:00', '2025-12-10 12:00')[0];
    $fullWidth = $japanese->parseText('２０２０／７／１２', '2012-08-10')[0];
    $slashRange = $japanese->parseText('2013/12/26~2014/1/7', '2012-08-10')[0];

    expect($slash->start->date()->toDateTimeString())->toBe('2020-07-12 12:00:00')
        ->and($slash->start->tags())->toContain('parser/JPSlashDateFormatParser')
        ->and($fullSlash->index)->toBe(0)
        ->and($fullSlash->text)->toBe('2012/3/31')
        ->and($fullSlash->start->get('year'))->toBe(2012)
        ->and($fullSlash->start->get('month'))->toBe(3)
        ->and($fullSlash->start->get('day'))->toBe(31)
        ->and($fullSlash->start->date()->toDateTimeString())->toBe('2012-03-31 12:00:00')
        ->and($monthDaySlash->index)->toBe(0)
        ->and($monthDaySlash->text)->toBe('12/31')
        ->and($monthDaySlash->start->get('year'))->toBe(2012)
        ->and($monthDaySlash->start->get('month'))->toBe(12)
        ->and($monthDaySlash->start->get('day'))->toBe(31)
        ->and($monthDaySlash->start->date()->toDateTimeString())->toBe('2012-12-31 12:00:00')
        ->and($earlyMonthDaySlash->index)->toBe(0)
        ->and($earlyMonthDaySlash->text)->toBe('8/5')
        ->and($earlyMonthDaySlash->start->get('year'))->toBe(2012)
        ->and($earlyMonthDaySlash->start->get('month'))->toBe(8)
        ->and($earlyMonthDaySlash->start->get('day'))->toBe(5)
        ->and($earlyMonthDaySlash->start->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($slashDateTime->text)->toBe('12/9の16:00')
        ->and($slashDateTime->start->date()->toDateTimeString())->toBe('2025-12-09 16:00:00')
        ->and($slashDateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($fullWidth->start->date()->toDateTimeString())->toBe('2020-07-12 12:00:00')
        ->and($slashRange->text)->toBe('2013/12/26~2014/1/7')
        ->and($slashRange->index)->toBe(0)
        ->and($slashRange->start->get('year'))->toBe(2013)
        ->and($slashRange->start->get('month'))->toBe(12)
        ->and($slashRange->start->get('day'))->toBe(26)
        ->and($slashRange->start->date()->toDateTimeString())->toBe('2013-12-26 12:00:00')
        ->and($slashRange->end?->get('year'))->toBe(2014)
        ->and($slashRange->end?->get('month'))->toBe(1)
        ->and($slashRange->end?->get('day'))->toBe(7)
        ->and($slashRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00');
});
