<?php

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Calculation\Years;

it('calculates years like upstream helpers', function () {
    expect(Years::findMostLikelyADYear(1997))->toBe(1997)
        ->and(Years::findMostLikelyADYear(97))->toBe(1997)
        ->and(Years::findMostLikelyADYear(12))->toBe(2012)
        ->and(Years::findMostLikelyADYear(50))->toBe(2050)
        ->and(Years::findMostLikelyADYear(51))->toBe(1951)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-08-10'), 3, 1))->toBe(2013)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-08-10'), 10, 8))->toBe(2012)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-01-01'), 31, 12))->toBe(2011)
        ->and(Years::findYearClosestToReference(CarbonImmutable::parse('2012-12-31'), 1, 1))->toBe(2013);
});
