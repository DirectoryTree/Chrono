<?php

use DirectoryTree\Chrono\Chrono;

it('parses finnish weekdays', function () {
    $finnish = Chrono::fi();
    $merged = $finnish->parseText('maanantaina 10. elokuuta 2012', '2012-08-10')[0];
    $monday = $finnish->parseText('maanantai', '2012-08-09')[0];
    $mondayEssive = $finnish->parseText('maanantaina', '2012-08-09')[0];
    $nextMonday = $finnish->parseText('ensi maanantai', '2012-08-09')[0];
    $lastMonday = $finnish->parseText('viime maanantai', '2012-08-09')[0];

    expect($finnish->parseText('Nähdään maanantaina', '2012-08-10')[0]->text)
        ->toBe('maanantaina')
        ->and($finnish->parseText('Nähdään maanantaina', '2012-08-10')[0]->start->tags())->toContain('parser/FIWeekdayParser')
        ->and($finnish->parseDateText('Nähdään maanantaina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($finnish->parseDateText('Nähdään ensi maanantaina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-13 12:00:00')
        ->and($finnish->parseDateText('Nähdään viime sunnuntaina', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-05 12:00:00')
        ->and($monday->index)->toBe(0)
        ->and($monday->text)->toBe('maanantai')
        ->and($monday->start->get('year'))->toBe(2012)
        ->and($monday->start->get('month'))->toBe(8)
        ->and($monday->start->get('day'))->toBe(6)
        ->and($monday->start->get('weekday'))->toBe(1)
        ->and($mondayEssive->index)->toBe(0)
        ->and($mondayEssive->text)->toBe('maanantaina')
        ->and($mondayEssive->start->get('year'))->toBe(2012)
        ->and($mondayEssive->start->get('month'))->toBe(8)
        ->and($mondayEssive->start->get('day'))->toBe(6)
        ->and($mondayEssive->start->get('weekday'))->toBe(1)
        ->and($nextMonday->index)->toBe(0)
        ->and($nextMonday->text)->toBe('ensi maanantai')
        ->and($nextMonday->start->get('year'))->toBe(2012)
        ->and($nextMonday->start->get('month'))->toBe(8)
        ->and($nextMonday->start->get('day'))->toBe(13)
        ->and($nextMonday->start->get('weekday'))->toBe(1)
        ->and($lastMonday->index)->toBe(0)
        ->and($lastMonday->text)->toBe('viime maanantai')
        ->and($lastMonday->start->get('year'))->toBe(2012)
        ->and($lastMonday->start->get('month'))->toBe(8)
        ->and($lastMonday->start->get('day'))->toBe(6)
        ->and($lastMonday->start->get('weekday'))->toBe(1)
        ->and($finnish->parseText('sunnuntai', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($finnish->parseText('tiistai', '2012-08-09')[0]->start->get('weekday'))->toBe(2)
        ->and($finnish->parseText('keskiviikko', '2012-08-09')[0]->start->get('weekday'))->toBe(3)
        ->and($finnish->parseText('torstai', '2012-08-09')[0]->start->get('weekday'))->toBe(4)
        ->and($finnish->parseText('perjantai', '2012-08-09')[0]->start->get('weekday'))->toBe(5)
        ->and($finnish->parseText('lauantai', '2012-08-09')[0]->start->get('weekday'))->toBe(6)
        ->and($finnish->parseText('ma', '2012-08-09')[0]->start->get('weekday'))->toBe(1)
        ->and($finnish->parseText('pe', '2012-08-09')[0]->start->get('weekday'))->toBe(5)
        ->and($finnish->parseText('su', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($merged->text)->toBe('maanantaina 10. elokuuta 2012')
        ->and($merged->start->isCertain('weekday'))->toBeTrue()
        ->and($merged->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00');
});
