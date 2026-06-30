<?php

use DirectoryTree\Chrono\Chrono;

it('parses german weekdays', function () {
    $german = Chrono::de();
    $monday = $german->parseText('Montag', '2012-08-09')[0];
    $lastFriday = $german->parseText('Die Deadline war letzten Freitag...', '2012-08-09')[0];
    $nextFriday = $german->parseText('Treffen wir uns am Freitag nächste Woche', '2015-04-18')[0];
    $nextTuesday = $german->parseText('Ich habe vor, am Dienstag nächste Woche freizunehmen', '2015-04-18')[0];
    $range = $german->parseText('diesen Freitag bis diesen Montag', '2016-08-04', ['forwardDate' => true])[0];
    $monthOverlap = $german->parseText('Sonntag, den 7. Dezember 2014', '2012-08-09')[0];
    $dashOverlap = $german->parseText('Sonntag 7.12.2014', '2012-08-09')[0];

    expect($monday->text)->toBe('Montag')
        ->and($monday->index)->toBe(0)
        ->and($monday->start->date()->toDateTimeString())
        ->toBe('2012-08-06 12:00:00')
        ->and($monday->start->tags())->toContain('parser/DEWeekdayParser')
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($german->parseText('am Donnerstag', '2012-08-09')[0]->text)->toBe('am Donnerstag')
        ->and($german->parseDateText('am Donnerstag', '2012-08-09')?->toDateTimeString())
        ->toBe('2012-08-09 12:00:00')
        ->and($german->parseText('Sonntag', '2012-08-09')[0]->text)->toBe('Sonntag')
        ->and($german->parseText('Sonntag', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($german->parseDateText('Sonntag', '2012-08-09')?->toDateTimeString())
        ->toBe('2012-08-12 12:00:00')
        ->and($lastFriday->index)->toBe(17)
        ->and($lastFriday->text)
        ->toBe('letzten Freitag')
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($lastFriday->start->date()->toDateTimeString())
        ->toBe('2012-08-03 12:00:00')
        ->and($nextFriday->index)->toBe(16)
        ->and($nextFriday->text)
        ->toBe('am Freitag nächste Woche')
        ->and($nextFriday->start->date()->toDateTimeString())
        ->toBe('2015-04-24 12:00:00')
        ->and($nextTuesday->index)->toBe(14)
        ->and($nextTuesday->text)
        ->toBe('am Dienstag nächste Woche')
        ->and($nextTuesday->start->get('weekday'))->toBe(2)
        ->and($nextTuesday->start->date()->toDateTimeString())
        ->toBe('2015-04-21 12:00:00')
        ->and($range->index)->toBe(0)
        ->and($range->text)->toBe('diesen Freitag bis diesen Montag')
        ->and($range->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($range->start->get('weekday'))->toBe(5)
        ->and($range->start->isCertain('day'))->toBeFalse()
        ->and($range->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($range->end?->get('weekday'))->toBe(1)
        ->and($range->end?->isCertain('day'))->toBeFalse()
        ->and($monthOverlap->text)->toBe('Sonntag, den 7. Dezember 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($dashOverlap->text)->toBe('Sonntag 7.12.2014')
        ->and($dashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($dashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('weekday'))->toBeTrue();
});

it('parses german weekdays with upstream-shaped certainty', function () {
    $german = Chrono::de();
    $monday = $german->parseText('Montag', '2012-08-09')[0];
    $thursday = $german->parseText('am Donnerstag', '2012-08-09')[0];
    $sunday = $german->parseText('Sonntag', '2012-08-09')[0];
    $lastFriday = $german->parseText('Die Deadline war letzten Freitag...', '2012-08-09')[0];
    $nextFriday = $german->parseText('Treffen wir uns am Freitag nächste Woche', '2015-04-18')[0];
    $nextTuesday = $german->parseText('Ich habe vor, am Dienstag nächste Woche freizunehmen', '2015-04-18')[0];
    $range = $german->parseText('diesen Freitag bis diesen Montag', '2016-08-04', ['forwardDate' => true])[0];
    $monthOverlap = $german->parseText('Sonntag, den 7. Dezember 2014', '2012-08-09')[0];
    $dashOverlap = $german->parseText('Sonntag 7.12.2014', '2012-08-09')[0];

    expect($monday->index)->toBe(0)
        ->and($monday->text)->toBe('Montag')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($monday->start->isCertain('day'))->toBeFalse()
        ->and($monday->start->isCertain('month'))->toBeFalse()
        ->and($monday->start->isCertain('year'))->toBeFalse()
        ->and($monday->start->isCertain('weekday'))->toBeTrue()
        ->and($monday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($thursday->text)->toBe('am Donnerstag')
        ->and($thursday->start->get('day'))->toBe(9)
        ->and($thursday->start->get('weekday'))->toBe(4)
        ->and($thursday->start->isCertain('day'))->toBeFalse()
        ->and($sunday->text)->toBe('Sonntag')
        ->and($sunday->start->get('day'))->toBe(12)
        ->and($sunday->start->get('weekday'))->toBe(0)
        ->and($lastFriday->index)->toBe(17)
        ->and($lastFriday->text)->toBe('letzten Freitag')
        ->and($lastFriday->start->date()->toDateTimeString())->toBe('2012-08-03 12:00:00')
        ->and($lastFriday->start->get('weekday'))->toBe(5)
        ->and($nextFriday->index)->toBe(16)
        ->and($nextFriday->text)->toBe('am Freitag nächste Woche')
        ->and($nextFriday->start->date()->toDateTimeString())->toBe('2015-04-24 12:00:00')
        ->and($nextFriday->start->get('weekday'))->toBe(5)
        ->and($nextTuesday->index)->toBe(14)
        ->and($nextTuesday->text)->toBe('am Dienstag nächste Woche')
        ->and($nextTuesday->start->date()->toDateTimeString())->toBe('2015-04-21 12:00:00')
        ->and($nextTuesday->start->get('weekday'))->toBe(2)
        ->and($range->index)->toBe(0)
        ->and($range->text)->toBe('diesen Freitag bis diesen Montag')
        ->and($range->start->date()->toDateTimeString())->toBe('2016-08-05 12:00:00')
        ->and($range->start->get('weekday'))->toBe(5)
        ->and($range->start->isCertain('day'))->toBeFalse()
        ->and($range->start->isCertain('month'))->toBeFalse()
        ->and($range->start->isCertain('year'))->toBeFalse()
        ->and($range->start->isCertain('weekday'))->toBeTrue()
        ->and($range->end?->date()->toDateTimeString())->toBe('2016-08-08 12:00:00')
        ->and($range->end?->get('weekday'))->toBe(1)
        ->and($range->end?->isCertain('day'))->toBeFalse()
        ->and($range->end?->isCertain('month'))->toBeFalse()
        ->and($range->end?->isCertain('year'))->toBeFalse()
        ->and($range->end?->isCertain('weekday'))->toBeTrue()
        ->and($monthOverlap->index)->toBe(0)
        ->and($monthOverlap->text)->toBe('Sonntag, den 7. Dezember 2014')
        ->and($monthOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($monthOverlap->start->isCertain('day'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('month'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('year'))->toBeTrue()
        ->and($monthOverlap->start->isCertain('weekday'))->toBeTrue()
        ->and($dashOverlap->text)->toBe('Sonntag 7.12.2014')
        ->and($dashOverlap->start->date()->toDateTimeString())->toBe('2014-12-07 12:00:00')
        ->and($dashOverlap->start->isCertain('day'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('month'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('year'))->toBeTrue()
        ->and($dashOverlap->start->isCertain('weekday'))->toBeTrue();
});

it('parses german timezones and weekday times', function () {
    $german = Chrono::de();
    $cet = $german->parseText('um 14 Uhr CET', '2016-02-28')[0];
    $cest = $german->parseText('14 Uhr cet', '2016-05-28')[0];
    $falsePositive = $german->parseText('am Freitag um 14 Uhr cetteln wir etwas an', '2016-02-28')[0];
    $weekdayTime = $german->parseText('Freitag um 14 Uhr CET', '2016-05-28')[0];
    $plain = $german->parseText('um 14 Uhr', '2016-02-28')[0];

    expect($plain->text)->toBe('um 14 Uhr')
        ->and($plain->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($plain->start->get('timezoneOffset'))->toBeNull()
        ->and($cet->text)->toBe('um 14 Uhr CET')
        ->and($cet->start->tags())->toContain('parser/DETimeExpressionExtensionParser')
        ->and($cet->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cet->start->get('timezoneOffset'))->toBe(60)
        ->and($cest->text)->toBe('14 Uhr cet')
        ->and($cest->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($cest->start->get('timezoneOffset'))->toBe(120)
        ->and($falsePositive->text)->toBe('am Freitag um 14 Uhr')
        ->and($falsePositive->start->date()->toDateTimeString())->toBe('2016-02-26 14:00:00')
        ->and($falsePositive->start->isCertain('timezoneOffset'))->toBeFalse()
        ->and($falsePositive->start->get('timezoneOffset'))->toBeNull()
        ->and($weekdayTime->text)->toBe('Freitag um 14 Uhr CET')
        ->and($weekdayTime->start->date()->toDateTimeString())->toBe('2016-05-27 14:00:00')
        ->and($weekdayTime->start->isCertain('timezoneOffset'))->toBeTrue()
        ->and($weekdayTime->start->get('timezoneOffset'))->toBe(120);
});
