<?php

use Chrono\Chrono;

it('parses ukrainian within time unit expressions', function () {
    $within = Chrono::uk()->parseText('протягом 1 місяця', '2012-08-10 09:30')[0];
    $withinMinute = Chrono::uk()->parseText('буде зроблено протягом хвилини', '2012-08-10 00:00')[0];
    $withinHours = Chrono::uk()->parseText('буде виконано на протязі 2 годин.', '2012-08-10 00:00')[0];

    expect($within->start->date()->toDateTimeString())->toBe('2012-09-10 09:30:00')
        ->and($within->start->tags())->toContain('parser/UKTimeUnitWithinFormatParser')
        ->and($within->start->isCertain('month'))->toBeTrue()
        ->and($within->start->isCertain('day'))->toBeFalse()
        ->and($withinMinute->index)->toBe(26)
        ->and($withinMinute->text)->toBe('протягом хвилини')
        ->and($withinMinute->start->date()->toDateTimeString())->toBe('2012-08-10 00:01:00')
        ->and($withinMinute->start->tags())->toContain('result/relativeDateAndTime')
        ->and($withinHours->index)->toBe(26)
        ->and($withinHours->text)->toBe('на протязі 2 годин')
        ->and($withinHours->start->date()->toDateTimeString())->toBe('2012-08-10 02:00:00');
});
