<?php

use DirectoryTree\Chrono\Chrono;

it('merges dutch dates with times and date ranges', function () {
    $dutch = Chrono::nl();
    $dateTime = $dutch->parseText('Afspraak 10 augustus 2012 om 6:30', '2012-08-10')[0];
    $timeRange = $dutch->parseText('Afspraak 10 augustus 2012 om 6:30 - 8:45', '2012-08-10')[0];
    $dateRange = $dutch->parseText('Evenement 10 augustus 2012 tot 12 augustus 2012', '2012-08-10')[0];
    $dashRange = $dutch->parseText('Evenement woensdag - vrijdag', '2012-08-10')[0];
    $casualRange = $dutch->parseText('vandaag tot morgennamiddag', '2012-08-04 12:00')[0];

    expect($dateTime->text)->toBe('10 augustus 2012 om 6:30')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($dateTime->start->isCertain('hour'))->toBeTrue()
        ->and($timeRange->text)->toBe('10 augustus 2012 om 6:30 - 8:45')
        ->and($timeRange->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($timeRange->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($dateRange->text)->toBe('10 augustus 2012 tot 12 augustus 2012')
        ->and($dateRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dateRange->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dashRange->text)->toBe('woensdag - vrijdag')
        ->and($dashRange->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($dashRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($casualRange->text)->toBe('vandaag tot morgennamiddag')
        ->and($casualRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($casualRange->end?->date()->toDateTimeString())->toBe('2012-08-05 15:00:00');
});
