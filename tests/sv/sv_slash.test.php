<?php

use DirectoryTree\Chrono\Chrono;

it('parses swedish month name and slash dates', function () {
    $swedish = Chrono::sv();
    $dayMonth = $swedish->parse('den 15 augusti', '2012-08-10')[0];
    $dayMonthYear = $swedish->parse('15 augusti 2012', '2012-08-10')[0];
    $abbreviated = $swedish->parse('15 aug 2012', '2012-08-10')[0];
    $explicit = $swedish->parse('den 10 augusti 2012', '2012-08-10')[0];
    $hyphenRange = $swedish->parse('15-16 augusti', '2012-08-10')[0];
    $range = $swedish->parse('10-12 augusti', '2012-08-10')[0];
    $tillRange = $swedish->parse('15 till 16 augusti', '2012-08-10')[0];
    $slash = $swedish->parse('10/08/2012', '2012-08-10')[0];

    expect($dayMonth->start->get('year'))->toBe(2012)
        ->and($dayMonth->start->get('month'))->toBe(8)
        ->and($dayMonth->start->get('day'))->toBe(15)
        ->and($dayMonthYear->start->get('year'))->toBe(2012)
        ->and($dayMonthYear->start->get('month'))->toBe(8)
        ->and($dayMonthYear->start->get('day'))->toBe(15)
        ->and($abbreviated->start->get('year'))->toBe(2012)
        ->and($abbreviated->start->get('month'))->toBe(8)
        ->and($abbreviated->start->get('day'))->toBe(15)
        ->and($explicit->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($explicit->start->tags())->toContain('parser/SVMonthNameLittleEndianParser')
        ->and($hyphenRange->start->get('year'))->toBe(2012)
        ->and($hyphenRange->start->get('month'))->toBe(8)
        ->and($hyphenRange->start->get('day'))->toBe(15)
        ->and($hyphenRange->end?->get('year'))->toBe(2012)
        ->and($hyphenRange->end?->get('month'))->toBe(8)
        ->and($hyphenRange->end?->get('day'))->toBe(16)
        ->and($range->end?->date()->toDateTimeString())->toBe('2012-08-12 12:00:00')
        ->and($range->end?->tags())->toContain('parser/SVMonthNameLittleEndianParser')
        ->and($tillRange->end?->date()->toDateTimeString())->toBe('2012-08-16 12:00:00')
        ->and($slash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($swedish->parse('32 augusti', '2012-08-10'))->toBe([]);
});
