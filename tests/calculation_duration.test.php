<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Duration;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\Reference;

it('calculates durations like upstream helpers', function () {
    $reference = CarbonImmutable::parse('2022-08-27 12:52:11.000');

    expect(Duration::add($reference, ['year' => 1])->toDateTimeString())->toBe('2023-08-27 12:52:11')
        ->and(Duration::add($reference, ['month' => 1])->toDateTimeString())->toBe('2022-09-27 12:52:11')
        ->and(Duration::add($reference, ['week' => 1])->toDateTimeString())->toBe('2022-09-03 12:52:11')
        ->and(Duration::add($reference, ['day' => 1])->toDateTimeString())->toBe('2022-08-28 12:52:11')
        ->and(Duration::add($reference, ['hour' => 1])->toDateTimeString())->toBe('2022-08-27 13:52:11')
        ->and(Duration::add($reference, ['minute' => 1])->toDateTimeString())->toBe('2022-08-27 12:53:11')
        ->and(Duration::add($reference, ['second' => 1])->toDateTimeString())->toBe('2022-08-27 12:52:12')
        ->and(Duration::add($reference, ['millisecond' => 1])->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.001')
        ->and(Duration::add($reference, ['y' => 1])->toDateTimeString())->toBe('2023-08-27 12:52:11')
        ->and(Duration::add($reference, ['M' => 1])->toDateTimeString())->toBe('2022-09-27 12:52:11')
        ->and(Duration::add($reference, ['w' => 1])->toDateTimeString())->toBe('2022-09-03 12:52:11')
        ->and(Duration::add($reference, ['d' => 1])->toDateTimeString())->toBe('2022-08-28 12:52:11')
        ->and(Duration::add($reference, ['h' => 1])->toDateTimeString())->toBe('2022-08-27 13:52:11')
        ->and(Duration::add($reference, ['m' => 1])->toDateTimeString())->toBe('2022-08-27 12:53:11')
        ->and(Duration::add($reference, ['s' => 1])->toDateTimeString())->toBe('2022-08-27 12:52:12')
        ->and(Duration::add($reference, ['ms' => 1])->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.001')
        ->and(Duration::add($reference, ['month' => 1, 'day' => 4])->toDateTimeString())->toBe('2022-10-01 12:52:11')
        ->and(Duration::add($reference, ['month' => 1, 'day' => 4, 'hour' => 12])->toDateTimeString())->toBe('2022-10-02 00:52:11')
        ->and(Duration::add($reference, ['year' => 0.5])->toDateTimeString())->toBe('2023-02-27 12:52:11')
        ->and(Duration::add($reference, ['month' => 0.5])->toDateTimeString())->toBe('2022-09-10 12:52:11')
        ->and(Duration::add($reference, ['week' => 0.5])->toDateTimeString())->toBe('2022-08-31 12:52:11')
        ->and(Duration::add($reference, ['day' => 0.5])->toDateTimeString())->toBe('2022-08-28 00:52:11')
        ->and(Duration::add($reference, ['hour' => 0.5])->toDateTimeString())->toBe('2022-08-27 13:22:11')
        ->and(Duration::add($reference, ['minute' => 0.5])->toDateTimeString())->toBe('2022-08-27 12:52:41')
        ->and(Duration::add($reference, ['second' => 0.5])->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.500')
        ->and(Duration::add($reference, ['year' => 0.5, 'month' => 2])->toDateTimeString())->toBe('2023-04-27 12:52:11')
        ->and(Duration::reverse(['year' => 5, 'month' => -5]))->toBe(['year' => -5, 'month' => 5])
        ->and(Duration::EMPTY)->toBe(['day' => 0, 'second' => 0, 'millisecond' => 0]);
});

it('adds durations as implied parsed component values', function () {
    $time = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);

    $time->addDurationAsImplied(['hour' => 3]);

    $date = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);

    $date->addDurationAsImplied(['day' => 3]);

    $millisecond = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11.400'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
        'millisecond' => true,
    ]);

    $millisecond->addDurationAsImplied(['millisecond' => 250]);

    expect($time->date()->toDateTimeString())->toBe('2022-08-27 15:52:11')
        ->and($time->get('hour'))->toBe(15)
        ->and($time->isCertain('hour'))->toBeFalse()
        ->and($time->isCertain('minute'))->toBeFalse()
        ->and($time->isCertain('second'))->toBeFalse()
        ->and($time->isCertain('day'))->toBeTrue()
        ->and($date->date()->toDateTimeString())->toBe('2022-08-30 12:52:11')
        ->and($date->get('day'))->toBe(30)
        ->and($date->isCertain('day'))->toBeFalse()
        ->and($date->isCertain('weekday'))->toBeFalse()
        ->and($date->isCertain('month'))->toBeFalse()
        ->and($date->isCertain('year'))->toBeFalse()
        ->and($date->isCertain('hour'))->toBeTrue()
        ->and($millisecond->date()->format('Y-m-d H:i:s.v'))->toBe('2022-08-27 12:52:11.400')
        ->and($millisecond->get('millisecond'))->toBe(400)
        ->and($millisecond->isCertain('millisecond'))->toBeTrue();
});

