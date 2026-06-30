<?php

use DirectoryTree\Chrono\Chrono;

it('parses traditional chinese deadline expressions', function () {
    $chinese = Chrono::zhHant();
    $daysWithin = $chinese->parseText('五日內我地有d野做', '2012-08-10')[0];
    $numericDaysWithin = $chinese->parseText('5日之內我地有d野做', '2012-08-10')[0];
    $tenDaysWithin = $chinese->parseText('十日內我地有d野做', '2012-08-10')[0];
    $fiveMinutesLater = $chinese->parseText('五分鐘後', '2012-08-10 12:14')[0];
    $oneHourWithin = $chinese->parseText('一個鐘之內', '2012-08-10 12:14')[0];
    $numericMinutesLater = $chinese->parseText('5分鐘之後我就收皮', '2012-08-10 12:14')[0];
    $secondsLater = $chinese->parseText('係5秒之後你就會收皮', '2012-08-10 12:14')[0];
    $halfHourWithin = $chinese->parseText('半小時之內', '2012-08-10 12:14')[0];
    $weeksWithin = $chinese->parseText('兩個禮拜內答覆我', '2012-08-10 12:14')[0];
    $monthWithin = $chinese->parseText('1個月之內答覆我', '2012-08-10 12:14')[0];
    $fewMonthsWithin = $chinese->parseText('幾個月之內答覆我', '2012-08-10 12:14')[0];
    $yearWithin = $chinese->parseText('一年內答覆我', '2012-08-10 12:14')[0];
    $numericYearWithin = $chinese->parseText('1年之內答覆我', '2012-08-10 12:14')[0];

    expect($daysWithin->text)->toBe('五日內')
        ->and($daysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($daysWithin->start->tags())->toContain('parser/ZHHantDeadlineFormatParser')
        ->and($numericDaysWithin->text)->toBe('5日之內')
        ->and($numericDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-15 12:00:00')
        ->and($tenDaysWithin->text)->toBe('十日內')
        ->and($tenDaysWithin->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($fiveMinutesLater->text)->toBe('五分鐘後')
        ->and($fiveMinutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($oneHourWithin->text)->toBe('一個鐘之內')
        ->and($oneHourWithin->start->date()->toDateTimeString())->toBe('2012-08-10 13:14:00')
        ->and($numericMinutesLater->text)->toBe('5分鐘之後')
        ->and($numericMinutesLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:19:00')
        ->and($secondsLater->text)->toBe('5秒之後')
        ->and($secondsLater->start->date()->toDateTimeString())->toBe('2012-08-10 12:14:05')
        ->and($halfHourWithin->text)->toBe('半小時之內')
        ->and($halfHourWithin->start->date()->toDateTimeString())->toBe('2012-08-10 12:44:00')
        ->and($weeksWithin->text)->toBe('兩個禮拜內')
        ->and($weeksWithin->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($monthWithin->text)->toBe('1個月之內')
        ->and($monthWithin->start->date()->toDateTimeString())->toBe('2012-09-10 12:00:00')
        ->and($fewMonthsWithin->text)->toBe('幾個月之內')
        ->and($fewMonthsWithin->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00')
        ->and($yearWithin->text)->toBe('一年內')
        ->and($yearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($numericYearWithin->text)->toBe('1年之內')
        ->and($numericYearWithin->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00');
});
