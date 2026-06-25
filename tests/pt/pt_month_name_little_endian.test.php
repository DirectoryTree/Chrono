<?php

use Chrono\Chrono;

it('parses portuguese month name dates and ranges', function () {
    $portuguese = Chrono::pt();
    $explicit = $portuguese->parseText('10 de agosto de 2012', '2012-08-10')[0];
    $beforeCommonEra = $portuguese->parseText('10 Agosto 234 AC', '2012-08-10')[0];
    $commonEra = $portuguese->parseText('10 Agosto 88 d. C.', '2012-08-10')[0];
    $weekdayMonth = $portuguese->parseText('Dom 15Set', '2013-08-10')[0];
    $upperWeekdayMonth = $portuguese->parseText('DOM 15SET', '2013-08-10')[0];
    $prefixedMonth = $portuguese->parseText('O prazo é 10 Agosto', '2012-08-10')[0];
    $weekdayPrefixedMonth = $portuguese->parseText('O prazo é terça-feira, 10 de janeiro', '2012-08-10')[0];
    $abbreviatedWeekdayPrefixedMonth = $portuguese->parseText('O prazo é Qua, 10 Janeiro', '2012-08-10')[0];
    $range = $portuguese->parseText('10-12 de agosto', '2012-08-10')[0];
    $dashRange = $portuguese->parseText('10 - 22 Agosto 2012', '2012-08-10')[0];
    $aRange = $portuguese->parseText('10 a 22 Agosto 2012', '2012-08-10')[0];
    $untilRange = $portuguese->parseText('15 até 16 agosto', '2012-08-10')[0];
    $crossMonthRange = $portuguese->parseText('10 Agosto - 12 Setembro', '2012-08-10')[0];
    $crossMonthWithYear = $portuguese->parseText('10 Agosto - 12 Setembro 2013', '2012-08-10')[0];
    $dateTime = $portuguese->parseText('12 de Julho às 19:00', '2012-08-10')[0];
    $explicitWithoutDe = $portuguese->parseText('10 Agosto 2012', '2012-08-10')[0];

    expect($explicit->index)->toBe(0)
        ->and($explicit->text)->toBe('10 de agosto de 2012')
        ->and($explicit->start->get('year'))->toBe(2012)
        ->and($explicit->start->get('month'))->toBe(8)
        ->and($explicit->start->get('day'))->toBe(10)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($explicit->start->tags())->toContain('parser/PTMonthNameLittleEndianParser')
        ->and($explicitWithoutDe->index)->toBe(0)
        ->and($explicitWithoutDe->text)->toBe('10 Agosto 2012')
        ->and($explicitWithoutDe->start->get('year'))->toBe(2012)
        ->and($explicitWithoutDe->start->get('month'))->toBe(8)
        ->and($explicitWithoutDe->start->get('day'))->toBe(10)
        ->and($explicitWithoutDe->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($beforeCommonEra->text)->toBe('10 Agosto 234 AC')
        ->and($beforeCommonEra->start->get('year'))->toBe(-234)
        ->and($beforeCommonEra->start->date()->format('Y-m-d H:i:s'))->toBe('-0234-08-10 12:00:00')
        ->and($commonEra->text)->toBe('10 Agosto 88 d. C.')
        ->and($commonEra->start->get('year'))->toBe(88)
        ->and($commonEra->start->get('month'))->toBe(8)
        ->and($commonEra->start->get('day'))->toBe(10)
        ->and($weekdayMonth->index)->toBe(0)
        ->and($weekdayMonth->text)->toBe('Dom 15Set')
        ->and($weekdayMonth->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($upperWeekdayMonth->text)->toBe('DOM 15SET')
        ->and($upperWeekdayMonth->start->date()->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($prefixedMonth->text)->toBe('10 Agosto')
        ->and($prefixedMonth->index)->toBe(11)
        ->and($prefixedMonth->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($weekdayPrefixedMonth->text)->toBe('terça-feira, 10 de janeiro')
        ->and($weekdayPrefixedMonth->index)->toBe(11)
        ->and($weekdayPrefixedMonth->start->get('weekday'))->toBe(2)
        ->and($weekdayPrefixedMonth->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($abbreviatedWeekdayPrefixedMonth->text)->toBe('Qua, 10 Janeiro')
        ->and($abbreviatedWeekdayPrefixedMonth->start->get('weekday'))->toBe(3)
        ->and($abbreviatedWeekdayPrefixedMonth->start->date()->toDateTimeString())->toBe('2013-01-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->end?->tags())->toContain('parser/PTMonthNameLittleEndianParser')
        ->and($dashRange->text)->toBe('10 - 22 Agosto 2012')
        ->and($dashRange->index)->toBe(0)
        ->and($dashRange->start->get('year'))->toBe(2012)
        ->and($dashRange->start->get('month'))->toBe(8)
        ->and($dashRange->start->get('day'))->toBe(10)
        ->and($dashRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dashRange->end?->get('year'))->toBe(2012)
        ->and($dashRange->end?->get('month'))->toBe(8)
        ->and($dashRange->end?->get('day'))->toBe(22)
        ->and($dashRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($aRange->text)->toBe('10 a 22 Agosto 2012')
        ->and($aRange->index)->toBe(0)
        ->and($aRange->start->get('year'))->toBe(2012)
        ->and($aRange->start->get('month'))->toBe(8)
        ->and($aRange->start->get('day'))->toBe(10)
        ->and($aRange->end?->get('year'))->toBe(2012)
        ->and($aRange->end?->get('month'))->toBe(8)
        ->and($aRange->end?->get('day'))->toBe(22)
        ->and($aRange->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($untilRange->text)->toBe('15 até 16 agosto')
        ->and($untilRange->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($untilRange->end?->date()->toDateTimeString())->toBe('2012-08-16 12:00:00')
        ->and($crossMonthRange->text)->toBe('10 Agosto - 12 Setembro')
        ->and($crossMonthRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($crossMonthRange->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($crossMonthWithYear->text)->toBe('10 Agosto - 12 Setembro 2013')
        ->and($crossMonthWithYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($crossMonthWithYear->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($dateTime->text)->toBe('12 de Julho às 19:00')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($portuguese->parseText('32 Agosto 2014', '2012-08-10'))->toBe([])
        ->and($portuguese->parseText('29 Fevereiro 2014', '2012-08-10'))->toBe([])
        ->and(Chrono::strictPortuguese()->parseText('32 Agosto', '2012-08-10'))->toBe([]);
});
