<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Weekdays;
use DirectoryTree\Chrono\Reference;
use DirectoryTree\Chrono\Weekday;

it('calculates weekdays like upstream helpers', function () {
    $saturday = CarbonImmutable::parse('2022-08-20 12:00:00');
    $sunday = CarbonImmutable::parse('2022-08-21 12:00:00');
    $tuesday = CarbonImmutable::parse('2022-08-02 12:00:00');

    expect(Weekdays::getDaysToWeekday($saturday, Weekday::MONDAY, 'this'))->toBe(2)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::FRIDAY, 'this'))->toBe(5)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::SUNDAY, 'this'))->toBe(5)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::FRIDAY, 'last'))->toBe(-1)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::MONDAY, 'last'))->toBe(-5)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SUNDAY, 'last'))->toBe(-6)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SATURDAY, 'last'))->toBe(-7)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::MONDAY, 'next'))->toBe(1)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::SATURDAY, 'next'))->toBe(6)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::SUNDAY, 'next'))->toBe(7)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SATURDAY, 'next'))->toBe(7)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::SUNDAY, 'next'))->toBe(8)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::MONDAY, 'next'))->toBe(6)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::FRIDAY, 'next'))->toBe(10)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::SUNDAY, 'next'))->toBe(12)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::MONDAY))->toBe(2)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::TUESDAY))->toBe(3)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::FRIDAY))->toBe(-1)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::THURSDAY))->toBe(-2)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::WEDNESDAY))->toBe(-3);
});

it('creates weekday components like upstream helpers', function () {
    $reference = Reference::make('2022-08-20 12:00:00');
    $components = Weekdays::createParsingComponentsAtWeekday($reference, Weekday::MONDAY, 'this');
    $jstReference = Reference::make([
        'instant' => '2025-02-27T17:00:00.000Z',
        'timezone' => 'JST',
    ]);
    $pstReference = Reference::make([
        'instant' => '2025-02-27T17:00:00.000Z',
        'timezone' => 'PST',
    ]);
    $jstFriday = Weekdays::createParsingComponentsAtWeekday($jstReference, Weekday::FRIDAY, 'this');
    $pstFriday = Weekdays::createParsingComponentsAtWeekday($pstReference, Weekday::FRIDAY, 'this');

    expect($components->date()->toDateTimeString())->toBe('2022-08-22 12:00:00')
        ->and($components->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($components->isCertain('weekday'))->toBeTrue()
        ->and($components->isCertain('day'))->toBeFalse()
        ->and($jstFriday->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 12:00:00 +09:00')
        ->and($pstFriday->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 12:00:00 -08:00');
});
