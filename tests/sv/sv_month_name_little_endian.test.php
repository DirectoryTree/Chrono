<?php

use Chrono\Chrono;

it('parses swedish little-endian month names with upstream-shaped components', function () {
    $swedish = Chrono::sv();
    $contextual = $swedish->parseText('den 15 augusti', '2012-08-10')[0];
    $explicit = $swedish->parseText('15 augusti 2012', '2012-08-10')[0];
    $abbreviated = $swedish->parseText('15 aug 2012', '2012-08-10')[0];
    $hyphenRange = $swedish->parseText('15-16 augusti', '2012-08-10')[0];
    $tillRange = $swedish->parseText('15 till 16 augusti', '2012-08-10')[0];

    expect($contextual->index)->toBe(0)
        ->and($contextual->text)->toBe('den 15 augusti')
        ->and($contextual->start->get('year'))->toBe(2012)
        ->and($contextual->start->get('month'))->toBe(8)
        ->and($contextual->start->get('day'))->toBe(15)
        ->and($contextual->start->isCertain('year'))->toBeFalse()
        ->and($contextual->start->isCertain('month'))->toBeTrue()
        ->and($contextual->start->isCertain('day'))->toBeTrue()
        ->and($contextual->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($explicit->index)->toBe(0)
        ->and($explicit->text)->toBe('15 augusti 2012')
        ->and($explicit->start->get('year'))->toBe(2012)
        ->and($explicit->start->get('month'))->toBe(8)
        ->and($explicit->start->get('day'))->toBe(15)
        ->and($explicit->start->isCertain('year'))->toBeTrue()
        ->and($abbreviated->index)->toBe(0)
        ->and($abbreviated->text)->toBe('15 aug 2012')
        ->and($abbreviated->start->get('year'))->toBe(2012)
        ->and($abbreviated->start->get('month'))->toBe(8)
        ->and($abbreviated->start->get('day'))->toBe(15)
        ->and($hyphenRange->index)->toBe(0)
        ->and($hyphenRange->text)->toBe('15-16 augusti')
        ->and($hyphenRange->start->get('year'))->toBe(2012)
        ->and($hyphenRange->start->get('month'))->toBe(8)
        ->and($hyphenRange->start->get('day'))->toBe(15)
        ->and($hyphenRange->end?->get('year'))->toBe(2012)
        ->and($hyphenRange->end?->get('month'))->toBe(8)
        ->and($hyphenRange->end?->get('day'))->toBe(16)
        ->and($hyphenRange->end?->isCertain('year'))->toBeFalse()
        ->and($hyphenRange->end?->isCertain('month'))->toBeTrue()
        ->and($hyphenRange->end?->isCertain('day'))->toBeTrue()
        ->and($tillRange->text)->toBe('15 till 16 augusti')
        ->and($tillRange->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($tillRange->end?->date()->toDateTimeString())->toBe('2012-08-16 12:00:00')
        ->and($swedish->parseText('32 augusti', '2012-08-10'))->toBe([]);
});
