<?php

use Chrono\Chrono;

it('parses ukrainian ago time unit expressions', function () {
    $ago = Chrono::uk()->parseText('2 дні тому', '2012-08-10 09:30')[0];

    expect($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/UKTimeUnitAgoFormatParser');
});
