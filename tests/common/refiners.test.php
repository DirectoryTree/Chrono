<?php

use Carbon\CarbonImmutable;
use Chrono\Calculation\MergingCalculation;
use Chrono\Chrono;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiners\ExtractTimezoneAbbrRefiner;
use Chrono\Refiners\ForwardDateRefiner;
use Chrono\Weekday;

it('merges time followed by date in common date-time refiners', function () {
    $german = Chrono::german()->parseText('um 5 Uhr am 10. August 2012', '2012-08-10')[0];
    $dutch = Chrono::dutch()->parseText('om 5 uur 10 augustus 2012', '2012-08-10')[0];

    expect($german->text)
        ->toBe('um 5 Uhr am 10. August 2012')
        ->and($german->index)->toBe(0)
        ->and($german->date()->toDateTimeString())->toBe('2012-08-10 05:00:00')
        ->and($german->tags())->toContain('refiner/mergeTimeFollowedByDate')
        ->and($dutch->text)->toBe('om 5 uur 10 augustus 2012')
        ->and($dutch->index)->toBe(0)
        ->and($dutch->date()->toDateTimeString())->toBe('2012-08-10 05:00:00')
        ->and($dutch->tags())->toContain('refiner/mergeTimeFollowedByDate');
});

it('moves merged overnight time ranges to the next day like upstream helpers', function () {
    $date = new ParsedResult(0, 'Tuesday', new ParsedComponents(CarbonImmutable::parse('2022-08-23 12:00:00'), [
        'year' => 2022,
        'month' => 8,
        'day' => 23,
        'weekday' => Weekday::TUESDAY->value,
    ]));

    $time = new ParsedResult(
        8,
        '9pm - 1am',
        new ParsedComponents(CarbonImmutable::parse('2022-08-23 21:00:00'), [
            'hour' => 9,
            'minute' => 0,
            'meridiem' => Meridiem::PM->value,
        ]),
        new ParsedComponents(CarbonImmutable::parse('2022-08-23 01:00:00'), [
            'hour' => 1,
            'minute' => 0,
            'meridiem' => Meridiem::AM->value,
        ]),
    );

    $merged = MergingCalculation::mergeDateTimeResult($date, $time);

    expect($merged->start->date()->toDateTimeString())->toBe('2022-08-23 21:00:00')
        ->and($merged->end?->date()->toDateTimeString())->toBe('2022-08-24 01:00:00')
        ->and($merged->end?->isCertain('day'))->toBeTrue();
});

it('forwards same-day weekday components by a full week like upstream refiner', function () {
    $result = new ParsedResult(0, 'Friday', new ParsedComponents(CarbonImmutable::parse('2023-04-07 12:00:00'), [
        'weekday' => Weekday::FRIDAY->value,
    ]));

    $results = (new ForwardDateRefiner)->refine(
        'Friday',
        [$result],
        Reference::make('2023-04-07 13:00:00'),
        new Options(['forwardDate' => true]),
    );

    expect($results[0]->start->date()->toDateTimeString())->toBe('2023-04-14 12:00:00')
        ->and($results[0]->start->isCertain('weekday'))->toBeTrue()
        ->and($results[0]->start->isCertain('day'))->toBeFalse();
});

it('ignores lowercase timezone abbreviations when an implied offset conflicts like upstream refiner', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2023-04-07 12:00:00'));
    $components->imply('timezoneOffset', 240);

    $result = new ParsedResult(0, 'tomorrow', $components);

    $results = (new ExtractTimezoneAbbrRefiner)->refine(
        'tomorrow est',
        [$result],
        Reference::make('2023-04-06 12:00:00'),
        new Options,
    );

    expect($results[0]->text)->toBe('tomorrow')
        ->and($results[0]->start->get('timezoneOffset'))->toBe(240)
        ->and($results[0]->start->isCertain('timezoneOffset'))->toBeFalse();
});
