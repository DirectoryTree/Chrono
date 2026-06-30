<?php

use DirectoryTree\Chrono\Chrono;
use DirectoryTree\Chrono\Weekday;

it('parses japanese weekdays and parenthesized weekdays', function () {
    $japanese = Chrono::ja();
    $weekday = $japanese->parseText('水曜日', '2012-08-10')[0];
    $closestThursday = $japanese->parseText('木曜日', '2016-09-02')[0];
    $previousWednesday = $japanese->parseText('前の水曜日', '2016-09-02')[0];
    $asciiParenthesized = $japanese->parseText('(木)', '2016-09-02')[0];
    $parenthesized = $japanese->parseText('（土）', '2012-08-10')[0];
    $fullWidthParenthesized = $japanese->parseText('（木）', '2016-09-02')[0];
    $forwardRange = $japanese->parseText('土曜日～月曜日', '2016-09-02', ['forwardDate' => true])[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 00:00:00')
        ->and($weekday->start->tags())->toContain('parser/JPWeekdayParser')
        ->and($japanese->parseDateText('次の月曜日', '2012-08-10')?->toDateTimeString())->toBe('2012-08-13 00:00:00')
        ->and($closestThursday->index)->toBe(0)
        ->and($closestThursday->text)->toBe('木曜日')
        ->and($closestThursday->start->get('year'))->toBe(2016)
        ->and($closestThursday->start->get('month'))->toBe(9)
        ->and($closestThursday->start->get('day'))->toBe(1)
        ->and($closestThursday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($closestThursday->start->isCertain('year'))->toBeFalse()
        ->and($closestThursday->start->isCertain('month'))->toBeFalse()
        ->and($closestThursday->start->isCertain('day'))->toBeFalse()
        ->and($closestThursday->start->isCertain('weekday'))->toBeTrue()
        ->and($closestThursday->start->date()->toDateTimeString())->toBe('2016-09-01 00:00:00')
        ->and($previousWednesday->index)->toBe(0)
        ->and($previousWednesday->text)->toBe('前の水曜日')
        ->and($previousWednesday->start->get('year'))->toBe(2016)
        ->and($previousWednesday->start->get('month'))->toBe(8)
        ->and($previousWednesday->start->get('day'))->toBe(31)
        ->and($previousWednesday->start->get('weekday'))->toBe(Weekday::WEDNESDAY->value)
        ->and($previousWednesday->start->date()->toDateTimeString())->toBe('2016-08-31 00:00:00')
        ->and($asciiParenthesized->index)->toBe(0)
        ->and($asciiParenthesized->text)->toBe('(木)')
        ->and($asciiParenthesized->start->get('year'))->toBe(2016)
        ->and($asciiParenthesized->start->get('month'))->toBe(9)
        ->and($asciiParenthesized->start->get('day'))->toBe(1)
        ->and($asciiParenthesized->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($asciiParenthesized->start->isCertain('year'))->toBeFalse()
        ->and($asciiParenthesized->start->isCertain('month'))->toBeFalse()
        ->and($asciiParenthesized->start->isCertain('day'))->toBeFalse()
        ->and($asciiParenthesized->start->isCertain('weekday'))->toBeTrue()
        ->and($asciiParenthesized->start->date()->toDateTimeString())->toBe('2016-09-01 00:00:00')
        ->and($parenthesized->start->date()->toDateTimeString())->toBe('2012-08-11 00:00:00')
        ->and($parenthesized->start->tags())->toContain('parser/JPWeekdayWithParenthesesParser')
        ->and($fullWidthParenthesized->index)->toBe(0)
        ->and($fullWidthParenthesized->text)->toBe('（木）')
        ->and($fullWidthParenthesized->start->get('year'))->toBe(2016)
        ->and($fullWidthParenthesized->start->get('month'))->toBe(9)
        ->and($fullWidthParenthesized->start->get('day'))->toBe(1)
        ->and($fullWidthParenthesized->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($fullWidthParenthesized->start->isCertain('year'))->toBeFalse()
        ->and($fullWidthParenthesized->start->isCertain('month'))->toBeFalse()
        ->and($fullWidthParenthesized->start->isCertain('day'))->toBeFalse()
        ->and($fullWidthParenthesized->start->isCertain('weekday'))->toBeTrue()
        ->and($fullWidthParenthesized->start->date()->toDateTimeString())->toBe('2016-09-01 00:00:00')
        ->and($forwardRange->text)->toBe('土曜日～月曜日')
        ->and($forwardRange->index)->toBe(0)
        ->and($forwardRange->start->get('year'))->toBe(2016)
        ->and($forwardRange->start->get('month'))->toBe(9)
        ->and($forwardRange->start->get('day'))->toBe(3)
        ->and($forwardRange->start->get('weekday'))->toBe(Weekday::SATURDAY->value)
        ->and($forwardRange->start->isCertain('year'))->toBeFalse()
        ->and($forwardRange->start->isCertain('month'))->toBeFalse()
        ->and($forwardRange->start->isCertain('day'))->toBeFalse()
        ->and($forwardRange->start->isCertain('weekday'))->toBeTrue()
        ->and($forwardRange->start->date()->toDateTimeString())->toBe('2016-09-03 00:00:00')
        ->and($forwardRange->end?->get('year'))->toBe(2016)
        ->and($forwardRange->end?->get('month'))->toBe(9)
        ->and($forwardRange->end?->get('day'))->toBe(5)
        ->and($forwardRange->end?->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($forwardRange->end?->isCertain('year'))->toBeFalse()
        ->and($forwardRange->end?->isCertain('month'))->toBeFalse()
        ->and($forwardRange->end?->isCertain('day'))->toBeFalse()
        ->and($forwardRange->end?->isCertain('weekday'))->toBeTrue()
        ->and($forwardRange->end?->date()->toDateTimeString())->toBe('2016-09-05 00:00:00');
});

it('merges japanese dates with weekdays, times, and date ranges', function () {
    $japanese = Chrono::ja();
    $weekday = $japanese->parseText('2014年7月12日（土）', '2012-08-10')[0];
    $monthDayWeekday = $japanese->parseText('8月27日水曜日', '2012-08-10')[0];
    $monthDayParenthesizedWeekday = $japanese->parseText('8月27日（水）', '2012-08-10')[0];
    $slashParenthesizedWeekday = $japanese->parseText('2012/8/27（水）', '2012-08-10')[0];
    $fullWidthSlashParenthesizedWeekday = $japanese->parseText('１／３０（木）', '2025-02-10')[0];
    $slashNoWeekday = $japanese->parseText('1/30の木曜日', '2025-02-10')[0];
    $asciiSlashParenthesizedWeekday = $japanese->parseText('1/30(木)', '2025-02-10')[0];
    $dateTime = $japanese->parseText('2014年7月12日の午後3時', '2012-08-10')[0];
    $dateRange = $japanese->parseText('2月11日から2月13日', '2012-08-10')[0];
    $fullWidthDateWeekdayTime = $japanese->parseText('１月３０日（木）１４：００', '2025-02-10')[0];
    $fullWidthDateWeekdayTimeRange = $japanese->parseText('１月３１日（金）１２：００－１６：００', '2025-02-10')[0];
    $fullWidthDateTimeRange = $japanese->parseText('１月３０日（木）１２：００－１月３１日（金）１６：００', '2025-02-10')[0];

    expect($weekday->text)->toBe('2014年7月12日（土）')
        ->and($weekday->start->isCertain('weekday'))->toBeTrue()
        ->and($weekday->tags())->toContain('refiner/mergeWeekdayComponent')
        ->and($monthDayWeekday->index)->toBe(0)
        ->and($monthDayWeekday->text)->toBe('8月27日水曜日')
        ->and($monthDayWeekday->start->get('year'))->toBe(2012)
        ->and($monthDayWeekday->start->get('month'))->toBe(8)
        ->and($monthDayWeekday->start->get('day'))->toBe(27)
        ->and($monthDayWeekday->start->get('weekday'))->toBe(Weekday::WEDNESDAY->value)
        ->and($monthDayWeekday->start->date()->toDateTimeString())->toBe('2012-08-27 12:00:00')
        ->and($monthDayParenthesizedWeekday->index)->toBe(0)
        ->and($monthDayParenthesizedWeekday->text)->toBe('8月27日（水）')
        ->and($monthDayParenthesizedWeekday->start->get('year'))->toBe(2012)
        ->and($monthDayParenthesizedWeekday->start->get('month'))->toBe(8)
        ->and($monthDayParenthesizedWeekday->start->get('day'))->toBe(27)
        ->and($monthDayParenthesizedWeekday->start->get('weekday'))->toBe(Weekday::WEDNESDAY->value)
        ->and($monthDayParenthesizedWeekday->start->date()->toDateTimeString())->toBe('2012-08-27 12:00:00')
        ->and($slashParenthesizedWeekday->index)->toBe(0)
        ->and($slashParenthesizedWeekday->text)->toBe('2012/8/27（水）')
        ->and($slashParenthesizedWeekday->start->get('year'))->toBe(2012)
        ->and($slashParenthesizedWeekday->start->get('month'))->toBe(8)
        ->and($slashParenthesizedWeekday->start->get('day'))->toBe(27)
        ->and($slashParenthesizedWeekday->start->get('weekday'))->toBe(Weekday::WEDNESDAY->value)
        ->and($slashParenthesizedWeekday->start->date()->toDateTimeString())->toBe('2012-08-27 12:00:00')
        ->and($fullWidthSlashParenthesizedWeekday->text)->toBe('１／３０（木）')
        ->and($fullWidthSlashParenthesizedWeekday->start->date()->toDateTimeString())->toBe('2025-01-30 12:00:00')
        ->and($fullWidthSlashParenthesizedWeekday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($slashNoWeekday->text)->toBe('1/30の木曜日')
        ->and($slashNoWeekday->start->date()->toDateTimeString())->toBe('2025-01-30 12:00:00')
        ->and($slashNoWeekday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($asciiSlashParenthesizedWeekday->text)->toBe('1/30(木)')
        ->and($asciiSlashParenthesizedWeekday->start->date()->toDateTimeString())->toBe('2025-01-30 12:00:00')
        ->and($asciiSlashParenthesizedWeekday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($dateTime->text)->toBe('2014年7月12日の午後3時')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('2014-07-12 15:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($dateRange->text)->toBe('2月11日から2月13日')
        ->and($dateRange->end?->date()->format('m-d H:i:s'))->toBe('02-13 12:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange')
        ->and($fullWidthDateWeekdayTime->text)->toBe('１月３０日（木）１４：００')
        ->and($fullWidthDateWeekdayTime->start->date()->toDateTimeString())->toBe('2025-01-30 14:00:00')
        ->and($fullWidthDateWeekdayTime->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($fullWidthDateWeekdayTimeRange->text)->toBe('１月３１日（金）１２：００－１６：００')
        ->and($fullWidthDateWeekdayTimeRange->start->date()->toDateTimeString())->toBe('2025-01-31 12:00:00')
        ->and($fullWidthDateWeekdayTimeRange->start->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($fullWidthDateWeekdayTimeRange->end?->date()->toDateTimeString())->toBe('2025-01-31 16:00:00')
        ->and($fullWidthDateWeekdayTimeRange->end?->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($fullWidthDateTimeRange->text)->toBe('１月３０日（木）１２：００－１月３１日（金）１６：００')
        ->and($fullWidthDateTimeRange->start->date()->toDateTimeString())->toBe('2025-01-30 12:00:00')
        ->and($fullWidthDateTimeRange->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($fullWidthDateTimeRange->end?->date()->toDateTimeString())->toBe('2025-01-31 16:00:00')
        ->and($fullWidthDateTimeRange->end?->get('weekday'))->toBe(Weekday::FRIDAY->value);
});
