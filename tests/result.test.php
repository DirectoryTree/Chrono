<?php

use Carbon\CarbonImmutable;
use Chrono\Chrono;
use Chrono\Dates;
use Chrono\Meridiem;
use Chrono\Options;
use Chrono\ParsedComponents;
use Chrono\ParsedResult;
use Chrono\Reference;
use Chrono\Refiner;

it('assigns and implies date components like upstream helpers', function () {
    $date = CarbonImmutable::parse('2026-06-23 15:04:05.006');
    $assigned = new ParsedComponents(CarbonImmutable::parse('2012-08-10 01:02:03.004'));
    $implied = new ParsedComponents(CarbonImmutable::parse('2012-08-10 01:02:03.004'));

    Dates::assignSimilarDate($assigned, $date);
    Dates::assignSimilarTime($assigned, $date);
    Dates::implySimilarDate($implied, $date);
    Dates::implySimilarTime($implied, $date);

    expect($assigned->get('year'))->toBe(2026)
        ->and($assigned->get('month'))->toBe(6)
        ->and($assigned->get('day'))->toBe(23)
        ->and($assigned->get('hour'))->toBe(15)
        ->and($assigned->get('meridiem'))->toBe(Meridiem::PM)
        ->and($assigned->isCertain('meridiem'))->toBeTrue()
        ->and($implied->get('year'))->toBe(2026)
        ->and($implied->get('hour'))->toBe(15)
        ->and($implied->get('meridiem'))->toBe(Meridiem::PM)
        ->and($implied->isCertain('year'))->toBeFalse()
        ->and($implied->isCertain('meridiem'))->toBeFalse();
});

it('returns known and implied meridiem components like upstream results', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2022-08-27 12:00:00'));

    expect($components->get('meridiem'))->toBeNull()
        ->and($components->imply('meridiem', Meridiem::PM->value))->toBe($components)
        ->and($components->get('meridiem'))->toBe(Meridiem::PM)
        ->and($components->isCertain('meridiem'))->toBeFalse()
        ->and($components->assign('meridiem', Meridiem::AM->value))->toBe($components)
        ->and($components->get('meridiem'))->toBe(Meridiem::AM)
        ->and($components->isCertain('meridiem'))->toBeTrue();
});

it('exposes reference metadata on parsed results like upstream', function () {
    $reference = Reference::make('2022-08-27 12:52:11');
    $result = Chrono::parse('tomorrow', $reference->date)[0];
    $range = Chrono::parse('March 1 to March 2', $reference->date)[0];
    $clone = $result->clone();

    expect($result->reference)->toBeInstanceOf(Reference::class)
        ->and($result->reference?->date->toDateTimeString())->toBe('2022-08-27 12:52:11')
        ->and($result->refDate?->toDateTimeString())->toBe('2022-08-27 12:52:11')
        ->and($result->start->reference())->toBe($result->reference)
        ->and($range->end)->not->toBeNull()
        ->and($range->reference)->toBeInstanceOf(Reference::class)
        ->and($range->refDate?->toDateTimeString())->toBe('2022-08-27 12:52:11')
        ->and($range->start->reference())->toBe($range->reference)
        ->and($range->end?->reference())->toBe($range->reference)
        ->and($clone->reference)->toBe($result->reference)
        ->and($clone->start->reference())->toBe($result->reference)
        ->and($clone->refDate?->toDateTimeString())->toBe('2022-08-27 12:52:11');
});

