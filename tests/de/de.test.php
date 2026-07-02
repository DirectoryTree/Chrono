<?php

use DirectoryTree\Chrono\Chrono;

it('parses german casual date ranges and daypart variants', function () {
    $german = Chrono::de();
    $earlyRange = $german->parse('Der Event ist heute - nächsten Freitag', '2012-08-04 12:00')[0];
    $sameDayRange = $german->parse('Der Event ist heute - nächsten Freitag', '2012-08-10 12:00')[0];

    expect($earlyRange->text)->toBe('heute - nächsten Freitag')
        ->and($earlyRange->start->date()->toDateTimeString())->toBe('2012-08-04 12:00:00')
        ->and($earlyRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($sameDayRange->end?->date()->toDateTimeString())->toBe('2012-08-17 12:00:00')
        ->and($german->date('heute Nacht', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 22:00:00')
        ->and($german->date('heute Nacht um 20 Uhr', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($german->date('heute Abend um 8', '2012-01-01 12:00')?->toDateTimeString())
        ->toBe('2012-01-01 20:00:00')
        ->and($german->date('gestern Nachmittag', '2016-10-01')?->toDateTimeString())
        ->toBe('2016-09-30 15:00:00')
        ->and($german->date('morgen Morgen', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-10-02 06:00:00')
        ->and($german->date('uebermorgen Abend', '2016-10-01 08:00')?->toDateTimeString())
        ->toBe('2016-10-03 18:00:00')
        ->and($german->date('vorgestern Vormittag', '2016-10-01')?->toDateTimeString())
        ->toBe('2016-09-29 09:00:00');
});
