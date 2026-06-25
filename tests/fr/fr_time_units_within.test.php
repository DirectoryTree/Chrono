<?php

use Chrono\Chrono;

it('parses french within time unit expressions', function () {
    $french = Chrono::fr();

    $fiveDays = $french->parseText('On doit faire quelque chose dans 5 jours.', '2012-08-10')[0];
    $fiveDaysText = $french->parseText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')[0];
    $timer = $french->parseText('régler une minuterie de 5 minutes', '2012-08-10 12:14')[0];
    $minutes = $french->parseText('Dans 5 minutes je vais rentrer chez moi', '2012-08-10 12:14')[0];
    $seconds = $french->parseText('Dans 5 secondes une voiture va bouger', '2012-08-10 12:14')[0];
    $uppercaseMinutes = $french->parseText('Dans 5 Minutes une voiture doit être bougée', '2012-08-10 12:14')[0];
    $abbreviatedMinutes = $french->parseText('Dans 5 mins une voiture doit être bougée', '2012-08-10 12:14')[0];

    expect($fiveDays->index)->toBe(28)
        ->and($fiveDays->text)->toBe('dans 5 jours')
        ->and($fiveDays->start->date()->toDateString())->toBe('2012-08-15')
        ->and($fiveDaysText->index)->toBe(28)
        ->and($fiveDaysText->text)->toBe('dans cinq jours')
        ->and($fiveDaysText->start->date()->format('Y-m-d H:i'))->toBe('2012-08-15 11:12')
        ->and($french->parseDateText('dans 5 minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseDateText('pour 5 minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseDateText('en 1 heure', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($timer->index)->toBe(22)
        ->and($timer->text)->toBe('de 5 minutes')
        ->and($timer->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($minutes->index)->toBe(0)
        ->and($minutes->text)->toBe('Dans 5 minutes')
        ->and($minutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($seconds->index)->toBe(0)
        ->and($seconds->text)->toBe('Dans 5 secondes')
        ->and($seconds->start->date()->format('Y-m-d H:i:s'))->toBe('2012-08-10 12:14:05')
        ->and($french->parseDateText('dans deux semaines', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-24 12:14:00')
        ->and($french->parseDateText('dans un mois', '2012-08-10 07:14')?->toDateTimeString())
        ->toBe('2012-09-10 07:14:00')
        ->and($french->parseDateText('dans quelques mois', '2012-07-10 22:14')?->toDateTimeString())
        ->toBe('2012-10-10 22:14:00')
        ->and($french->parseDateText('en une année', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($french->parseDateText('dans une Année', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2013-08-10 12:14:00')
        ->and($uppercaseMinutes->index)->toBe(0)
        ->and($uppercaseMinutes->text)->toBe('Dans 5 Minutes')
        ->and($uppercaseMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($abbreviatedMinutes->index)->toBe(0)
        ->and($abbreviatedMinutes->text)->toBe('Dans 5 mins')
        ->and($abbreviatedMinutes->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00');
});
