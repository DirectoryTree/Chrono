<?php

use DirectoryTree\Chrono\Chrono;

it('parses russian within time unit expressions', function () {
    $within = Chrono::ru()->parseText('в течение 1 месяца', '2012-08-10 09:30')[0];
    $withinMinute = Chrono::ru()->parseText('будет сделано в течение минуты', '2012-08-10 00:00')[0];
    $withinHours = Chrono::ru()->parseText('будет сделано в течение 2 часов.', '2012-08-10 00:00')[0];

    expect($within->start->date()->toDateTimeString())->toBe('2012-09-10 09:30:00')
        ->and($within->start->tags())->toContain('parser/RUTimeUnitWithinFormatParser')
        ->and($within->start->isCertain('month'))->toBeTrue()
        ->and($within->start->isCertain('day'))->toBeFalse()
        ->and($withinMinute->index)->toBe(14)
        ->and($withinMinute->text)->toBe('в течение минуты')
        ->and($withinMinute->start->date()->toDateTimeString())->toBe('2012-08-10 00:01:00')
        ->and($withinMinute->start->tags())->toContain('result/relativeDateAndTime')
        ->and($withinHours->index)->toBe(14)
        ->and($withinHours->text)->toBe('в течение 2 часов')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 02:00:00');
});
