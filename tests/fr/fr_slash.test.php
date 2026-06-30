<?php

use DirectoryTree\Chrono\Chrono;

it('merges french slash date ranges', function () {
    $french = Chrono::fr();
    $dash = $french->parseText('Evénement 10/08/2012 - 12/08/2012', '2012-08-10')[0];
    $au = $french->parseText('Evénement 10/08/2012 au 12/08/2012', '2012-08-10')[0];

    expect($dash->text)->toBe('10/08/2012 - 12/08/2012')
        ->and($dash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($dash->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($dash->tags())->toContain('refiner/mergeDateRange')
        ->and($au->text)->toBe('10/08/2012 au 12/08/2012')
        ->and($au->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($au->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($au->tags())->toContain('refiner/mergeDateRange');
});

it('parses french slash dates', function () {
    $french = Chrono::fr();
    $explicit = $french->parseText('8/2/2016', '2012-08-10')[0];
    $withArticle = $french->parseText('le 8/2/2016', '2012-08-10')[0];
    $inferredYear = $french->parseText('le 8/2', '2012-08-10')[0];
    $weekday = $french->parseText('lundi 8/2/2016', '2012-08-10')[0];
    $twoDigitYear = $french->parseText('samedi 9/2/20 ', '2012-08-10')[0];

    expect($explicit->text)->toBe('8/2/2016')
        ->and($explicit->index)->toBe(0)
        ->and($explicit->start->get('year'))->toBe(2016)
        ->and($explicit->start->get('month'))->toBe(2)
        ->and($explicit->start->get('day'))->toBe(8)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($explicit->start->tags())->toContain('parser/SlashDateFormatParser')
        ->and($withArticle->text)->toBe('8/2/2016')
        ->and($withArticle->index)->toBe(3)
        ->and($withArticle->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($inferredYear->text)->toBe('8/2')
        ->and($inferredYear->index)->toBe(3)
        ->and($inferredYear->start->isCertain('year'))->toBeFalse()
        ->and($inferredYear->start->date()->toDateTimeString())->toBe('2013-02-08 12:00:00')
        ->and($weekday->text)->toBe('lundi 8/2/2016')
        ->and($weekday->index)->toBe(0)
        ->and($weekday->start->date()->toDateTimeString())->toBe('2016-02-08 12:00:00')
        ->and($weekday->start->isCertain('weekday'))->toBeTrue()
        ->and($weekday->start->tags())->toContain('parser/FRSlashDateParser')
        ->and($twoDigitYear->text)->toBe('samedi 9/2/20')
        ->and($twoDigitYear->index)->toBe(0)
        ->and($twoDigitYear->start->get('year'))->toBe(2020)
        ->and($twoDigitYear->start->get('weekday'))->toBe(6)
        ->and($twoDigitYear->start->date()->toDateTimeString())->toBe('2020-02-09 12:00:00')
        ->and($twoDigitYear->start->tags())->toContain('parser/FRSlashDateParser');
});
