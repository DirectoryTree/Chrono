<?php

use Chrono\Chrono;

it('parses year month day expressions', function () {
    $slash = Chrono::parse('2012/8/10', '2012-08-10')[0];
    $prefixedSlash = Chrono::parse('The Deadline is 2012/8/10', '2012-08-10')[0];
    $monthName = Chrono::parse('2012/Aug/10', '2012-08-10')[0];
    $prefixedMonthName = Chrono::parse('The Deadline is 2012/aug/10', '2012-08-10')[0];
    $prefixedSpacedMonthName = Chrono::parse('The Deadline is 2018 March 18', '2012-08-10')[0];
    $strictShortMonth = Chrono::strict()->parseText('2014/2/28', '2012-08-10')[0];
    $strictFullMonth = Chrono::strict()->parseText('2014/12/28', '2012-08-10')[0];
    $strictDot = Chrono::strict()->parseText('2014.12.28', '2012-08-10')[0];
    $strictSpaced = Chrono::strict()->parseText('2014 12 28', '2012-08-10')[0];

    expect($slash->text)->toBe('2012/8/10')
        ->and($slash->index)->toBe(0)
        ->and($slash->start->get('year'))->toBe(2012)
        ->and($slash->start->get('month'))->toBe(8)
        ->and($slash->start->get('day'))->toBe(10)
        ->and($slash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($slash->start->tags())->toContain('parser/ENYearMonthDayParser')
        ->and($prefixedSlash->text)->toBe('2012/8/10')
        ->and($prefixedSlash->index)->toBe(16)
        ->and($prefixedSlash->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($strictShortMonth->text)->toBe('2014/2/28')
        ->and($strictFullMonth->text)->toBe('2014/12/28')
        ->and($strictFullMonth->start->date()->toDateTimeString())->toBe('2014-12-28 12:00:00')
        ->and($strictDot->text)->toBe('2014.12.28')
        ->and($strictDot->start->date()->toDateTimeString())->toBe('2014-12-28 12:00:00')
        ->and($strictSpaced->text)->toBe('2014 12 28')
        ->and($strictSpaced->start->date()->toDateTimeString())->toBe('2014-12-28 12:00:00')
        ->and($monthName->text)->toBe('2012/Aug/10')
        ->and($monthName->start->get('year'))->toBe(2012)
        ->and($monthName->start->get('month'))->toBe(8)
        ->and($monthName->start->get('day'))->toBe(10)
        ->and($monthName->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedMonthName->text)->toBe('2012/aug/10')
        ->and($prefixedMonthName->index)->toBe(16)
        ->and($prefixedMonthName->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($prefixedSpacedMonthName->text)->toBe('2018 March 18')
        ->and($prefixedSpacedMonthName->index)->toBe(16)
        ->and($prefixedSpacedMonthName->start->date()->toDateTimeString())->toBe('2018-03-18 12:00:00')
        ->and(Chrono::parse('2018 Mar. 18', '2012-08-10')[0]->text)
        ->toBe('2018 Mar. 18')
        ->and(Chrono::parseDate('2018/Mar./18', '2012-08-10')?->toDateTimeString())
        ->toBe('2018-03-18 12:00:00');
});

it('swaps casual year month day order only when strict mode rejects it', function () {
    $slash = Chrono::casual()->parseText('2024/13/1', '2012-08-10')[0];
    $dash = Chrono::casual()->parseText('2024-13-01', '2012-08-10')[0];

    expect(Chrono::strict()->parseText('2024/13/1', '2012-08-10'))->toBe([])
        ->and(Chrono::strict()->parseText('2024-13-01', '2012-08-10'))->toBe([])
        ->and($slash->start->get('year'))->toBe(2024)
        ->and($slash->start->get('month'))->toBe(1)
        ->and($slash->start->get('day'))->toBe(13)
        ->and($dash->start->get('year'))->toBe(2024)
        ->and($dash->start->get('month'))->toBe(1)
        ->and($dash->start->get('day'))->toBe(13);
});

it('rejects unlikely and impossible year month day expressions', function () {
    expect(Chrono::parse('2012/80/10', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('2012 80 10', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('2014-08-32', '2012-08-10'))->toBe([])
        ->and(Chrono::parse('2014-02-30', '2012-08-10'))->toBe([]);
});
