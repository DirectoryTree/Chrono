<?php

use Chrono\Chrono;

it('parses vietnamese later time unit expressions', function () {
    $result = Chrono::vi()->parseText('3 ngày sau', '2012-08-10 09:30')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-13 09:30:00');
});
