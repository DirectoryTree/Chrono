<?php

use Chrono\Chrono;

it('parses italian slash month year expressions', function () {
    $italian = Chrono::it();
    $strictItalian = Chrono::strictItalian();
    $slashDate = $strictItalian->parseText('Pubblicato il 10/08/2012', '2012-08-10')[0];

    expect($italian->parseText('Contratto valido da 06/2005', '2012-08-10')[0]->text)
        ->toBe('06/2005')
        ->and($italian->parseText('Contratto valido da 06/2005', '2012-08-10')[0]->start->tags())->toContain('parser/ITSlashMonthFormatParser')
        ->and($italian->parseDateText('Contratto valido da 06/2005', '2012-08-10')?->toDateTimeString())
        ->toBe('2005-06-01 12:00:00')
        ->and($italian->parseText('Contratto valido da 13/2005', '2012-08-10'))
        ->toBe([])
        ->and($slashDate->text)->toBe('10/08/2012')
        ->and($slashDate->start->date()->toDateTimeString())->toBe('2012-10-08 12:00:00')
        ->and($slashDate->start->tags())->toContain('parser/SlashDateFormatParser');
});
