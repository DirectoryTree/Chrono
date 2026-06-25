<?php

use Chrono\Chrono;

it('parses vietnamese within time unit expressions', function () {
    $result = Chrono::vi()->parseText('trong 5 ngày', '2012-08-10')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-15 00:00:00');
});
