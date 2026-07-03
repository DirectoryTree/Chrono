<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Enums\Weekday;

it('parses portuguese slash dates', function () {
    $portuguese = Chrono::pt();
    $monday = $portuguese->parse('segunda 8/2/2016', '2012-08-10')[0];
    $tuesday = $portuguese->parse('Terça-feira 9/2/2016', '2012-08-10')[0];

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('segunda 8/2/2016')
        ->and($monday->start->get('year'))->toBe(2016)
        ->and($monday->start->get('month'))->toBe(2)
        ->and($monday->start->get('day'))->toBe(8)
        ->and($monday->start->get('weekday'))->toBe(Weekday::Monday->value)
        ->and($monday->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($tuesday->index)->toBe(0)
        ->and($tuesday->text)->toBe('Terça-feira 9/2/2016')
        ->and($tuesday->start->get('year'))->toBe(2016)
        ->and($tuesday->start->get('month'))->toBe(2)
        ->and($tuesday->start->get('day'))->toBe(9)
        ->and($tuesday->start->get('weekday'))->toBe(Weekday::Tuesday->value)
        ->and($tuesday->start->date()->toDateTimeString())->toBe('2016-02-09 12:00:00');
});