it('supports custom refiners that mutate ambiguous parsed results like upstream chrono instances', function () {
    $afternoonGuess = new class implements Refiner
    {
        public function refine(string $text, array $results, Reference $reference, Options $options): array
        {
            foreach ($results as $result) {
                if (! $result->start->isCertain('meridiem')
                    && $result->start->get('hour') >= 1
                    && $result->start->get('hour') < 4) {
                    $result->start->assign('meridiem', Meridiem::PM->value);
                    $result->start->assign('hour', (int) $result->start->get('hour') + 12);
                }
            }

            return $results;
        }
    };

    $custom = Chrono::casual()->withRefiner($afternoonGuess);
    $ambiguous = $custom->parseText('This is at 2.30', '2016-10-01 08:00')[0];
    $explicit = $custom->parseText('This is at 2.30 AM', '2016-10-01 08:00')[0];

    expect($ambiguous->text)->toBe('at 2.30')
        ->and($ambiguous->start->get('hour'))->toBe(14)
        ->and($ambiguous->start->get('minute'))->toBe(30)
        ->and($ambiguous->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($explicit->text)->toBe('at 2.30 AM')
        ->and($explicit->start->get('hour'))->toBe(2)
        ->and($explicit->start->get('minute'))->toBe(30)
        ->and($explicit->start->get('meridiem'))->toBe(Meridiem::AM);
});

it('combines parsed result tags from result start and end components', function () {
    $start = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 09:00')))->addTag('custom/start');
    $end = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 10:00')))->addTag('custom/end');
    $result = new ParsedResult(0, '9-10', $start, $end, ['custom/result']);

    $result->addTag('custom/added');
    $result->addTags(['custom/batch']);
    $result->addTags(new ArrayIterator(['custom/iterator']));

    expect($result->tags())->toContain('custom/start')
        ->and($result->tags())->toContain('custom/end')
        ->and($result->tags())->toContain('custom/result')
        ->and($result->tags())->toContain('custom/added')
        ->and($result->tags())->toContain('custom/batch')
        ->and($result->tags())->toContain('custom/iterator')
        ->and($result->start->tags())->toContain('custom/added')
        ->and($result->end?->tags())->toContain('custom/added')
        ->and($result->start->tags())->toContain('custom/batch')
        ->and($result->end?->tags())->toContain('custom/batch')
        ->and($result->start->tags())->toContain('custom/iterator')
        ->and($result->end?->tags())->toContain('custom/iterator');
});

it('exposes parsed result date clone and string helpers', function () {
    $start = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 09:00')))->addTag('custom/start');
    $end = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 10:00')))->addTag('custom/end');
    $result = new ParsedResult(4, '9-10', $start, $end, ['custom/result']);

    $clone = $result->clone();
    $clone->start->assign('hour', 11)->addTag('custom/clone');

    expect($result->date()->toDateTimeString())->toBe('2026-06-23 12:00:00')
        ->and($clone->date()->toDateTimeString())->toBe('2026-06-23 11:00:00')
        ->and($result->date()->toDateTimeString())->toBe('2026-06-23 12:00:00')
        ->and($result->tags())->not->toContain('custom/clone')
        ->and($clone->tags())->not->toContain('custom/start')
        ->and($clone->tags())->not->toContain('custom/end')
        ->and($clone->tags())->not->toContain('custom/result')
        ->and($clone->tags())->toContain('custom/clone')
        ->and((string) $result)->toContain('index: 4')
        ->and((string) $result)->toContain("text: '9-10'")
        ->and((string) $result)->toContain('custom/start')
        ->and((string) $result)->toContain('custom/end');
});

it('manipulates parsed components like upstream parsing components', function () {
    $components = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:00:00'), [
        'year' => true,
        'month' => true,
        'day' => true,
    ]);

    expect($components->get('weekday'))->toBeNull()
        ->and($components->isCertain('weekday'))->toBeFalse();

    $components->imply('weekday', 1);

    expect($components->get('weekday'))->toBe(1)
        ->and($components->isCertain('weekday'))->toBeFalse();

    $components->assign('weekday', 2);
    $components->imply('year', 2013);
    $components->assign('year', 2013);
    $components->addTags(['custom/one', 'custom/two']);
    $components->attachReference(Reference::make([
        'instant' => 'Wed Jun 09 2021 07:21:32 GMT+0900',
        'timezone' => 'JST',
    ]));

    $clone = $components->clone();
    $clone->delete(['weekday', 'year'])->addTag('custom/clone');

    expect($components->get('weekday'))->toBe(2)
        ->and($components->isCertain('weekday'))->toBeTrue()
        ->and($components->get('year'))->toBe(2013)
        ->and($components->getCertainComponents())->toContain('year')
        ->and($components->getCertainComponents())->toContain('month')
        ->and($components->getCertainComponents())->toContain('day')
        ->and($components->getCertainComponents())->toContain('weekday')
        ->and($components->tags())->toContain('custom/one')
        ->and($components->tags())->toContain('custom/two')
        ->and($components->isOnlyDate())->toBeTrue()
        ->and($clone->isCertain('weekday'))->toBeFalse()
        ->and($clone->isCertain('year'))->toBeFalse()
        ->and($clone->get('year'))->toBeNull()
        ->and($clone->tags())->not->toContain('custom/one')
        ->and($clone->tags())->not->toContain('custom/two')
        ->and($clone->tags())->toContain('custom/clone')
        ->and($components->tags())->not->toContain('custom/clone')
        ->and((string) $components)->toContain('custom/one')
        ->and((string) $components)->toContain('knownValues')
        ->and((string) $components)->toContain('impliedValues')
        ->and((string) $components)->toContain('reference')
        ->and((string) $components)->toContain('2021-06-09 07:21:32 +09:00')
        ->and((string) $components)->toContain('"timezoneOffset":540');
});

