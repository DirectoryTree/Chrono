<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Weekdays;
use DirectoryTree\Chrono\Enums\Weekday;
use DirectoryTree\Chrono\Reference;

it('calculates weekdays like upstream helpers', function () {
    $saturday = CarbonImmutable::parse('2022-08-20 12:00:00');
    $sunday = CarbonImmutable::parse('2022-08-21 12:00:00');
    $tuesday = CarbonImmutable::parse('2022-08-02 12:00:00');

    expect(Weekdays::getDaysToWeekday($saturday, Weekday::Monday, 'this'))->toBe(2)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::Friday, 'this'))->toBe(5)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::Sunday, 'this'))->toBe(5)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Friday, 'last'))->toBe(-1)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Monday, 'last'))->toBe(-5)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Sunday, 'last'))->toBe(-6)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Saturday, 'last'))->toBe(-7)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::Monday, 'next'))->toBe(1)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::Saturday, 'next'))->toBe(6)
        ->and(Weekdays::getDaysToWeekday($sunday, Weekday::Sunday, 'next'))->toBe(7)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Saturday, 'next'))->toBe(7)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Sunday, 'next'))->toBe(8)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::Monday, 'next'))->toBe(6)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::Friday, 'next'))->toBe(10)
        ->and(Weekdays::getDaysToWeekday($tuesday, Weekday::Sunday, 'next'))->toBe(12)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Monday))->toBe(2)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Tuesday))->toBe(3)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Friday))->toBe(-1)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Thursday))->toBe(-2)
        ->and(Weekdays::getDaysToWeekday($saturday, Weekday::Wednesday))->toBe(-3);
});

it('creates weekday components like upstream helpers', function () {
    $reference = Reference::make('2022-08-20 12:00:00');
    $components = Weekdays::createParsingComponentsAtWeekday($reference, Weekday::Monday, 'this');
    $jstReference = Reference::make([
        'instant' => '2025-02-27T17:00:00.000Z',
        'timezone' => 'JST',
    ]);
    $pstReference = Reference::make([
        'instant' => '2025-02-27T17:00:00.000Z',
        'timezone' => 'PST',
    ]);
    $jstFriday = Weekdays::createParsingComponentsAtWeekday($jstReference, Weekday::Friday, 'this');
    $pstFriday = Weekdays::createParsingComponentsAtWeekday($pstReference, Weekday::Friday, 'this');

    expect($components->date()->toDateTimeString())->toBe('2022-08-22 12:00:00')
        ->and($components->get('weekday'))->toBe(Weekday::Monday->value)
        ->and($components->isCertain('weekday'))->toBeTrue()
        ->and($components->isCertain('day'))->toBeFalse()
        ->and($jstFriday->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 12:00:00 +09:00')
        ->and($pstFriday->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 12:00:00 -08:00');
});
