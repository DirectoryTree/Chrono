<?php

use Chrono\Chrono;

it('parses simplified chinese time expressions', function () {
    $time = Chrono::zhHans()->parseText('下午3点半到5点', '2012-08-10')[0];

    expect($time->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($time->start->tags())->toContain('parser/ZHHansTimeExpressionParser')
        ->and($time->end?->date()->toDateTimeString())->toBe('2012-08-10 17:00:00');
});
