<?php

use DirectoryTree\Chrono\Chrono;

it('parses finnish common iso and slash date formats', function () {
    $finnish = Chrono::fi();
    $iso = $finnish->parse('Julkaistu 2026-06-23 14:30', '2012-08-10')[0];

    expect($iso->text)->toBe('2026-06-23 14:30')
        ->and($iso->start->date()->toDateTimeString())->toBe('2026-06-23 14:30:00')
        ->and($finnish->parse('Julkaistu 10/08/2012', '2012-08-10')[0]->text)
        ->toBe('10/08/2012')
        ->and($finnish->date('Julkaistu 10/08/2012', '2012-08-10')?->toDateTimeString())
        ->toBe('2012-08-10 12:00:00')
        ->and($finnish->date('Tapahtuma 6/20', '2026-06-23 09:00', ['forwardDate' => true])?->toDateTimeString())
        ->toBe('2027-06-20 12:00:00');
});
