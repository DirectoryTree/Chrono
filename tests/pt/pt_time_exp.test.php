<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Meridiem;

it('parses portuguese time expressions', function () {
    $portuguese = Chrono::pt();
    $single = $portuguese->parseText('Ficaremos às 6.13 AM', '2012-08-10')[0];
    $dotRange = $portuguese->parseText('8:10 - 12.32', '2012-08-10')[0];
    $prefixedRange = $portuguese->parseText(' de 6:30pm a 11:00pm ', '2012-08-10')[0];
    $dateTime = $portuguese->parseText('Algo passou em 10 de Agosto de 2012 10:12:59 pm', '2012-08-10')[0];
    $impliedMeridiemRange = $portuguese->parseText('de 1pm a 3', '2012-08-10')[0];
    $shortMeridiem = $portuguese->parseText('6pm', '2012-08-10')[0];
    $spacedMeridiem = $portuguese->parseText('6 pm', '2012-08-10')[0];
    $shortRange = $portuguese->parseText('7-10pm', '2012-08-10')[0];
    $shortDotTime = $portuguese->parseText('11.1pm', '2012-08-10')[0];
    $atNoon = $portuguese->parseText('às 12', '2012-08-10')[0];

    expect($single->index)->toBe(10)
        ->and($single->text)->toBe('às 6.13 AM')
        ->and($single->start->get('hour'))->toBe(6)
        ->and($single->start->get('minute'))->toBe(13)
        ->and($single->start->date()->toDateTimeString())->toBe('2012-08-10 06:13:00')
        ->and($single->start->tags())->toContain('parser/PTTimeExpressionParser')
        ->and($dotRange->index)->toBe(0)
        ->and($dotRange->text)->toBe('8:10 - 12.32')
        ->and($dotRange->start->get('hour'))->toBe(8)
        ->and($dotRange->start->get('minute'))->toBe(10)
        ->and($dotRange->start->isCertain('year'))->toBeFalse()
        ->and($dotRange->start->isCertain('month'))->toBeFalse()
        ->and($dotRange->start->isCertain('day'))->toBeFalse()
        ->and($dotRange->start->isCertain('hour'))->toBeTrue()
        ->and($dotRange->start->isCertain('minute'))->toBeTrue()
        ->and($dotRange->start->isCertain('second'))->toBeFalse()
        ->and($dotRange->start->isCertain('millisecond'))->toBeFalse()
        ->and($dotRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($dotRange->end?->get('hour'))->toBe(12)
        ->and($dotRange->end?->get('minute'))->toBe(32)
        ->and($dotRange->end?->isCertain('year'))->toBeFalse()
        ->and($dotRange->end?->isCertain('month'))->toBeFalse()
        ->and($dotRange->end?->isCertain('day'))->toBeFalse()
        ->and($dotRange->end?->isCertain('hour'))->toBeTrue()
        ->and($dotRange->end?->isCertain('minute'))->toBeTrue()
        ->and($dotRange->end?->isCertain('second'))->toBeFalse()
        ->and($dotRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($dotRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($prefixedRange->index)->toBe(1)
        ->and($prefixedRange->text)->toBe('de 6:30pm a 11:00pm')
        ->and($prefixedRange->start->get('hour'))->toBe(18)
        ->and($prefixedRange->start->get('minute'))->toBe(30)
        ->and($prefixedRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($prefixedRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($prefixedRange->end?->get('hour'))->toBe(23)
        ->and($prefixedRange->end?->get('minute'))->toBe(0)
        ->and($prefixedRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($prefixedRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($dateTime->index)->toBe(15)
        ->and($dateTime->text)->toBe('10 de Agosto de 2012 10:12:59 pm')
        ->and($dateTime->start->get('year'))->toBe(2012)
        ->and($dateTime->start->get('month'))->toBe(8)
        ->and($dateTime->start->get('day'))->toBe(10)
        ->and($dateTime->start->get('hour'))->toBe(22)
        ->and($dateTime->start->get('minute'))->toBe(12)
        ->and($dateTime->start->get('second'))->toBe(59)
        ->and($dateTime->start->get('millisecond'))->toBe(0)
        ->and($dateTime->start->isCertain('millisecond'))->toBeFalse()
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 22:12:59')
        ->and($impliedMeridiemRange->index)->toBe(0)
        ->and($impliedMeridiemRange->text)->toBe('de 1pm a 3')
        ->and($impliedMeridiemRange->start->get('hour'))->toBe(13)
        ->and($impliedMeridiemRange->start->get('minute'))->toBe(0)
        ->and($impliedMeridiemRange->start->get('second'))->toBe(0)
        ->and($impliedMeridiemRange->start->get('millisecond'))->toBe(0)
        ->and($impliedMeridiemRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($impliedMeridiemRange->start->isCertain('meridiem'))->toBeTrue()
        ->and($impliedMeridiemRange->end?->get('hour'))->toBe(15)
        ->and($impliedMeridiemRange->end?->get('minute'))->toBe(0)
        ->and($impliedMeridiemRange->end?->get('second'))->toBe(0)
        ->and($impliedMeridiemRange->end?->get('millisecond'))->toBe(0)
        ->and($impliedMeridiemRange->end?->isCertain('meridiem'))->toBeTrue()
        ->and($shortMeridiem->text)->toBe('6pm')
        ->and($spacedMeridiem->text)->toBe('6 pm')
        ->and($shortRange->text)->toBe('7-10pm')
        ->and($shortDotTime->text)->toBe('11.1pm')
        ->and($atNoon->text)->toBe('às 12');
});

it('parses portuguese random date and time expressions', function () {
    $portuguese = Chrono::pt();

    expect($portuguese->parseText('segunda 4/29/2013 630-930am', '2012-08-10')[0]->text)->toBe('segunda 4/29/2013 630-930am')
        ->and($portuguese->parseText('terça 5/1/2013 1115am', '2012-08-10')[0]->text)->toBe('terça 5/1/2013 1115am')
        ->and($portuguese->parseText('quarta 5/3/2013 1230pm', '2012-08-10')[0]->text)->toBe('quarta 5/3/2013 1230pm')
        ->and($portuguese->parseText('domingo 5/6/2013  750am-910am', '2012-08-10')[0]->text)->toBe('domingo 5/6/2013  750am-910am')
        ->and($portuguese->parseText('segunda-feira 5/13/2013 630-930am', '2012-08-10')[0]->text)->toBe('segunda-feira 5/13/2013 630-930am')
        ->and($portuguese->parseText('quarta-feira 5/15/2013 1030am', '2012-08-10')[0]->text)->toBe('quarta-feira 5/15/2013 1030am')
        ->and($portuguese->parseText('quinta 6/21/2013 2:30', '2012-08-10')[0]->text)->toBe('quinta 6/21/2013 2:30')
        ->and($portuguese->parseText('terça-feira 7/2/2013 1-230 pm', '2012-08-10')[0]->text)->toBe('terça-feira 7/2/2013 1-230 pm')
        ->and($portuguese->parseText('Segunda-feira, 6/24/2013, 7:00pm - 8:30pm', '2012-08-10')[0]->text)->toBe('Segunda-feira, 6/24/2013, 7:00pm - 8:30pm')
        ->and($portuguese->parseText('Quarta, 3 Julho de 2013 às 2pm', '2012-08-10')[0]->text)->toBe('Quarta, 3 Julho de 2013 às 2pm')
        ->and($portuguese->parseText('6pm', '2012-08-10')[0]->text)->toBe('6pm')
        ->and($portuguese->parseText('6 pm', '2012-08-10')[0]->text)->toBe('6 pm')
        ->and($portuguese->parseText('7-10pm', '2012-08-10')[0]->text)->toBe('7-10pm')
        ->and($portuguese->parseText('11.1pm', '2012-08-10')[0]->text)->toBe('11.1pm')
        ->and($portuguese->parseText('às 12', '2012-08-10')[0]->text)->toBe('às 12');
});
