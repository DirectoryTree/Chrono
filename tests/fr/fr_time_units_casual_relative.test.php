<?php

use Chrono\Chrono;

it('parses french relative durations', function () {
    $french = Chrono::fr();
    $timer = $french->parseText('régler une minuterie de 5 minutes', '2012-08-10 12:14')[0];
    $movingCar = $french->parseText('Dans 5 secondes une voiture va bouger', '2012-08-10 12:14')[0];
    $uppercaseMinutes = $french->parseText('Dans 5 Minutes une voiture doit être bougée', '2012-08-10 12:14')[0];
    $abbreviatedMinutes = $french->parseText('Dans 5 mins une voiture doit être bougée', '2012-08-10 12:14')[0];

    expect($french->parseText('On doit faire quelque chose dans 5 jours.', '2012-08-10')[0]->text)
        ->toBe('dans 5 jours')
        ->and($french->parseText('On doit faire quelque chose dans 5 jours.', '2012-08-10')[0]->index)
        ->toBe(28)
        ->and($french->parseDateText('On doit faire quelque chose dans 5 jours.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-15 00:00:00')
        ->and($french->parseText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')[0]->text)
        ->toBe('dans cinq jours')
        ->and($french->parseText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')[0]->index)
        ->toBe(28)
        ->and($french->parseDateText('On doit faire quelque chose dans cinq jours.', '2012-08-10 11:12')?->toDateTimeString())
        ->toBe('2012-08-15 11:12:00')
        ->and($french->parseDateText('dans 5 minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseDateText('pour 5 minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseDateText('en 1 heure', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 13:14:00')
        ->and($french->parseDateText('pendant deux heures et trois minutes', '2012-08-10 12:14')?->toDateTimeString())
        ->toBe('2012-08-10 14:17:00')
        ->and($timer->index)
        ->toBe(21)
        ->and($timer->text)
        ->toBe('de 5 minutes')
        ->and($french->parseText('Dans 5 minutes je vais rentrer chez moi', '2012-08-10 12:14')[0]->text)
        ->toBe('Dans 5 minutes')
        ->and($movingCar->text)
        ->toBe('Dans 5 secondes')
        ->and($movingCar->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:14:05')
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
        ->and($uppercaseMinutes->text)
        ->toBe('Dans 5 Minutes')
        ->and($uppercaseMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($abbreviatedMinutes->text)
        ->toBe('Dans 5 mins')
        ->and($abbreviatedMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 12:19:00')
        ->and($french->parseText('pendant deux heures', '2012-08-10 12:14')[0]->tags())
        ->toContain('result/relativeDate')
        ->and($french->parseText('pendant deux heures', '2012-08-10 12:14')[0]->tags())
        ->toContain('parser/FRTimeUnitWithinFormatParser');
});

it('parses french casual relative units', function () {
    $french = Chrono::fr();
    $nextWeek = $french->parseText('la semaine prochaine', '2017-05-12')[0];
    $twoWeeks = $french->parseText('les 2 prochaines semaines', '2017-05-12 18:11')[0];
    $previousDays = $french->parseText('les 30 jours précédents', '2017-05-12')[0];
    $pastHours = $french->parseText('les 24 heures passées', '2017-05-12 11:27')[0];
    $nextSeconds = $french->parseText('les 90 secondes suivantes', '2017-05-12 11:27:03')[0];
    $lastMinutes = $french->parseText('les huit dernieres minutes', '2017-05-12 11:27')[0];
    $quarter = $french->parseText('le dernier trimestre', '2017-05-12 11:27')[0];
    $year = $french->parseText("l'année prochaine", '2017-05-12 11:27')[0];

    expect($french->parseText("le mois d'avril"))
        ->toBe([])
        ->and($french->parseText("le mois d'avril prochain"))
        ->toBe([])
        ->and($nextWeek->text)
        ->toBe('la semaine prochaine')
        ->and($nextWeek->start->date()->toDateTimeString())
        ->toBe('2017-05-19 00:00:00')
        ->and($quarter->tags())->toContain('parser/FRTimeUnitRelativeFormatParser')
        ->and($twoWeeks->text)
        ->toBe('les 2 prochaines semaines')
        ->and($twoWeeks->start->date()->toDateTimeString())
        ->toBe('2017-05-26 18:11:00')
        ->and($french->parseDateText('les trois prochaines semaines', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-06-02 00:00:00')
        ->and($french->parseDateText('le mois dernier', '2017-05-12')?->toDateTimeString())
        ->toBe('2017-04-12 00:00:00')
        ->and($previousDays->text)
        ->toBe('les 30 jours précédents')
        ->and($previousDays->start->date()->toDateTimeString())
        ->toBe('2017-04-12 00:00:00')
        ->and($pastHours->text)
        ->toBe('les 24 heures passées')
        ->and($pastHours->start->date()->toDateTimeString())
        ->toBe('2017-05-11 11:27:00')
        ->and($nextSeconds->text)
        ->toBe('les 90 secondes suivantes')
        ->and($nextSeconds->start->date()->toDateTimeString())
        ->toBe('2017-05-12 11:28:33')
        ->and($lastMinutes->text)
        ->toBe('les huit dernieres minutes')
        ->and($lastMinutes->start->date()->toDateTimeString())
        ->toBe('2017-05-12 11:19:00')
        ->and($quarter->text)->toBe('le dernier trimestre')
        ->and($quarter->start->date()->toDateTimeString())->toBe('2017-02-12 11:27:00')
        ->and($quarter->start->isCertain('month'))->toBeFalse()
        ->and($quarter->start->isCertain('day'))->toBeFalse()
        ->and($quarter->start->isCertain('hour'))->toBeFalse()
        ->and($quarter->start->isCertain('minute'))->toBeFalse()
        ->and($quarter->start->isCertain('second'))->toBeFalse()
        ->and($year->text)->toBe("l'année prochaine")
        ->and($year->start->date()->toDateTimeString())->toBe('2018-05-12 11:27:00')
        ->and($year->start->isCertain('month'))->toBeFalse()
        ->and($year->start->isCertain('day'))->toBeFalse()
        ->and($year->start->isCertain('hour'))->toBeFalse()
        ->and($year->start->isCertain('minute'))->toBeFalse()
        ->and($year->start->isCertain('second'))->toBeFalse();
});
