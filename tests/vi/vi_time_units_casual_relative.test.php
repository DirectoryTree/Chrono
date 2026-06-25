<?php

use Chrono\Chrono;

it('parses vietnamese casual relative time unit expressions', function () {
    $result = Chrono::vi()->parseText('tuần sau', '2012-08-10 09:30')[0];

    expect($result->start->date()->toDateTimeString())->toBe('2012-08-17 09:30:00');
});
