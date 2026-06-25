<?php

use Chrono\Chrono;

it('parses vietnamese time expressions', function () {
    $time = Chrono::vi()->parseText('7 giờ 30 phút', '2012-08-10')[0];

    expect($time->start->get('hour'))->toBe(7)
        ->and($time->start->get('minute'))->toBe(30);
});