it('adds fractional durations using upstream duration cascading', function () {
    $halfYear = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfYear->addDurationAsImplied(['year' => 0.5]);

    $halfMonth = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfMonth->addDurationAsImplied(['month' => 0.5]);

    $halfWeek = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfWeek->addDurationAsImplied(['week' => 0.5]);

    $halfHour = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $halfHour->addDurationAsImplied(['hour' => 0.5]);

    $shortMonth = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:52:11'), [
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
    ]);
    $shortMonth->addDurationAsImplied(['M' => 1]);

    expect($halfYear->date()->toDateTimeString())->toBe('2023-02-27 12:52:11')
        ->and($halfYear->isCertain('month'))->toBeFalse()
        ->and($halfMonth->date()->toDateTimeString())->toBe('2022-09-10 12:52:11')
        ->and($halfMonth->isCertain('day'))->toBeFalse()
        ->and($halfWeek->date()->toDateTimeString())->toBe('2022-08-31 12:52:11')
        ->and($halfWeek->get('day'))->toBe(31)
        ->and($halfHour->date()->toDateTimeString())->toBe('2022-08-27 13:22:11')
        ->and($halfHour->get('minute'))->toBe(22)
        ->and($halfHour->isCertain('minute'))->toBeFalse()
        ->and($shortMonth->date()->toDateTimeString())->toBe('2022-09-27 12:52:11');
});

it('adds durations as implied values using timezone-adjusted references', function () {
    $jstReference = Reference::make([
        'instant' => 'Thu Feb 27 2025 17:00:00 GMT+0000',
        'timezone' => 'JST',
    ]);
    $jst = ParsedComponents::createRelativeFromReference($jstReference);

    $pstReference = Reference::make([
        'instant' => 'Thu Feb 27 2025 17:00:00 GMT+0000',
        'timezone' => 'PST',
    ]);
    $pst = ParsedComponents::createRelativeFromReference($pstReference);

    $jst->addDurationAsImplied(['hour' => 3]);
    $pst->addDurationAsImplied(['hour' => 3]);

    expect($jstReference->date->format('Y-m-d H:i:s P'))->toBe('2025-02-28 02:00:00 +09:00')
        ->and($jst->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-28 05:00:00 +09:00')
        ->and($jst->isCertain('hour'))->toBeFalse()
        ->and($pstReference->date->format('Y-m-d H:i:s P'))->toBe('2025-02-27 09:00:00 -08:00')
        ->and($pst->date()->format('Y-m-d H:i:s P'))->toBe('2025-02-27 12:00:00 -08:00')
        ->and($pst->isCertain('hour'))->toBeFalse();

    $jst->addDurationAsImplied(['day' => 3]);
    $pst->addDurationAsImplied(['day' => 3]);

    expect($jst->date()->format('Y-m-d H:i:s P'))->toBe('2025-03-03 05:00:00 +09:00')
        ->and($jst->isCertain('day'))->toBeFalse()
        ->and($pst->date()->format('Y-m-d H:i:s P'))->toBe('2025-03-02 12:00:00 -08:00')
        ->and($pst->isCertain('day'))->toBeFalse();
});
