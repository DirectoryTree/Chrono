<?php

use DirectoryTree\Chrono\Chrono;

it('parses german dash and dot numeric dates', function () {
    $german = Chrono::de();
    $dash = $german->parseText('30-12-16')[0];
    $prefixedDash = $german->parseText('Freitag 30-12-16')[0];
    $dot = $german->parseText('30.12.16')[0];
    $prefixedDot = $german->parseText('Freitag 30.12.16')[0];

    expect($dash->text)->toBe('30-12-16')
        ->and($dash->index)->toBe(0)
        ->and($dash->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($dash->start->get('year'))->toBe(2016)
        ->and($dash->start->get('month'))->toBe(12)
        ->and($dash->start->get('day'))->toBe(30)
        ->and($german->parseDateText('30-12-16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00')
        ->and($prefixedDash->text)
        ->toBe('Freitag 30-12-16')
        ->and($prefixedDash->index)->toBe(0)
        ->and($prefixedDash->start->tags())->toContain('parser/DEDashDateParser')
        ->and($prefixedDash->start->get('year'))->toBe(2016)
        ->and($prefixedDash->start->get('month'))->toBe(12)
        ->and($prefixedDash->start->get('day'))->toBe(30)
        ->and($german->parseDateText('Freitag 30-12-16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00')
        ->and($dot->text)->toBe('30.12.16')
        ->and($dot->start->tags())->toContain('parser/DEDashDateParser')
        ->and($dot->start->get('year'))->toBe(2016)
        ->and($dot->start->get('month'))->toBe(12)
        ->and($dot->start->get('day'))->toBe(30)
        ->and($german->parseDateText('30.12.16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00')
        ->and($prefixedDot->text)
        ->toBe('Freitag 30.12.16')
        ->and($prefixedDot->start->tags())->toContain('parser/DEDashDateParser')
        ->and($prefixedDot->start->get('year'))->toBe(2016)
        ->and($prefixedDot->start->get('month'))->toBe(12)
        ->and($prefixedDot->start->get('day'))->toBe(30)
        ->and($german->parseDateText('Freitag 30.12.16')?->toDateTimeString())
        ->toBe('2016-12-30 12:00:00');
});
