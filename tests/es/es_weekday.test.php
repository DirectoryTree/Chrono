<?php

use DirectoryTree\Chrono\Chrono;

it('parses spanish weekdays', function () {
    $spanish = Chrono::es();
    $thursday = $spanish->parse('jueves', '2012-08-10 12:00')[0];
    $friday = $spanish->parse('viernes', '2012-08-10 12:00')[0];

    expect($thursday->text)->toBe('jueves')
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->date()->toDateTimeString())->toBe('2012-08-09 00:00:00')
        ->and($thursday->start->tags())->toContain('parser/ESWeekdayParser')
        ->and($friday->text)->toBe('viernes')
        ->and($friday->start->get('weekday'))->toBe(5)
        ->and($friday->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($spanish->date('viernes', '2012-08-10 12:00', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2012-08-17 00:00:00')
        ->and($spanish->date('próximo viernes', '2012-08-10 12:00')?->toDateTimeString())
        ->toBe('2012-08-17 00:00:00');
});
