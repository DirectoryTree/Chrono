<?php

use DirectoryTree\Chrono\Chrono;

it('parses dutch month name dates and ranges', function () {
    $dutch = Chrono::nl();
    $range = $dutch->parse('Evenement 10 - 25 maart 2019', '2012-08-10')[0];
    $explicitRange = $dutch->parse('10 augustus - 12 september 2013', '2012-08-10')[0];
    $beforeChrist = $dutch->parse('10 augustus 234 voor Christus', '2012-08-10')[0];
    $afterChrist = $dutch->parse('10 augustus 88 na Christus', '2012-08-10')[0];

    expect($dutch->parse('Afspraak 1 januari 2019', '2012-08-10')[0]->text)
        ->toBe('1 januari 2019')
        ->and($dutch->parse('Afspraak 1 januari 2019', '2012-08-10')[0]->start->tags())->toContain('parser/NLMonthNameMiddleEndianParser')
        ->and($dutch->date('Afspraak 1 januari 2019', '2012-08-10')?->toDateTimeString())
        ->toBe('2019-01-01 12:00:00')
        ->and($dutch->date('Afspraak 12de juli 2013', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-07-12 12:00:00')
        ->and($dutch->date('Afspraak eerste november 2013', '2012-08-10')?->toDateTimeString())
        ->toBe('2013-11-01 12:00:00')
        ->and($range->text)->toBe('10 - 25 maart 2019')
        ->and($range->start->date()->toDateTimeString())->toBe('2019-03-10 12:00:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2019-03-25 12:00:00')
        ->and($dutch->parse('10 augustus 2012', '2012-08-10')[0]->text)->toBe('10 augustus 2012')
        ->and($dutch->date('3 februari 82', '2012-08-10')?->toDateTimeString())->toBe('1982-02-03 12:00:00')
        ->and($beforeChrist->index)->toBe(0)
        ->and($beforeChrist->text)->toBe('10 augustus 234 voor Christus')
        ->and($beforeChrist->start->get('year'))->toBe(-234)
        ->and($beforeChrist->start->get('month'))->toBe(8)
        ->and($beforeChrist->start->get('day'))->toBe(10)
        ->and($afterChrist->index)->toBe(0)
        ->and($afterChrist->text)->toBe('10 augustus 88 na Christus')
        ->and($afterChrist->start->get('year'))->toBe(88)
        ->and($afterChrist->start->get('month'))->toBe(8)
        ->and($afterChrist->start->get('day'))->toBe(10)
        ->and($dutch->parse('Zon 15 Sept', '2013-08-10')[0]->text)->toBe('Zon 15 Sept')
        ->and($dutch->date('Zon 15 Sept', '2013-08-10')?->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($dutch->parse('ZON 15 SEPT', '2013-08-10')[0]->text)->toBe('ZON 15 SEPT')
        ->and($dutch->date('ZON 15 SEPT', '2013-08-10')?->toDateTimeString())->toBe('2013-09-15 12:00:00')
        ->and($dutch->parse('De deadline is dinsdag, 10 januari', '2012-08-10')[0]->text)->toBe('dinsdag, 10 januari')
        ->and($dutch->parse('De deadline is dinsdag, 10 januari', '2012-08-10')[0]->start->get('weekday'))->toBe(2)
        ->and($dutch->parse('De deadline is di, 10 januari', '2012-08-10')[0]->text)->toBe('di, 10 januari')
        ->and($dutch->parse('De deadline is di, 10 januari', '2012-08-10')[0]->start->get('weekday'))->toBe(2)
        ->and($dutch->date('31ste maart 2016', '2012-08-10')?->toDateTimeString())->toBe('2016-03-31 12:00:00')
        ->and($dutch->date('23ste februari 2016', '2012-08-10')?->toDateTimeString())->toBe('2016-02-23 12:00:00')
        ->and($dutch->parse('10 - 22 augustus 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($dutch->parse('10 tot 22 augustus 2012', '2012-08-10')[0]->end?->date()->toDateTimeString())->toBe('2012-08-22 12:00:00')
        ->and($explicitRange->text)->toBe('10 augustus - 12 september 2013')
        ->and($explicitRange->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($explicitRange->end?->date()->toDateTimeString())->toBe('2013-09-12 12:00:00')
        ->and($dutch->parse('10 augustus - 12 september', '2012-08-10')[0]->end?->date()->toDateTimeString())->toBe('2012-09-12 12:00:00')
        ->and($dutch->date('12de juli om 19:00', '2012-08-10')?->toDateTimeString())->toBe('2012-07-12 19:00:00')
        ->and($dutch->date('5 mei 12:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-05 12:00:00')
        ->and($dutch->date('7 mei 11:00', '2012-08-10')?->toDateTimeString())->toBe('2012-05-07 11:00:00')
        ->and($dutch->parse('vierentwintigste mei', '2012-08-10')[0]->start->get('day'))->toBe(24)
        ->and($dutch->parse('achtste tot elfde mei 2010', '2012-08-10')[0]->end?->get('day'))->toBe(11)
        ->and($dutch->date('24ste oktober, 9:00', '2017-07-07 15:00')?->toDateTimeString())->toBe('2017-10-24 09:00:00')
        ->and($dutch->date('24ste oktober, 21:00', '2017-07-07 15:00')?->toDateTimeString())->toBe('2017-10-24 21:00:00')
        ->and($dutch->date('24 oktober, 21:00', '2017-07-07 15:00')?->toDateTimeString())->toBe('2017-10-24 21:00:00')
        ->and($dutch->parse('03 aug 96', '2012-08-10')[0]->start->date()->toDateTimeString())->toBe('1996-08-03 12:00:00')
        ->and($dutch->parse('3 aug 96', '2012-08-10')[0]->start->date()->toDateTimeString())->toBe('1996-08-03 12:00:00');
});
