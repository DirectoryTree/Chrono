<?php

use Chrono\Chrono;

it('parses german relative durations', function () {
    $german = Chrono::de();
    $fiveDays = $german->parseText('Wir müssen etwas in 5 Tagen erledigen.', '2012-08-10')[0];
    $fiveDaysWord = $german->parseText('Wir müssen etwas in fünf Tagen erledigen.', '2012-08-10 11:12')[0];
    $timer = $german->parseText('starte einen Timer für 5 Minuten', '2012-08-10 12:14')[0];
    $home = $german->parseText('In 5 Minuten gehe ich nach Hause', '2012-08-10 12:14')[0];
    $seconds = $german->parseText('In 5 Sekunden wird ein Auto fahren', '2012-08-10 12:14')[0];
    $abbreviated = $german->parseText('In 5 Min wird ein Auto fahren', '2012-08-10 12:14')[0];

    expect($fiveDays->text)
        ->toBe('in 5 Tagen')
        ->and($fiveDays->index)->toBe(17)
        ->and($fiveDays->tags())->toContain('result/relativeDate')
        ->and($fiveDays->tags())->toContain('parser/DETimeUnitRelativeFormatParser')
        ->and($fiveDays->start->date()->toDateTimeString())
        ->toBe('2012-08-15 00:00:00')
        ->and($fiveDaysWord->text)->toBe('in fünf Tagen')
        ->and($fiveDaysWord->index)->toBe(17)
        ->and($fiveDaysWord->start->date()->toDateTimeString())
        ->toBe('2012-08-15 11:12:00')
        ->and($german->parseDateText('in 5 Minuten', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($german->parseDateText('für 5 minuten', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($german->parseDateText('in einer Stunde', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($timer->index)->toBe(19)
        ->and($timer->text)
        ->toBe('für 5 Minuten')
        ->and($home->text)->toBe('In 5 Minuten')
        ->and($home->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($seconds->text)->toBe('In 5 Sekunden')
        ->and($seconds->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:14:05')
        ->and($german->parseDateText('in zwei Wochen', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-24 12:14:00')
        ->and($german->parseDateText('in einem Monat', '2012-08-10 07:14')?->toDateTimeString())
        ->toBe('2012-09-10 07:14:00')
        ->and($german->parseDateText('in einigen Monaten', '2012-07-10 22:14')?->toDateTimeString())
        ->toBe('2012-10-10 22:14:00')
        ->and($german->parseDateText('in einem Jahr', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($german->parseDateText('in 20 Jahren', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2032-08-10 12:14:00')
        ->and($abbreviated->text)->toBe('In 5 Min')
        ->and($abbreviated->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00');
});

it('parses german casual relative units', function () {
    $german = Chrono::de();
    $days = $german->parseText('in den 30 vorangegangenen Tagen', '2017-05-12')[0];
    $hours = $german->parseText('die vergangenen 24 Stunden', '2017-05-12 11:27')[0];
    $seconds = $german->parseText('in den folgenden 90 sekunden', '2017-05-12 11:27:03')[0];
    $minutes = $german->parseText('die letzten acht Minuten', '2017-05-12 11:27')[0];
    $quarter = $german->parseText('letztes Quartal', '2017-05-12 11:27')[0];
    $year = $german->parseText('kommendes Jahr', '2017-05-12 11:27')[0];

    expect($german->parseDateText('kommende Woche', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-05-19 00:00:00')
        ->and($german->parseDateText('in drei Wochen', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-06-02 00:00:00')
        ->and($german->parseDateText('letzten Monat', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-04-12 00:00:00')
        ->and($days->text)->toBe('30 vorangegangenen Tagen')
        ->and($days->tags())->toContain('result/relativeDate')
        ->and($days->tags())->toContain('parser/DETimeUnitRelativeFormatParser')
        ->and($days->start->date()->toDateTimeString())->toBe('2017-04-12 00:00:00')
        ->and($hours->text)->toBe('vergangenen 24 Stunden')
        ->and($hours->start->date()->toDateTimeString())->toBe('2017-05-11 11:27:00')
        ->and($seconds->text)->toBe('folgenden 90 sekunden')
        ->and($seconds->start->date()->toDateTimeString())->toBe('2017-05-12 11:28:33')
        ->and($minutes->text)->toBe('letzten acht Minuten')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2017-05-12 11:19:00')
        ->and($quarter->text)->toBe('letztes Quartal')
        ->and($quarter->start->date()->toDateTimeString())->toBe('2017-02-12 11:27:00')
        ->and($quarter->start->isCertain('month'))->toBeFalse()
        ->and($quarter->start->isCertain('day'))->toBeFalse()
        ->and($quarter->start->isCertain('hour'))->toBeFalse()
        ->and($year->text)->toBe('kommendes Jahr')
        ->and($year->start->date()->toDateTimeString())->toBe('2018-05-12 11:27:00')
        ->and($year->start->isCertain('month'))->toBeFalse()
        ->and($year->start->isCertain('day'))->toBeFalse()
        ->and($year->start->isCertain('hour'))->toBeFalse()
        ->and($year->start->isCertain('minute'))->toBeFalse()
        ->and($year->start->isCertain('second'))->toBeFalse()
        ->and($german->parseText('Letzte Aktualisierun 03/12/2025')[0]->text)
        ->toBe('03/12/2025')
        ->and($german->parseText('Letzte Aktualisierun 03/12/2025')[0]->start->date()->toDateTimeString())
        ->toBe('2025-12-03 12:00:00');
});
