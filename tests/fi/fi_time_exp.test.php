<?php

use DirectoryTree\Chrono\Chrono;

it('parses finnish time expressions and ranges', function () {
    $finnish = Chrono::fi();
    $specific = $finnish->parseText('klo 15:00', '2012-08-10')[0];
    $kello = $finnish->parseText('kello 8:30', '2012-08-10')[0];
    $dotted = $finnish->parseText('klo 13.00', '2012-08-10')[0];
    $milliseconds = $finnish->parseText('klo 8:10:30.123', '2012-08-10')[0];
    $range = $finnish->parseText('klo 6:30 - 8:45', '2012-08-10')[0];
    $upstreamRange = $finnish->parseText('klo 10:00-12:00', '2012-08-10')[0];
    $compactRange = $finnish->parseText('klo 10:00-12:00', '2012-08-10')[0];
    $dateTime = $finnish->parseText('15 elokuuta 2012 klo 14:00', '2012-08-10')[0];

    expect($specific->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($specific->start->get('hour'))->toBe(15)
        ->and($specific->start->get('minute'))->toBe(0)
        ->and($specific->start->tags())->toContain('parser/FITimeExpressionParser')
        ->and($kello->start->date()->toDateTimeString())->toBe('2012-08-10 08:30:00')
        ->and($kello->start->get('hour'))->toBe(8)
        ->and($kello->start->get('minute'))->toBe(30)
        ->and($dotted->start->get('hour'))->toBe(13)
        ->and($dotted->start->get('minute'))->toBe(0)
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($finnish->parseText('Nähdään klo 6:13', '2012-08-10')[0]->text)
        ->toBe('klo 6:13')
        ->and($finnish->parseDateText('Nähdään klo 6:13', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:13:00')
        ->and($finnish->parseDateText('Nähdään kello 18.30', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:30:00')
        ->and($finnish->parseDateText('Nähdään klo 630', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:30:00')
        ->and($finnish->parseDateText('Nähdään klo 6pm', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:00:00')
        ->and($range->text)->toBe('klo 6:30 - 8:45')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($upstreamRange->start->get('hour'))->toBe(10)
        ->and($upstreamRange->start->get('minute'))->toBe(0)
        ->and($upstreamRange->end?->get('hour'))->toBe(12)
        ->and($upstreamRange->end?->get('minute'))->toBe(0)
        ->and($dateTime->start->get('year'))->toBe(2012)
        ->and($dateTime->start->get('month'))->toBe(8)
        ->and($dateTime->start->get('day'))->toBe(15)
        ->and($dateTime->start->get('hour'))->toBe(14)
        ->and($dateTime->start->get('minute'))->toBe(0)
        ->and($compactRange->text)->toBe('klo 10:00-12:00')
        ->and($compactRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($finnish->parseText('Vuosi 2020', '2012-08-10'))
        ->toBe([]);
});
