<?php

use DirectoryTree\Chrono\Chrono;

it('parses japanese standard dates', function () {
    $japanese = Chrono::ja();
    $standard = $japanese->parseText('2014年7月12日', '2012-08-10')[0];
    $prefixedStandard = $japanese->parseText('主な株主（2012年3月31日現在）', '2012-08-10')[0];
    $fullWidthMonthStandard = $japanese->parseText('主な株主（2012年９月3日現在）', '2012-08-10')[0];
    $leapDay = $japanese->parseText('主な株主（2020年2月29日現在）', '2019-08-10')[0];
    $missingYear = $japanese->parseText('主な株主（９月3日現在）', '2012-08-10')[0];
    $heiseiEra = $japanese->parseText('主な株主（平成26年12月29日）', '2012-08-10')[0];
    $showaEra = $japanese->parseText('主な株主（昭和６４年１月７日）', '2012-08-10')[0];
    $eraFirstYear = $japanese->parseText('主な株主（令和元年5月1日）', '2012-08-10')[0];
    $eraSecondYear = $japanese->parseText('主な株主（令和2年5月1日）', '2012-08-10')[0];
    $sameYear = $japanese->parseText('主な株主（同年7月27日）', '2012-08-10')[0];
    $currentYear = $japanese->parseText('主な株主（本年7月27日）', '2012-08-10')[0];
    $currentYearAlternative = $japanese->parseText('主な株主（今年7月27日）', '2012-08-10')[0];
    $currentYearLateMonth = $japanese->parseText('主な株主（今年11月27日）', '2012-01-10')[0];
    $yearlessPast = $japanese->parseText('7月27日', '2012-08-10')[0];
    $yearlessClosest = $japanese->parseText('11月27日', '2012-01-10')[0];
    $standardRange = $japanese->parseText('2013年12月26日-2014年1月7日', '2012-08-10')[0];
    $fullWidthRange = $japanese->parseText('２０１３年１２月２６日ー2014年1月7日', '2012-08-10')[0];
    $spacedRange = $japanese->parseText('2013年12月26日 ～ ２０１４年１月７日', '2012-08-10')[0];

    expect($standard->start->date()->toDateTimeString())->toBe('2014-07-12 12:00:00')
        ->and($standard->start->tags())->toContain('parser/JPStandardParser')
        ->and($prefixedStandard->index)->toBe(5)
        ->and($prefixedStandard->text)->toBe('2012年3月31日')
        ->and($prefixedStandard->start->get('year'))->toBe(2012)
        ->and($prefixedStandard->start->get('month'))->toBe(3)
        ->and($prefixedStandard->start->get('day'))->toBe(31)
        ->and($prefixedStandard->start->date()->toDateTimeString())->toBe('2012-03-31 12:00:00')
        ->and($fullWidthMonthStandard->text)->toBe('2012年９月3日')
        ->and($fullWidthMonthStandard->start->date()->toDateTimeString())->toBe('2012-09-03 12:00:00')
        ->and($leapDay->text)->toBe('2020年2月29日')
        ->and($leapDay->start->date()->toDateTimeString())->toBe('2020-02-29 12:00:00')
        ->and($missingYear->text)->toBe('９月3日')
        ->and($missingYear->start->date()->toDateTimeString())->toBe('2012-09-03 12:00:00')
        ->and($heiseiEra->text)->toBe('平成26年12月29日')
        ->and($heiseiEra->start->date()->toDateTimeString())->toBe('2014-12-29 12:00:00')
        ->and($showaEra->text)->toBe('昭和６４年１月７日')
        ->and($showaEra->start->date()->toDateTimeString())->toBe('1989-01-07 12:00:00')
        ->and($eraFirstYear->text)->toBe('令和元年5月1日')
        ->and($eraFirstYear->start->date()->toDateTimeString())->toBe('2019-05-01 12:00:00')
        ->and($eraSecondYear->text)->toBe('令和2年5月1日')
        ->and($eraSecondYear->start->date()->toDateTimeString())->toBe('2020-05-01 12:00:00')
        ->and($eraSecondYear->start->isCertain('year'))->toBeTrue()
        ->and($sameYear->text)->toBe('同年7月27日')
        ->and($sameYear->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($currentYear->text)->toBe('本年7月27日')
        ->and($currentYear->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($currentYearAlternative->text)->toBe('今年7月27日')
        ->and($currentYearAlternative->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($currentYearLateMonth->text)->toBe('今年11月27日')
        ->and($currentYearLateMonth->start->date()->toDateTimeString())->toBe('2012-11-27 12:00:00')
        ->and($yearlessPast->index)->toBe(0)
        ->and($yearlessPast->text)->toBe('7月27日')
        ->and($yearlessPast->start->get('year'))->toBe(2012)
        ->and($yearlessPast->start->get('month'))->toBe(7)
        ->and($yearlessPast->start->get('day'))->toBe(27)
        ->and($yearlessPast->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($yearlessPast->start->isCertain('year'))->toBeFalse()
        ->and($yearlessClosest->text)->toBe('11月27日')
        ->and($yearlessClosest->start->date()->toDateTimeString())->toBe('2011-11-27 12:00:00')
        ->and($standardRange->text)->toBe('2013年12月26日-2014年1月7日')
        ->and($standardRange->start->date()->toDateTimeString())->toBe('2013-12-26 12:00:00')
        ->and($standardRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00')
        ->and($fullWidthRange->text)->toBe('２０１３年１２月２６日ー2014年1月7日')
        ->and($fullWidthRange->start->date()->toDateTimeString())->toBe('2013-12-26 12:00:00')
        ->and($fullWidthRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00')
        ->and($spacedRange->text)->toBe('2013年12月26日 ～ ２０１４年１月７日')
        ->and($spacedRange->start->date()->toDateTimeString())->toBe('2013-12-26 12:00:00')
        ->and($spacedRange->end?->date()->toDateTimeString())->toBe('2014-01-07 12:00:00');
});
