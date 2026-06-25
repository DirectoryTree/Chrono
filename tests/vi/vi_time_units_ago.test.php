<?php

use Chrono\Chrono;

it('parses vietnamese ago time unit expressions', function () {
    $result = Chrono::vi()->parseText('2 ngày trước', '2012-08-10 09:30')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00');
});
