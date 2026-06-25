<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses french time expressions', function () {
    $french = Chrono::fr();
    $hourMinute = $french->parseText('8h10', '2012-08-10 00:00')[0];
    $hourMinuteSuffix = $french->parseText('8h10m', '2012-08-10 00:00')[0];
    $withZeroSeconds = $french->parseText('8h10m00', '2012-08-10 00:00')[0];
    $withSeconds = $french->parseText('8h10m00s', '2012-08-10 00:00')[0];
    $withMilliseconds = $french->parseText('8:10:30.123', '2012-08-10 00:00')[0];
    $prefixed = $french->parseText('RDV à 6.13 AM', '2012-08-10 00:00')[0];

    expect($hourMinute->text)->toBe('8h10')
        ->and($hourMinute->index)->toBe(0)
        ->and($hourMinute->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($hourMinute->start->tags())->toContain('parser/FRSpecificTimeExpressionParser')
        ->and($hourMinute->start->isCertain('day'))->toBeFalse()
        ->and($hourMinute->start->isCertain('month'))->toBeFalse()
        ->and($hourMinute->start->isCertain('year'))->toBeFalse()
        ->and($hourMinute->start->isCertain('hour'))->toBeTrue()
        ->and($hourMinute->start->isCertain('minute'))->toBeTrue()
        ->and($hourMinute->start->isCertain('second'))->toBeFalse()
        ->and($hourMinuteSuffix->text)->toBe('8h10m')
        ->and($hourMinuteSuffix->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($hourMinuteSuffix->start->isCertain('second'))->toBeFalse()
        ->and($withZeroSeconds->text)->toBe('8h10m00')
        ->and($withZeroSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($withZeroSeconds->start->isCertain('second'))->toBeTrue()
        ->and($french->parseDateText('8:10 PM', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 20:10:00')
        ->and($french->parseDateText('8h10 PM', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 20:10:00')
        ->and($french->parseDateText('1230pm', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 12:30:00')
        ->and($french->parseDateText('5:16p', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5h16p', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5h16mp', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5:16 p.m.', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($french->parseDateText('5h16 p.m.', '2012-08-10 00:00')?->toDateTimeString())
        ->toBe('2012-08-10 17:16:00')
        ->and($prefixed->index)
        ->toBe(4)
        ->and($prefixed->text)
        ->toBe('à 6.13 AM')
        ->and($prefixed->start->date()->toDateTimeString())
        ->toBe('2012-08-10 06:13:00')
        ->and($prefixed->start->tags())
        ->toContain('parser/FRTimeExpressionParser')
        ->and($withSeconds->text)->toBe('8h10m00s')
        ->and($withSeconds->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($withSeconds->start->isCertain('second'))->toBeTrue()
        ->and($withMilliseconds->text)->toBe('8:10:30.123')
        ->and($withMilliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 08:10:30.123')
        ->and($withMilliseconds->start->isCertain('millisecond'))->toBeTrue()
        ->and($french->parseText('8:62', '2012-08-10'))->toBe([])
        ->and($french->parseText('25:12', '2012-08-10'))->toBe([])
        ->and($french->parseText('12h12:99s', '2012-08-10'))->toBe([])
        ->and($french->parseText('13.12 PM', '2012-08-10'))->toBe([]);
});

it('parses french time ranges', function () {
    $french = Chrono::fr();
    $hourRange = $french->parseText('13h-15h', '2012-08-10 00:00')[0];
    $impliedHourRange = $french->parseText('13-15h', '2012-08-10 00:00')[0];
    $pmRange = $french->parseText('1-3pm', '2012-08-10 00:00')[0];
    $overnight = $french->parseText('11pm-2', '2012-08-10 00:00')[0];
    $minuteRange = $french->parseText('8:10 - 12.32', '2012-08-10 00:00')[0];
    $mixedRange = $french->parseText('8:10 - 12h32', '2012-08-10 00:00')[0];
    $tildeRange = $french->parseText('8:10 ~ 12h32', '2012-08-10 00:00')[0];
    $prefixedRange = $french->parseText(' de 6:30pm à 11:00pm ', '2012-08-10 00:00')[0];

    expect($hourRange->text)->toBe('13h-15h')
        ->and($hourRange->index)->toBe(0)
        ->and($hourRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($hourRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($hourRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($hourRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($impliedHourRange->text)->toBe('13-15h')
        ->and($impliedHourRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($impliedHourRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($pmRange->text)->toBe('1-3pm')
        ->and($pmRange->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($pmRange->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($pmRange->end?->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($pmRange->end?->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->text)->toBe('11pm-2')
        ->and($overnight->start->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($overnight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2012-08-11 02:00:00')
        ->and($overnight->end?->get('meridiem'))->toBe(Meridiem::AM)
        ->and($minuteRange->text)->toBe('8:10 - 12.32')
        ->and($minuteRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($minuteRange->start->isCertain('second'))->toBeFalse()
        ->and($minuteRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($minuteRange->end?->isCertain('second'))->toBeFalse()
        ->and($mixedRange->text)->toBe('8:10 - 12h32')
        ->and($mixedRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($mixedRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($tildeRange->text)->toBe('8:10 ~ 12h32')
        ->and($tildeRange->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($tildeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:32:00')
        ->and($prefixedRange->text)->toBe('de 6:30pm à 11:00pm')
        ->and($prefixedRange->index)->toBe(1)
        ->and($prefixedRange->start->date()->toDateTimeString())->toBe('2012-08-10 18:30:00')
        ->and($prefixedRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($french->parseText(' 2012 à 10:12:59', '2012-08-10 00:00')[0]->index)->toBe(6)
        ->and($french->parseText(' 2012 à 10:12:59', '2012-08-10 00:00')[0]->text)->toBe('à 10:12:59')
        ->and($french->parseDateText(' 2012 à 10:12:59', '2012-08-10 00:00')?->toDateTimeString())->toBe('2012-08-10 10:12:59');
});

it('merges french dates followed by time expressions', function () {
    $french = Chrono::fr();
    $iso = $french->parseText('Quelque chose se passe le 2014-04-18 à 3h00', '2012-08-10')[0];
    $isoRange = $french->parseText('Quelque chose se passe le 2014-04-18 7:00 - 8h00 ...', '2012-08-10')[0];
    $isoDeRange = $french->parseText('Quelque chose se passe le 2014-04-18 de 7:00 à 20:00 ...', '2012-08-10')[0];
    $month = $french->parseText('Quelque chose se passe le 10 Août 2012 à 10:12:59', '2012-08-10')[0];
    $compactMonth = $french->parseText('Quelque chose se passe le 15juin 2016 20h', '2016-07-10')[0];
    $attachedWeekday = $french->parseText('Jeudi6/5/2013 de 7h à 10h')[0];

    expect($iso->text)->toBe('2014-04-18 à 3h00')
        ->and($iso->index)->toBe(26)
        ->and($iso->start->date()->toDateTimeString())->toBe('2014-04-18 03:00:00')
        ->and($iso->start->isCertain('millisecond'))->toBeFalse()
        ->and($iso->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($isoRange->text)->toBe('2014-04-18 7:00 - 8h00')
        ->and($isoRange->index)->toBe(26)
        ->and($isoRange->start->date()->toDateTimeString())->toBe('2014-04-18 07:00:00')
        ->and($isoRange->start->isCertain('meridiem'))->toBeFalse()
        ->and($isoRange->start->isCertain('millisecond'))->toBeFalse()
        ->and($isoRange->start->tags())->toContain('parser/FRIsoDateTimeRangeParser')
        ->and($isoRange->end?->date()->toDateTimeString())->toBe('2014-04-18 08:00:00')
        ->and($isoRange->end?->isCertain('meridiem'))->toBeFalse()
        ->and($isoRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($isoRange->end?->tags())->toContain('parser/FRIsoDateTimeRangeParser')
        ->and($isoDeRange->text)->toBe('2014-04-18 de 7:00 à 20:00')
        ->and($isoDeRange->start->date()->toDateTimeString())->toBe('2014-04-18 07:00:00')
        ->and($isoDeRange->start->isCertain('meridiem'))->toBeFalse()
        ->and($isoDeRange->end?->date()->toDateTimeString())->toBe('2014-04-18 20:00:00')
        ->and($isoDeRange->end?->isCertain('millisecond'))->toBeFalse()
        ->and($month->text)->toBe('10 Août 2012 à 10:12:59')
        ->and($month->start->date()->toDateTimeString())->toBe('2012-08-10 10:12:59')
        ->and($month->start->isCertain('millisecond'))->toBeFalse()
        ->and($compactMonth->text)->toBe('15juin 2016 20h')
        ->and($compactMonth->index)->toBe(26)
        ->and($compactMonth->start->date()->toDateTimeString())->toBe('2016-06-15 20:00:00')
        ->and($attachedWeekday->text)->toBe('Jeudi6/5/2013 de 7h à 10h')
        ->and($attachedWeekday->start->get('weekday'))->toBe(4)
        ->and($attachedWeekday->start->date()->toDateTimeString())->toBe('2013-05-06 07:00:00')
        ->and($attachedWeekday->end?->date()->toDateTimeString())->toBe('2013-05-06 10:00:00');
});

it('parses french random date and time expressions', function () {
    $french = Chrono::fr();

    expect($french->parseText('lundi 29/4/2013 630-930am')[0]->text)
        ->toBe('lundi 29/4/2013 630-930am')
        ->and($french->parseText('mercredi 1/5/2013 1115am')[0]->text)
        ->toBe('mercredi 1/5/2013 1115am')
        ->and($french->parseText('vendredi 3/5/2013 1230pm')[0]->text)
        ->toBe('vendredi 3/5/2013 1230pm')
        ->and($french->parseText('dimanche 6/5/2013  750am-910am')[0]->text)
        ->toBe('dimanche 6/5/2013  750am-910am')
        ->and($french->parseText('lundi 13/5/2013 630-930am')[0]->text)
        ->toBe('lundi 13/5/2013 630-930am')
        ->and($french->parseText('Vendredi 21/6/2013 2:30')[0]->text)
        ->toBe('Vendredi 21/6/2013 2:30')
        ->and($french->parseText('mardi 7/2/2013 1-230 pm')[0]->text)
        ->toBe('mardi 7/2/2013 1-230 pm')
        ->and($french->parseText('mardi 7/2/2013 1-23h0')[0]->text)
        ->toBe('mardi 7/2/2013 1-23h0')
        ->and($french->parseText('mardi 7/2/2013 1h-23h0m')[0]->text)
        ->toBe('mardi 7/2/2013 1h-23h0m')
        ->and($french->parseText('Lundi, 24/6/2013, 7:00pm - 8:30pm')[0]->text)
        ->toBe('Lundi, 24/6/2013, 7:00pm - 8:30pm')
        ->and($french->parseText('Jeudi6/5/2013 de 7h à 10h')[0]->text)
        ->toBe('Jeudi6/5/2013 de 7h à 10h')
        ->and($french->parseText('18h')[0]->text)
        ->toBe('18h')
        ->and($french->parseText('18-22h')[0]->text)
        ->toBe('18-22h')
        ->and($french->parseText('11h-13')[0]->text)
        ->toBe('11h-13')
        ->and($french->parseText('à 12h')[0]->text)
        ->toBe('à 12h')
        ->and($french->parseText('Mercredi, 3 juil 2013 14h')[0]->text)
        ->toBe('Mercredi, 3 juil 2013 14h')
        ->and($french->parseText('that I need to know or am I covered?'))
        ->toBe([]);
});
