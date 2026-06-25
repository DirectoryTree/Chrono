<?php

use Chrono\Chrono;

it('parses vietnamese slash dates', function () {
    $slash = Chrono::vi()->parseText('Ngày 30/04/1975.', '2012-08-10')[0];
    $embeddedSlash = Chrono::vi()->parseText('Hội nghị 01/01/1954', '2012-08-10')[0];

    expect($slash->text)->toBe('30/04/1975')
        ->and($slash->start->date()->toDateTimeString())->toBe('1975-04-30 12:00:00')
        ->and($slash->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($embeddedSlash->text)->toBe('01/01/1954')
        ->and($embeddedSlash->start->date()->toDateTimeString())->toBe('1954-01-01 12:00:00');
});
