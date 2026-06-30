<?php

use DirectoryTree\Chrono\Chrono;

it('parses french past relative durations', function () {
    $french = Chrono::fr();
    $tenDays = $french->parseText('il y a 10 jours, on a fait quelque chose', '2012-08-10 13:30')[0];
    $fifteenMinutes = $french->parseText('il y a 15 minutes', '2012-08-10 12:14')[0];
    $spacedHours = $french->parseText('   il y a    12 heures', '2012-08-10 12:14')[0];
    $sentenceHours = $french->parseText("il y a 12 heures il s'est passé quelque chose", '2012-08-10 12:14')[0];
    $oneWeek = $french->parseText('il y a une semaine, on a fait quelque chose', '2012-08-03 08:34')[0];

    expect($french->parseText('il y a 5 jours, on a fait quelque chose', '2012-08-10')[0]->text)
        ->toBe('il y a 5 jours')
        ->and($french->parseText('il y a 5 jours, on a fait quelque chose', '2012-08-10')[0]->index)
        ->toBe(0)
        ->and($french->parseText('il y a 5 jours, on a fait quelque chose', '2012-08-10')[0]->tags())
        ->toContain('parser/FRTimeUnitAgoFormatParser')
        ->and($french->parseDateText('il y a 5 jours, on a fait quelque chose', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 00:00:00')
        ->and($tenDays->text)
        ->toBe('il y a 10 jours')
        ->and($tenDays->index)
        ->toBe(0)
        ->and($tenDays->start->date()->toDateTimeString())
        ->toBe('2012-07-31 13:30:00')
        ->and($fifteenMinutes->text)
        ->toBe('il y a 15 minutes')
        ->and($fifteenMinutes->start->date()->toDateTimeString())
        ->toBe('2012-08-10 11:59:00')
        ->and($spacedHours->index)
        ->toBe(3)
        ->and($spacedHours->text)
        ->toBe('il y a    12 heures')
        ->and($spacedHours->start->date()->toDateTimeString())
        ->toBe('2012-08-10 00:14:00')
        ->and($sentenceHours->text)
        ->toBe('il y a 12 heures')
        ->and($sentenceHours->start->date()->toDateTimeString())
        ->toBe('2012-08-10 00:14:00')
        ->and($french->parseDateText('il y a 5 mois, on a fait quelque chose', '2012-10-10')?->toDateTimeString())
        ->toBe('2012-05-10 00:00:00')
        ->and($french->parseDateText('il y a 5 ans, on a fait quelque chose', '2012-08-10 22:22')?->toDateTimeString())
        ->toBe('2007-08-10 22:22:00')
        ->and($oneWeek->text)
        ->toBe('il y a une semaine')
        ->and($oneWeek->start->date()->toDateTimeString())
        ->toBe('2012-07-27 08:34:00');
});
