<?php

use DirectoryTree\Chrono\Chrono;

it('parses swedish weekday references', function () {
    $swedish = Chrono::sv();
    $monday = $swedish->parse('måndag', '2012-08-09')[0];
    $weekday = $swedish->parse('på onsdag', '2012-08-10')[0];
    $prefixedMonday = $swedish->parse('på måndag', '2012-08-09')[0];
    $nextMonday = $swedish->parse('nästa måndag', '2012-08-09')[0];
    $lastMonday = $swedish->parse('förra måndag', '2012-08-09')[0];

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('måndag')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($weekday->text)->toBe('på onsdag')
        ->and($weekday->start->tags())->toContain('parser/SVWeekdayParser')
        ->and($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($weekday->start->isCertain('day'))->toBeFalse()
        ->and($weekday->start->isCertain('weekday'))->toBeTrue()
        ->and($prefixedMonday->index)->toBe(0)
        ->and($prefixedMonday->text)->toBe('på måndag')
        ->and($prefixedMonday->start->get('year'))->toBe(2012)
        ->and($prefixedMonday->start->get('month'))->toBe(8)
        ->and($prefixedMonday->start->get('day'))->toBe(6)
        ->and($prefixedMonday->start->get('weekday'))->toBe(1)
        ->and($prefixedMonday->start->isCertain('year'))->toBeFalse()
        ->and($prefixedMonday->start->isCertain('month'))->toBeFalse()
        ->and($prefixedMonday->start->isCertain('day'))->toBeFalse()
        ->and($prefixedMonday->start->isCertain('weekday'))->toBeTrue()
        ->and($nextMonday->text)->toBe('nästa måndag')
        ->and($nextMonday->start->get('day'))->toBe(13)
        ->and($nextMonday->start->get('weekday'))->toBe(1)
        ->and($nextMonday->start->isCertain('day'))->toBeFalse()
        ->and($lastMonday->text)->toBe('förra måndag')
        ->and($lastMonday->start->get('day'))->toBe(6)
        ->and($lastMonday->start->get('weekday'))->toBe(1)
        ->and($lastMonday->start->isCertain('day'))->toBeFalse()
        ->and($swedish->parse('söndag', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($swedish->parse('tisdag', '2012-08-09')[0]->start->get('weekday'))->toBe(2)
        ->and($swedish->parse('fredag', '2012-08-09')[0]->start->get('weekday'))->toBe(5)
        ->and($swedish->parse('lördag', '2012-08-09')[0]->start->get('weekday'))->toBe(6)
        ->and($swedish->date('nästa måndag', '2012-08-10')?->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($swedish->date('förra måndag', '2012-08-10')?->toDateTimeString())->toBe('2012-08-06 12:00:00');
});
