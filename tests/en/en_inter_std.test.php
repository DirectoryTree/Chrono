<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\ParsedResult;

it('parses iso dates into carbon instances', function () {
    $result = Chrono::parse('Ship on 2026-06-23 14:30')[0];

    expect($result)->toBeInstanceOf(ParsedResult::class)
        ->and($result->text)->toBe('2026-06-23 14:30')
        ->and($result->start->date())->toBeInstanceOf(CarbonImmutable::class)
        ->and($result->start->date()->toDateTimeString())->toBe('2026-06-23 14:30:00')
        ->and($result->tags())->toContain('parser/ISOFormatParser')
        ->and($result->tags())->toContain('parser/ENTimeExpressionParser')
        ->and($result->tags())->toContain('refiner/mergeDateFollowedByTime');
});

it('parses native javascript and rfc date strings', function () {
    $positiveOffset = Chrono::parse('1994-11-05T08:15:30+11:30')[0];
    $negativeOffset = Chrono::parse('2014-11-30T08:15:30-05:30')[0];
    $rfc = Chrono::parse('Sat, 21 Feb 2015 11:50:48 -0500')[0];
    $utcOffset = Chrono::parse('22 Feb 2015 04:12:00 -0000')[0];
    $slash = Chrono::parse('09/25/2017 10:31:50.522 PM')[0];
    $javascript = Chrono::parse('Sat Nov 05 1994 22:45:30 GMT+0900 (JST)')[0];
    $utcName = Chrono::parse('Fri, 31 Mar 2000 07:00:00 UTC')[0];

    expect($positiveOffset->text)->toBe('1994-11-05T08:15:30+11:30')
        ->and($positiveOffset->start->timezoneOffset())->toBe(690)
        ->and($positiveOffset->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 08:15:30 +11:30')
        ->and($negativeOffset->text)->toBe('2014-11-30T08:15:30-05:30')
        ->and($negativeOffset->start->timezoneOffset())->toBe(-330)
        ->and($negativeOffset->start->date()->format('Y-m-d H:i:s P'))->toBe('2014-11-30 08:15:30 -05:30')
        ->and($rfc->text)->toBe('Sat, 21 Feb 2015 11:50:48 -0500')
        ->and($rfc->start->timezoneOffset())->toBe(-300)
        ->and($rfc->start->date()->format('Y-m-d H:i:s P'))->toBe('2015-02-21 11:50:48 -05:00')
        ->and($rfc->tags())->toContain('parser/NativeDateFormatParser')
        ->and($utcOffset->text)->toBe('22 Feb 2015 04:12:00 -0000')
        ->and($utcOffset->start->timezoneOffset())->toBe(0)
        ->and($utcOffset->start->date()->format('Y-m-d H:i:s P'))->toBe('2015-02-22 04:12:00 +00:00')
        ->and($utcOffset->tags())->toContain('parser/NativeDateFormatParser')
        ->and($slash->text)->toBe('09/25/2017 10:31:50.522 PM')
        ->and($slash->start->get('millisecond'))->toBe(522)
        ->and($slash->start->date()->format('Y-m-d H:i:s.v'))->toBe('2017-09-25 22:31:50.522')
        ->and($slash->tags())->toContain('parser/NativeDateFormatParser')
        ->and($javascript->text)->toBe('Sat Nov 05 1994 22:45:30 GMT+0900 (JST)')
        ->and($javascript->start->timezoneOffset())->toBe(540)
        ->and($javascript->start->date()->format('Y-m-d H:i:s P'))->toBe('1994-11-05 22:45:30 +09:00')
        ->and($javascript->tags())->toContain('parser/NativeDateFormatParser')
        ->and($utcName->text)->toBe('Fri, 31 Mar 2000 07:00:00 UTC')
        ->and($utcName->start->timezoneOffset())->toBe(0)
        ->and($utcName->start->date()->format('Y-m-d H:i:s P'))->toBe('2000-03-31 07:00:00 +00:00')
        ->and($utcName->tags())->toContain('parser/NativeDateFormatParser');
});
