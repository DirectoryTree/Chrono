<?php

use Chrono\Chrono;
use Chrono\Weekday;

it('merges relative durations before and after parsed dates', function () {
    $afterYesterday = Chrono::parse('2 weeks after yesterday', '2022-02-02 00:00')[0];
    $beforeSlashDate = Chrono::parse('2 months before 02/02', '2022-02-02 12:00')[0];
    $afterWeekday = Chrono::parse('2 days after next Friday', '2022-02-02 12:00')[0];

    expect($afterYesterday->text)->toBe('2 weeks after yesterday')
        ->and($afterYesterday->start->date()->toDateTimeString())->toBe('2022-02-15 00:00:00')
        ->and($afterYesterday->start->get('year'))->toBe(2022)
        ->and($afterYesterday->start->get('month'))->toBe(2)
        ->and($afterYesterday->start->get('day'))->toBe(15)
        ->and($afterYesterday->start->get('weekday'))->toBe(Weekday::TUESDAY->value)
        ->and($afterYesterday->start->isCertain('year'))->toBeTrue()
        ->and($afterYesterday->start->isCertain('month'))->toBeTrue()
        ->and($afterYesterday->start->isCertain('day'))->toBeTrue()
        ->and($afterYesterday->tags())->toContain('refiner/mergeRelativeFollowByDate')
        ->and($beforeSlashDate->text)->toBe('2 months before 02/02')
        ->and($beforeSlashDate->start->date()->toDateTimeString())->toBe('2021-12-02 12:00:00')
        ->and($beforeSlashDate->start->get('year'))->toBe(2021)
        ->and($beforeSlashDate->start->get('month'))->toBe(12)
        ->and($beforeSlashDate->start->get('day'))->toBe(2)
        ->and($beforeSlashDate->start->isCertain('year'))->toBeTrue()
        ->and($beforeSlashDate->start->isCertain('month'))->toBeTrue()
        ->and($beforeSlashDate->start->isCertain('day'))->toBeFalse()
        ->and($beforeSlashDate->tags())->toContain('refiner/mergeRelativeFollowByDate')
        ->and($afterWeekday->text)->toBe('2 days after next Friday')
        ->and($afterWeekday->start->date()->toDateTimeString())->toBe('2022-02-13 12:00:00')
        ->and($afterWeekday->start->get('year'))->toBe(2022)
        ->and($afterWeekday->start->get('month'))->toBe(2)
        ->and($afterWeekday->start->get('day'))->toBe(13)
        ->and($afterWeekday->start->isCertain('year'))->toBeTrue()
        ->and($afterWeekday->start->isCertain('month'))->toBeTrue()
        ->and($afterWeekday->start->isCertain('day'))->toBeTrue()
        ->and($afterWeekday->tags())->toContain('refiner/mergeRelativeFollowByDate');
});
