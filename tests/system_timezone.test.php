<?php

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Chrono\Chrono;
use Chrono\Month;
use Chrono\Reference;
use Chrono\Timezone;
use Chrono\Weekday;

it('gets timezone DST boundary dates like upstream helpers', function () {
    $secondSunday = Timezone::getNthWeekdayOfMonth(2022, Month::MARCH, Weekday::SUNDAY, 2, 2);
    $firstSunday = Timezone::getNthWeekdayOfMonth(2022, Month::NOVEMBER, Weekday::SUNDAY, 1, 2);
    $lastSunday = Timezone::getLastWeekdayOfMonth(2022, Month::OCTOBER, Weekday::SUNDAY, 3);
    $lastFriday = Timezone::getLastWeekdayOfMonth(2024, 2, 5, 9);

    expect($secondSunday->toDateTimeString())->toBe('2022-03-13 02:00:00')
        ->and($firstSunday->toDateTimeString())->toBe('2022-11-06 02:00:00')
        ->and($lastSunday->toDateTimeString())->toBe('2022-10-30 03:00:00')
        ->and($lastFriday->toDateTimeString())->toBe('2024-02-23 09:00:00');
});

it('exposes timezone-adjusted reference dates like upstream references', function () {
    $plain = Reference::make('Wed Jun 09 2021 07:21:32 GMT+0900');
    $numeric = Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT+0900',
        'timezone' => 540,
    ]);
    $named = Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT+0900',
        'timezone' => 'JST',
    ]);
    $adjusted = Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT-0500',
        'timezone' => 'CDT',
    ]);

    expect($plain->timezoneOffset)->toBeNull()
        ->and($plain->instant->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 +09:00')
        ->and($plain->getSystemTimezoneAdjustmentMinute($plain->instant))->toBe(0)
        ->and($plain->getTimezoneOffset())->toBe(540)
        ->and($numeric->timezoneOffset)->toBe(540)
        ->and($numeric->instant->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 +09:00')
        ->and($numeric->getSystemTimezoneAdjustmentMinute($numeric->instant))->toBe(0)
        ->and($numeric->getSystemTimezoneAdjustmentMinute($numeric->instant, -300))->toBe(840)
        ->and($numeric->getDateWithAdjustedTimezone()->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 +09:00')
        ->and($numeric->getTimezoneOffset())->toBe(540)
        ->and($named->timezoneOffset)->toBe(540)
        ->and($named->getDateWithAdjustedTimezone()->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 +09:00')
        ->and($named->getTimezoneOffset())->toBe(540)
        ->and($adjusted->timezoneOffset)->toBe(-300)
        ->and($adjusted->instant->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 -05:00')
        ->and($adjusted->getDateWithAdjustedTimezone()->format('Y-m-d H:i:s P'))->toBe('2021-06-09 07:21:32 -05:00')
        ->and($adjusted->getTimezoneOffset())->toBe(-300);
});

it('uses dutch relative reference timezones like upstream', function () {
    $dutch = Chrono::nl();
    $reference = 'Sun Nov 29 2020 13:24:13 GMT+0900 (Japan Standard Time)';
    $now = $dutch->parseText('nu', $reference)[0];
    $within = $dutch->parseText('binnen 10 minuten', $reference)[0];
    $jst = $dutch->parseText('morgen om 17 uur', [
        'instant' => $reference,
        'timezone' => 'JST',
    ])[0];
    $pdt = $dutch->parseText('morgen om 17 uur', [
        'instant' => $reference,
        'timezone' => -420,
    ])[0];

    $now->start->imply('timezoneOffset', 60);
    $within->start->imply('timezoneOffset', 60);

    expect($now->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2020-11-29 13:24:13 +09:00')
        ->and($within->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2020-11-29 13:34:13 +09:00')
        ->and($jst->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2020-11-30 17:00:00 +09:00')
        ->and($jst->start->get('day'))->toBe(30)
        ->and($pdt->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2020-11-29 17:00:00 -07:00')
        ->and($pdt->start->get('day'))->toBe(29);

    $jst->start->imply('timezoneOffset', 60);
    $pdt->start->imply('timezoneOffset', 60);

    expect($jst->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2020-11-30 17:00:00 +01:00')
        ->and($pdt->start->date()->format('Y-m-d H:i:s P'))
        ->toBe('2020-11-29 17:00:00 +01:00');
});

it('parses custom ambiguous timezone abbreviations from options', function () {
    $timezone = [
        'timezoneOffsetDuringDst' => -120,
        'timezoneOffsetNonDst' => -180,
        'dstStart' => fn (int $year): CarbonInterface => CarbonImmutable::create($year, 3, 26, 2),
        'dstEnd' => fn (int $year): CarbonInterface => CarbonImmutable::create($year, 10, 29, 3),
    ];

    $standard = Chrono::parse('Jan 1st 2023 at 10:00 XYZ', '2023-01-01', [
        'timezones' => ['XYZ' => $timezone],
    ])[0];
    $daylight = Chrono::parse('Jun 1st 2023 at 10:00 XYZ', '2023-01-01', [
        'timezones' => ['XYZ' => $timezone],
    ])[0];

    expect($standard->start->timezoneOffset())->toBe(-180)
        ->and($standard->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-01-01 10:00:00 -03:00')
        ->and($daylight->start->timezoneOffset())->toBe(-120)
        ->and($daylight->start->date()->format('Y-m-d H:i:s P'))->toBe('2023-06-01 10:00:00 -02:00');
});

it('keeps relative instants stable across timezone reference settings like upstream', function () {
    $reference = 'Sun Nov 29 2020 13:24:13 GMT+0900 (Japan Standard Time)';
    $expected = CarbonImmutable::parse('2020-11-29 14:24:13 +09:00');

    $relative = Chrono::parse('in 1 hour get eggs and milk', $reference)[0];
    $gmt = Chrono::parse('in 1 hour GMT', $reference)[0];
    $jst = Chrono::parse('in 1 hour GMT', ['instant' => $reference, 'timezone' => 'JST'])[0];
    $bst = Chrono::parse('in 1 hour GMT', ['instant' => $reference, 'timezone' => 'BST'])[0];

    expect($relative->text)->toBe('in 1 hour')
        ->and($relative->start->timezoneOffset())->toBe(540)
        ->and($relative->start->date()->getTimestamp())->toBe($expected->getTimestamp())
        ->and($gmt->start->date()->getTimestamp())->toBe($expected->getTimestamp())
        ->and($jst->start->date()->getTimestamp())->toBe($expected->getTimestamp())
        ->and($bst->start->date()->getTimestamp())->toBe($expected->getTimestamp());
});

it('uses upstream reference timezone defaults and overrides', function () {
    expect(Chrono::parseDate('Friday at 4pm', '2021-06-09 07:00:00')?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00')
        ->and(Chrono::parseDate('Friday at 4pm', ['instant' => '2021-06-09 07:00:00'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00')
        ->and(Chrono::parseDate('Friday at 4pm', ['instant' => '2021-06-09 07:00:00', 'timezone' => null])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00')
        ->and(Chrono::parseDate('Friday at 4pm', ['instant' => '2021-06-09 07:00:00', 'timezone' => ''])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-11 16:00:00 +00:00');

    $jstInstant = 'Sun Jun 06 2021 19:00:00 GMT+0900 (JST)';

    expect(Chrono::parseDate('At 4pm tomorrow', ['instant' => $jstInstant, 'timezone' => 'BST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-07 16:00:00 +01:00')
        ->and(Chrono::parseDate('At 4pm tomorrow', ['instant' => $jstInstant, 'timezone' => 'JST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-07 16:00:00 +09:00')
        ->and(Chrono::parseDate('At 4pm tomorrow', ['instant' => $jstInstant, 'timezone' => 'BBB'], [
            'timezones' => ['BBB' => 60],
        ])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-07 16:00:00 +01:00');
});

it('uses reference timezone for written date times without embedded offsets like upstream', function () {
    expect(Chrono::parseDate('Sun Jun 06 2021 19:00:00', ['timezone' => 'JST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-06 19:00:00 +09:00')
        ->and(Chrono::parseDate('Sun Jun 06 2021 11:00:00', ['timezone' => 'BST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-06 11:00:00 +01:00')
        ->and(Chrono::parseDate('Sun Jun 06 2021 11:00:00', ['timezone' => 60])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-06-06 11:00:00 +01:00');
});

it('preserves precise now instants with reference timezone overrides', function () {
    $instant = 'Sat Mar 13 2021 14:22:14 GMT+0900 (Japan Standard Time)';

    $jst = Chrono::parseDate('now', $instant);
    $shifted = Chrono::parseDate('now', ['instant' => $instant, 'timezone' => -300]);

    expect($jst?->format('Y-m-d H:i:s P'))->toBe('2021-03-13 14:22:14 +09:00')
        ->and($shifted?->format('Y-m-d H:i:s P'))->toBe('2021-03-13 00:22:14 -05:00')
        ->and($shifted?->getTimestamp())->toBe($jst?->getTimestamp());
});

it('preserves explicit timezone offsets over reference timezone overrides', function () {
    $text = 'Sat Mar 13 2021 14:22:14 GMT+0900';
    $reference = '2026-06-23 09:00:00';

    expect(Chrono::parseDate($text, $reference)?->format('Y-m-d H:i:s P'))
        ->toBe('2021-03-13 14:22:14 +09:00')
        ->and(Chrono::parseDate($text, ['instant' => $reference])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-03-13 14:22:14 +09:00')
        ->and(Chrono::parseDate($text, ['instant' => $reference, 'timezone' => 540])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-03-13 14:22:14 +09:00')
        ->and(Chrono::parseDate($text, ['instant' => $reference, 'timezone' => 'JST'])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-03-13 14:22:14 +09:00')
        ->and(Chrono::parseDate($text, ['instant' => $reference, 'timezone' => -300])?->format('Y-m-d H:i:s P'))
        ->toBe('2021-03-13 14:22:14 +09:00');
});

it('parses ambiguous timezone abbreviations using dst rules', function () {
    expect(Chrono::parse('2022-03-12 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-300)
        ->and(Chrono::parse('2022-03-13 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-240)
        ->and(Chrono::parse('2021-11-06 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-240)
        ->and(Chrono::parse('2021-11-07 23:00 ET')[0]->start->timezoneOffset())
        ->toBe(-300)
        ->and(Chrono::parse('2022-03-12 23:00 CT')[0]->start->timezoneOffset())
        ->toBe(-360)
        ->and(Chrono::parse('2022-03-13 23:00 CT')[0]->start->timezoneOffset())
        ->toBe(-300)
        ->and(Chrono::parse('2022-11-06 23:00 CT')[0]->start->timezoneOffset())
        ->toBe(-360)
        ->and(Chrono::parse('2022-03-12 23:00 MT')[0]->start->timezoneOffset())
        ->toBe(-420)
        ->and(Chrono::parse('2022-03-13 23:00 MT')[0]->start->timezoneOffset())
        ->toBe(-360)
        ->and(Chrono::parse('2022-11-06 23:00 MT')[0]->start->timezoneOffset())
        ->toBe(-420)
        ->and(Chrono::parse('2022-03-12 23:00 PT')[0]->start->timezoneOffset())
        ->toBe(-480)
        ->and(Chrono::parse('2022-03-13 23:00 PT')[0]->start->timezoneOffset())
        ->toBe(-420)
        ->and(Chrono::parse('2022-11-06 23:00 PT')[0]->start->timezoneOffset())
        ->toBe(-480);
});

it('parses central european timezone abbreviation using dst rules', function () {
    expect(Chrono::parse('2022-03-26 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(60)
        ->and(Chrono::parse('2022-03-27 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(120)
        ->and(Chrono::parse('2022-10-29 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(120)
        ->and(Chrono::parse('2022-10-30 23:00 CET')[0]->start->timezoneOffset())
        ->toBe(60);
});

it('minimizes pre-1900 timezone drift like upstream', function () {
    $utc = Chrono::parse('1900-01-01T00:00:00-00:00')[0];
    $minusOne = Chrono::parse('1900-01-01T00:00:00-01:00')[0];
    $plusEight = Chrono::parse('1900-01-01T00:00:00+08:00')[0];
    $jst = Chrono::parse('1900-01-01T00:00', ['timezone' => 'JST'])[0];

    expect($utc->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 +00:00')
        ->and($minusOne->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 -01:00')
        ->and($plusEight->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 +08:00')
        ->and($jst->start->date()->format('Y-m-d H:i:s P'))->toBe('1900-01-01 00:00:00 +09:00')
        ->and($utc->start->date()->getTimestamp())->toBe(CarbonImmutable::parse('1900-01-01 00:00:00 +00:00')->getTimestamp())
        ->and($minusOne->start->date()->getTimestamp())->toBe(CarbonImmutable::parse('1900-01-01 00:00:00 -01:00')->getTimestamp())
        ->and($plusEight->start->date()->getTimestamp())->toBe(CarbonImmutable::parse('1900-01-01 00:00:00 +08:00')->getTimestamp())
        ->and($jst->start->date()->getTimestamp())->toBe(CarbonImmutable::parse('1900-01-01 00:00:00 +09:00')->getTimestamp());
});
