<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Enums\Meridiem;

it('parses dutch numeric time expressions and ranges', function () {
    $dutch = Chrono::nl();
    $offset = $dutch->parse('  11:00 ', '2016-10-01 08:00')[0];
    $prefixedOffset = $dutch->parse('2020 om  11:00 ', '2016-10-01 08:00')[0];
    $second = $dutch->parse('20:32:13', '2016-10-01 08:00')[0];
    $secondRange = $dutch->parse('10:00:00 - 21:45:00', '2016-10-01 08:00')[0];
    $milliseconds = $dutch->parse('20:32:13.123', '2016-10-01 08:00')[0];
    $evening = $dutch->parse("23:00 's avonds", '2016-10-01 08:00')[0];
    $tonight = $dutch->parse('23:00 vanavond', '2016-10-01 08:00')[0];
    $morning = $dutch->parse("6:00 's ochtends", '2016-10-01 08:00')[0];
    $afternoon = $dutch->parse('6:00 in de namiddag', '2016-10-01 08:00')[0];
    $range = $dutch->parse('Afspraak om 6:30 - 8:45 uur', '2012-08-10')[0];
    $overnight = $dutch->parse('Dienst om 23:30 - 1:15', '2012-08-10')[0];

    expect($offset->index)->toBe(2)
        ->and($offset->text)->toBe('11:00')
        ->and($offset->start->tags())->toContain('parser/NLTimeExpressionParser')
        ->and($prefixedOffset->index)->toBe(5)
        ->and($prefixedOffset->text)->toBe('om  11:00')
        ->and($second->start->date()->toDateTimeString())->toBe('2016-10-01 20:32:13')
        ->and($secondRange->end?->date()->toDateTimeString())->toBe('2016-10-01 21:45:00')
        ->and($milliseconds->start->date()->format('Y-m-d H:i:s.v'))->toBe('2016-10-01 20:32:13.123')
        ->and($evening->text)->toBe("23:00 's avonds")
        ->and($evening->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($evening->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($tonight->text)->toBe('23:00 vanavond')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2016-10-01 23:00:00')
        ->and($tonight->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($morning->text)->toBe("6:00 's ochtends")
        ->and($morning->start->date()->toDateTimeString())->toBe('2016-10-01 06:00:00')
        ->and($morning->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($afternoon->text)->toBe('6:00 in de namiddag')
        ->and($afternoon->start->date()->toDateTimeString())->toBe('2016-10-01 18:00:00')
        ->and($afternoon->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($dutch->parse('Afspraak om 6 uur', '2012-08-10')[0]->text)
        ->toBe('om 6 uur')
        ->and($dutch->date('Afspraak om 6 uur', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 06:00:00')
        ->and($dutch->date('Afspraak om 6:30 p.m.', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 18:30:00')
        ->and($dutch->date('Afspraak om 1234 am', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 00:34:00')
        ->and($range->text)->toBe('om 6:30 - 8:45 uur')
        ->and($range->start->date()->toDateTimeString())->toBe('2012-08-10 06:30:00')
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-10 08:45:00')
        ->and($overnight->end?->date()->toDateTimeString())->toBe('2012-08-11 01:15:00')
        ->and($dutch->parse('Gepubliceerd 2020', '2012-08-10'))
        ->toBe([]);
});
