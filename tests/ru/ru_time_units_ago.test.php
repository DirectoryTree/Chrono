<?php

use Chrono\Chrono;

it('parses russian ago time unit expressions', function () {
    $ago = Chrono::ru()->parseText('2 дня назад', '2012-08-10 09:30')[0];
    $halfHourAgo = Chrono::ru()->parseText('полчаса назад что-то было', '2012-07-10 00:00')[0];

    expect($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/RUTimeUnitAgoFormatParser')
        ->and($halfHourAgo->index)->toBe(0)
        ->and($halfHourAgo->text)->toBe('полчаса назад')
        ->and($halfHourAgo->start->date()->toDateTimeString())->toBe('2012-07-09 23:30:00');
});
