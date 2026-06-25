<?php

use Chrono\Chrono;

it('parses vietnamese forward dates', function () {
    $result = Chrono::vi()->parseText('ngày 15 tháng 3', '2012-08-10 12:00', ['forwardDate' => true])[0];

    expect($result->start->date()->toDateTimeString())->toBe('2013-03-15 12:00:00')
        ->and($result->start->isCertain('year'))->toBeFalse();
});