it('does not infer meridiem from hour-only components like upstream parsing components', function () {
    $components = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 12:00:00')))
        ->assign('hour', 15);

    expect($components->get('hour'))->toBe(15)
        ->and($components->get('meridiem'))->toBeNull()
        ->and($components->isCertain('meridiem'))->toBeFalse();
});

it('creates relative parsed components from references like upstream parsing components', function () {
    $reference = Reference::make('2022-08-27 12:52:11');

    $empty = ParsedComponents::createRelativeFromReference($reference);
    $days = ParsedComponents::createRelativeFromReference($reference, ['day' => 3]);
    $dateTime = ParsedComponents::createRelativeFromReference($reference, ['day' => 1, 'hour' => 3]);
    $weeks = ParsedComponents::createRelativeFromReference($reference, ['week' => 1]);
    $months = ParsedComponents::createRelativeFromReference($reference, ['month' => 1]);
    $years = ParsedComponents::createRelativeFromReference($reference, ['year' => 1]);

    expect($empty->date()->toDateTimeString())->toBe('2022-08-27 12:52:11')
        ->and($empty->reference())->toBe($reference)
        ->and($empty->isCertain('day'))->toBeTrue()
        ->and($empty->isCertain('hour'))->toBeTrue()
        ->and($empty->tags())->toContain('result/relativeDate')
        ->and($empty->tags())->toContain('result/relativeDateAndTime')
        ->and($days->date()->toDateTimeString())->toBe('2022-08-30 12:52:11')
        ->and($days->isCertain('day'))->toBeTrue()
        ->and($days->isCertain('hour'))->toBeFalse()
        ->and($dateTime->date()->toDateTimeString())->toBe('2022-08-28 15:52:11')
        ->and($dateTime->isCertain('day'))->toBeTrue()
        ->and($dateTime->isCertain('hour'))->toBeTrue()
        ->and($dateTime->tags())->toContain('result/relativeDateAndTime')
        ->and($weeks->date()->toDateTimeString())->toBe('2022-09-03 12:52:11')
        ->and($weeks->isCertain('day'))->toBeTrue()
        ->and($weeks->isCertain('weekday'))->toBeFalse()
        ->and($months->date()->toDateTimeString())->toBe('2022-09-27 12:52:11')
        ->and($months->isCertain('month'))->toBeTrue()
        ->and($months->isCertain('day'))->toBeFalse()
        ->and($years->date()->toDateTimeString())->toBe('2023-08-27 12:52:11')
        ->and($years->isCertain('year'))->toBeTrue()
        ->and($years->isCertain('month'))->toBeFalse();
});

it('validates assigned parsed component time bounds before carbon normalization', function () {
    $valid = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'));
    $validCalendar = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'), [
        'year' => 2014,
        'month' => 11,
        'day' => 24,
        'hour' => 12,
        'minute' => 30,
        'second' => 30,
    ]);
    $validWithImpliedTimezone = new ParsedComponents(CarbonImmutable::parse('2021-03-13 14:22:14'), [
        'year' => 2021,
        'month' => 3,
        'day' => 13,
        'hour' => 14,
        'minute' => 22,
        'second' => 14,
        'millisecond' => 0,
    ]);
    $invalidMonth = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'), [
        'year' => 2014,
        'month' => 13,
        'day' => 24,
    ]);
    $invalidDay = new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30'), [
        'year' => 2014,
        'month' => 11,
        'day' => 32,
    ]);
    $validBeforeCommonEraLeapDay = new ParsedComponents(CarbonImmutable::parse('-0240-02-29 12:30:30'), [
        'year' => -240,
        'month' => 2,
        'day' => 29,
    ]);
    $invalidBeforeCommonEraDay = new ParsedComponents(CarbonImmutable::parse('-0234-02-28 12:30:30'), [
        'year' => -234,
        'month' => 2,
        'day' => 31,
    ]);
    $invalidHour = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('hour', 24);
    $invalidNegativeHour = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('hour', -1);
    $invalidMinute = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('minute', 60);
    $invalidSecond = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('second', 60);
    $validMillisecondOverflow = (new ParsedComponents(CarbonImmutable::parse('2014-11-24 12:30:30')))->assign('millisecond', 1000);

    $validWithImpliedTimezone->imply('timezoneOffset', -300);

    expect($valid->isValidDate())->toBeTrue()
        ->and($validCalendar->isValidDate())->toBeTrue()
        ->and($validWithImpliedTimezone->isValidDate())->toBeTrue()
        ->and($validWithImpliedTimezone->isCertain('timezoneOffset'))->toBeFalse()
        ->and($validWithImpliedTimezone->date()->format('Y-m-d H:i:s P'))->toBe('2021-03-13 14:22:14 -05:00')
        ->and($invalidMonth->isValidDate())->toBeFalse()
        ->and($invalidDay->isValidDate())->toBeFalse()
        ->and($validBeforeCommonEraLeapDay->isValidDate())->toBeTrue()
        ->and($invalidBeforeCommonEraDay->isValidDate())->toBeFalse()
        ->and($invalidHour->get('hour'))->toBe(24)
        ->and($invalidHour->isValidDate())->toBeFalse()
        ->and($invalidNegativeHour->get('hour'))->toBe(-1)
        ->and($invalidNegativeHour->isValidDate())->toBeFalse()
        ->and($invalidMinute->get('minute'))->toBe(60)
        ->and($invalidMinute->isValidDate())->toBeFalse()
        ->and($invalidSecond->get('second'))->toBe(60)
        ->and($invalidSecond->isValidDate())->toBeFalse()
        ->and($validMillisecondOverflow->get('millisecond'))->toBe(1000)
        ->and($validMillisecondOverflow->date()->format('Y-m-d H:i:s.v'))->toBe('2014-11-24 12:00:01.000')
        ->and($validMillisecondOverflow->isValidDate())->toBeTrue();
});

