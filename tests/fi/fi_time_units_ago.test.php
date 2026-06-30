<?php

use DirectoryTree\Chrono\Chrono;

it('parses finnish ago time unit expressions', function () {
    $finnish = Chrono::fi();

    $fiveDaysAgo = $finnish->parseText('5 päivää sitten tehtiin jotain', '2012-08-10')[0];
    $tenDaysAgo = $finnish->parseText('10 päivää sitten tehtiin jotain', '2012-08-10 13:30')[0];
    $minutesAgo = $finnish->parseText('15 minuuttia sitten', '2012-08-10 12:14')[0];
    $prefixedHoursAgo = $finnish->parseText('   12 tuntia sitten', '2012-08-10 12:14')[0];
    $hoursAgo = $finnish->parseText('12 tuntia sitten tapahtui jotain', '2012-08-10 12:14')[0];
    $monthsAgo = $finnish->parseText('5 kuukautta sitten tehtiin jotain', '2012-10-10')[0];
    $yearsAgo = $finnish->parseText('5 vuotta sitten tehtiin jotain', '2012-08-10 22:22')[0];
    $weekAgo = $finnish->parseText('yksi viikkoa sitten tehtiin jotain', '2012-08-03 08:34')[0];

    expect($fiveDaysAgo->index)->toBe(0)
        ->and($fiveDaysAgo->text)->toBe('5 päivää sitten')
        ->and($fiveDaysAgo->start->date()->toDateString())->toBe('2012-08-05')
        ->and($tenDaysAgo->index)->toBe(0)
        ->and($tenDaysAgo->text)->toBe('10 päivää sitten')
        ->and($tenDaysAgo->start->date()->format('Y-m-d H:i'))->toBe('2012-07-31 13:30')
        ->and($minutesAgo->index)->toBe(0)
        ->and($minutesAgo->text)->toBe('15 minuuttia sitten')
        ->and($minutesAgo->start->date()->format('Y-m-d H:i'))->toBe('2012-08-10 11:59')
        ->and($prefixedHoursAgo->index)->toBe(3)
        ->and($prefixedHoursAgo->text)->toBe('12 tuntia sitten')
        ->and($prefixedHoursAgo->start->date()->format('Y-m-d H:i'))->toBe('2012-08-10 00:14')
        ->and($hoursAgo->index)->toBe(0)
        ->and($hoursAgo->text)->toBe('12 tuntia sitten')
        ->and($hoursAgo->start->date()->format('Y-m-d H:i'))->toBe('2012-08-10 00:14')
        ->and($monthsAgo->index)->toBe(0)
        ->and($monthsAgo->text)->toBe('5 kuukautta sitten')
        ->and($monthsAgo->start->date()->toDateString())->toBe('2012-05-10')
        ->and($yearsAgo->index)->toBe(0)
        ->and($yearsAgo->text)->toBe('5 vuotta sitten')
        ->and($yearsAgo->start->date()->format('Y-m-d H:i'))->toBe('2007-08-10 22:22')
        ->and($weekAgo->index)->toBe(0)
        ->and($weekAgo->text)->toBe('yksi viikkoa sitten')
        ->and($weekAgo->start->date()->format('Y-m-d H:i'))->toBe('2012-07-27 08:34');
});
