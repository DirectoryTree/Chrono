<?php

use Chrono\Chrono;

it('parses german time expressions', function () {
    $german = Chrono::de();
    $simple = $german->parseText('18:10', '2012-08-10')[0];
    $morning = $german->parseText('um 7 morgens', '2012-08-10')[0];
    $night = $german->parseText('um 8 Uhr in der Nacht', '2012-08-10')[0];
    $earlyNight = $german->parseText('um 5 Uhr in der Nacht', '2012-08-10')[0];
    $range = $german->parseText('18:10 - 22.32', '2012-08-10')[0];
    $tildeRange = $german->parseText('18:10 ~ 22.32', '2012-08-10')[0];
    $milliseconds = $german->parseText('18:10:30.123', '2012-08-10')[0];
    $vonRange = $german->parseText(' von 6:30 bis 23:00 ', '2012-08-10')[0];
    $hRange = $german->parseText(' von 6h30 bis 23h00 ', '2012-08-10')[0];
    $suffixRange = $german->parseText(' von 6h30 morgens bis 11 am Abend', '2012-08-10')[0];
    $specific = $german->parseText('8h10m00s Uhr', '2012-08-10')[0];

    expect($simple->text)->toBe('18:10')
        ->and($simple->index)->toBe(0)
        ->and($simple->start->date()->toDateTimeString())->toBe('2012-08-10 18:10:00')
        ->and($simple->start->isCertain('day'))->toBeFalse()
        ->and($simple->start->isCertain('month'))->toBeFalse()
        ->and($simple->start->isCertain('year'))->toBeFalse()
        ->and($simple->start->isCertain('hour'))->toBeTrue()
        ->and($simple->start->isCertain('minute'))->toBeTrue()
        ->and($simple->start->isCertain('second'))->toBeFalse()
        ->and($simple->start->isCertain('millisecond'))->toBeFalse()
        ->and($simple->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($simple->start->get('timezoneOffset'))->toBeNull()
        ->and($german->parseDateText('um 14 Uhr', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 14:00:00')
        ->and($german->parseDateText('um 16h', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 16:00:00')
        ->and($specific->text)->toBe('8h10m00s Uhr')
        ->and($specific->start->date()->toDateTimeString())->toBe('2012-08-10 08:10:00')
        ->and($specific->start->tags())->toContain('parser/DESpecificTimeExpressionParser')
        ->and($specific->start->isCertain('second'))->toBeTrue()
        ->and($morning->start->date()->toDateTimeString())->toBe('2012-08-10 07:00:00')
        ->and($morning->start->get('meridiem')->value)->toBe(0)
        ->and($morning->start->isCertain('meridiem'))->toBeTrue()
        ->and($german->parseText('11:00 Uhr vormittags', '2012-08-10')[0]->start->get('meridiem')->value)->toBe(0)
        ->and($german->parseDateText('um 8 Uhr nachmittags', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($night->start->date()->toDateTimeString())->toBe('2012-08-10 20:00:00')
        ->and($night->start->get('meridiem')->value)->toBe(1)
        ->and($earlyNight->start->date()->toDateTimeString())->toBe('2012-08-10 05:00:00')
        ->and($earlyNight->start->get('meridiem')->value)->toBe(0)
        ->and($range->text)->toBe('18:10 - 22.32')
        ->and($range->index)->toBe(0)
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 18:10:00')
        ->and($range->start->get('hour'))->toBe(18)
        ->and($range->start->get('minute'))->toBe(10)
        ->and($range->start->isCertain('day'))->toBeFalse()
        ->and($range->start->isCertain('month'))->toBeFalse()
        ->and($range->start->isCertain('year'))->toBeFalse()
        ->and($range->start->isCertain('hour'))->toBeTrue()
        ->and($range->start->isCertain('minute'))->toBeTrue()
        ->and($range->start->isCertain('second'))->toBeFalse()
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 22:32:00')
        ->and($range->end?->get('hour'))->toBe(22)
        ->and($range->end?->get('minute'))->toBe(32)
        ->and($range->end?->isCertain('day'))->toBeFalse()
        ->and($range->end?->isCertain('month'))->toBeFalse()
        ->and($range->end?->isCertain('year'))->toBeFalse()
        ->and($range->end?->isCertain('hour'))->toBeTrue()
        ->and($range->end?->isCertain('minute'))->toBeTrue()
        ->and($range->end?->isCertain('second'))->toBeFalse()
        ->and($tildeRange->text)->toBe('18:10 ~ 22.32')
        ->and($tildeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 22:32:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2012-08-10 18:10:30.123')
        ->and($german->parseText('Jahr 2020', '2012-08-10'))
        ->toBe([])
        ->and($vonRange->text)->toBe('von 6:30 bis 23:00')
        ->and($vonRange->index)->toBe(1)
        ->and($vonRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($vonRange->start->get('meridiem')->value)->toBe(0)
        ->and($vonRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($vonRange->end?->get('meridiem')->value)->toBe(1)
        ->and($hRange->text)->toBe('von 6h30 bis 23h00')
        ->and($hRange->index)->toBe(1)
        ->and($hRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($hRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($suffixRange->text)->toBe('von 6h30 morgens bis 11 am Abend')
        ->and($suffixRange->index)->toBe(1)
        ->and($suffixRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($suffixRange->end?->date()->toDateTimeString())->toBe('2012-08-10 23:00:00')
        ->and($german->parseText('am Mittag')[0]->text)->toBe('Mittag')
        ->and($german->parseDateText('am Mittag', '2012-08-10')?->toDateTimeString())->toBe('2012-08-10 12:00:00');
});

it('parses german random time expressions', function () {
    $german = Chrono::de();

    expect($german->parseText('um 12')[0]->text)
        ->toBe('um 12')
        ->and($german->parseText('am Mittag')[0]->text)
        ->toBe('Mittag')
        ->and($german->parseText('am Freitag um 14 Uhr cetteln wir etwas an', '2016-02-28')[0]->text)
        ->toBe('am Freitag um 14 Uhr')
        ->and($german->parseText('am Freitag um 14 Uhr cetteln wir etwas an', '2016-02-28')[0]->start->isCertain('timezoneOffset'))
        ->toBeFalse()
        ->and($german->parseText('Freitag um 14 Uhr CET', '2016-05-28')[0]->text)
        ->toBe('Freitag um 14 Uhr CET')
        ->and($german->parseText('Freitag um 14 Uhr CET', '2016-05-28')[0]->start->get('timezoneOffset'))
        ->toBe(120);
});