it('rejects parsed component times normalized through a dst gap like upstream', function () {
    $missingTwo = new ParsedComponents(CarbonImmutable::parse('2022-03-27 01:00:00', 'Europe/Berlin'), [
        'year' => 2022,
        'month' => 3,
        'day' => 27,
        'hour' => 2,
        'minute' => 0,
    ]);
    $missingLateTwo = new ParsedComponents(CarbonImmutable::parse('2022-03-27 01:00:00', 'Europe/Berlin'), [
        'year' => 2022,
        'month' => 3,
        'day' => 27,
        'hour' => 2,
        'minute' => 59,
    ]);
    $beforeGap = new ParsedComponents(CarbonImmutable::parse('2022-03-27 01:00:00', 'Europe/Berlin'), [
        'year' => 2022,
        'month' => 3,
        'day' => 27,
        'hour' => 1,
        'minute' => 59,
    ]);
    $afterGap = new ParsedComponents(CarbonImmutable::parse('2022-03-27 01:00:00', 'Europe/Berlin'), [
        'year' => 2022,
        'month' => 3,
        'day' => 27,
        'hour' => 3,
        'minute' => 0,
    ]);

    expect($missingTwo->date()->format('Y-m-d H:i:s P'))->toBe('2022-03-27 03:00:00 +02:00')
        ->and($missingTwo->isValidDate())->toBeFalse()
        ->and($missingLateTwo->date()->format('Y-m-d H:i:s P'))->toBe('2022-03-27 03:59:00 +02:00')
        ->and($missingLateTwo->isValidDate())->toBeFalse()
        ->and($beforeGap->isValidDate())->toBeTrue()
        ->and($afterGap->isValidDate())->toBeTrue();
});

it('detects only-time components by absence of certain date fields', function () {
    $impliedTime = new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00'));
    $impliedTime->imply('hour', 8);

    $weekday = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))->assign('weekday', 2);
    $date = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 08:00')))->assign('day', 23);

    expect($impliedTime->isCertain('hour'))->toBeFalse()
        ->and($impliedTime->isOnlyTime())->toBeTrue()
        ->and($weekday->isOnlyTime())->toBeFalse()
        ->and($date->isOnlyTime())->toBeFalse();
});

it('detects unknown-year dates by month certainty like upstream parsing components', function () {
    $monthOnly = (new ParsedComponents(CarbonImmutable::parse('2026-06-01 12:00')))->assign('month', 6);
    $monthDay = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 12:00')))
        ->assign('month', 6)
        ->assign('day', 23);
    $explicitYear = (new ParsedComponents(CarbonImmutable::parse('2026-06-23 12:00')))
        ->assign('month', 6)
        ->assign('year', 2026);

    expect($monthOnly->isDateWithUnknownYear())->toBeTrue()
        ->and($monthDay->isDateWithUnknownYear())->toBeTrue()
        ->and($explicitYear->isDateWithUnknownYear())->toBeFalse()
        ->and(Chrono::parseDate('in May', '2026-06-23', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2027-05-01 12:00:00');
});

it('returns all parsed results in document order', function () {
    $results = Chrono::parse('today and tomorrow', '2026-06-23 09:00');

    expect($results)->toHaveCount(2)
        ->and($results[0]->text)->toBe('today')
        ->and($results[1]->text)->toBe('tomorrow');
});
