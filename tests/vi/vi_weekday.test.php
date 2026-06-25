<?php

use Chrono\Chrono;
use Chrono\Meridiem;
use Chrono\Weekday;

it('parses vietnamese weekday time and relative expressions', function () {
    $vietnamese = Chrono::vi();
    $weekday = $vietnamese->parseText('thứ tư', '2012-08-10 09:30')[0];
    $prefixedWeekday = $vietnamese->parseText('Hẹn vào thứ hai', '2012-08-09')[0];
    $abbreviatedWeekday = $vietnamese->parseText('Hẹn t2', '2012-08-09')[0];
    $tuesday = $vietnamese->parseText('thứ ba', '2012-08-09')[0];
    $thursday = $vietnamese->parseText('thứ năm', '2012-08-09')[0];
    $friday = $vietnamese->parseText('thứ sáu', '2012-08-09')[0];
    $saturday = $vietnamese->parseText('thứ bảy', '2012-08-09')[0];
    $sunday = $vietnamese->parseText('chủ nhật', '2012-08-09')[0];
    $nextWeekday = $vietnamese->parseText('thứ hai tới', '2012-08-09')[0];
    $followingWeekday = $vietnamese->parseText('thứ hai sau', '2012-08-09')[0];
    $previousWeekday = $vietnamese->parseText('thứ hai qua', '2012-08-09')[0];
    $weekdayBeforeConjunction = $vietnamese->parseText('thứ hai sau khi chiến tranh kết thúc', '2012-08-10 12:00')[0];
    $sameWeekday = $vietnamese->parseText('thứ năm', '2012-08-09 12:00', ['forwardDate' => true])[0];
    $nextMonday = $vietnamese->parseText('thứ hai', '2012-08-14 12:00', ['forwardDate' => true])[0];
    $forwardMorning = $vietnamese->parseText('7 giờ sáng', '2012-08-10 08:00', ['forwardDate' => true])[0];
    $forwardSlash = $vietnamese->parseText('15/3', '2012-08-10 12:00', ['forwardDate' => true])[0];
    $forwardMonth = $vietnamese->parseText('tháng 3', '2012-08-10 12:00', ['forwardDate' => true])[0];
    $prefixedTime = $vietnamese->parseText('Cuộc họp lúc 7 giờ.', '2012-08-10 12:00')[0];
    $time = $vietnamese->parseText('lúc 7 giờ 30 phút chiều', '2012-08-10')[0];
    $plainTime = $vietnamese->parseText('lúc 7 giờ 30 phút', '2012-08-10 12:00')[0];
    $twentyFourHourTime = $vietnamese->parseText('vào 15 giờ 45 phút', '2012-08-10 12:00')[0];
    $colonTime = $vietnamese->parseText('Hẹn lúc 15:30.', '2012-08-10 12:00')[0];
    $morningTime = $vietnamese->parseText('9 giờ sáng', '2012-08-10 12:00')[0];
    $afternoonTime = $vietnamese->parseText('3 giờ chiều', '2012-08-10 12:00')[0];
    $nightTime = $vietnamese->parseText('10 giờ đêm', '2012-08-10 12:00')[0];
    $middayTime = $vietnamese->parseText('1 giờ trưa', '2012-08-10 12:00')[0];
    $lateMorningTime = $vietnamese->parseText('11 giờ trưa', '2012-08-10 12:00')[0];
    $noonTime = $vietnamese->parseText('12 giờ trưa', '2012-08-10 12:00')[0];
    $midnightTime = $vietnamese->parseText('12 giờ sáng', '2012-08-10 12:00')[0];
    $ago = $vietnamese->parseText('2 ngày trước', '2012-08-10 09:30')[0];
    $prefixedAgoDays = $vietnamese->parseText('Sự kiện 3 ngày trước.', '2012-08-10 12:00')[0];
    $agoWeeks = $vietnamese->parseText('2 tuần trước', '2012-08-10 12:00')[0];
    $agoMonths = $vietnamese->parseText('3 tháng trước', '2012-08-10 12:00')[0];
    $agoYears = $vietnamese->parseText('5 năm trước', '2012-08-10 12:00')[0];
    $agoPastMonth = $vietnamese->parseText('1 tháng qua', '2012-08-10 12:00')[0];
    $agoSpelledWeeks = $vietnamese->parseText('hai tuần trước', '2012-08-10 12:00')[0];
    $agoSpelledDays = $vietnamese->parseText('ba ngày trước', '2012-08-10 12:00')[0];
    $agoSpelledMonth = $vietnamese->parseText('một tháng qua', '2012-08-10 12:00')[0];
    $later = $vietnamese->parseText('3 tuần sau', '2012-08-10 09:30')[0];
    $prefixedLaterDays = $vietnamese->parseText('Sự kiện 3 ngày sau.', '2012-08-10 12:00')[0];
    $laterWeeks = $vietnamese->parseText('2 tuần nữa', '2012-08-10 12:00')[0];
    $laterMonths = $vietnamese->parseText('3 tháng tới', '2012-08-10 12:00')[0];
    $laterYears = $vietnamese->parseText('10 năm sau', '2012-08-10 12:00')[0];
    $laterSpelledDays = $vietnamese->parseText('ba ngày sau', '2012-08-10 12:00')[0];
    $laterSpelledWeeks = $vietnamese->parseText('hai tuần nữa', '2012-08-10 12:00')[0];
    $within = $vietnamese->parseText('trong 1 tháng', '2012-08-10 09:30')[0];
    $withinDays = $vietnamese->parseText('trong 3 ngày', '2012-08-10 12:00')[0];
    $casual = $vietnamese->parseText('tuần trước', '2012-08-10 09:30')[0];
    $bareNextYear = $vietnamese->parseText('năm sau', '2012-08-10 12:00')[0];
    $withinWeeks = $vietnamese->parseText('Hoàn thành trong 2 tuần.', '2012-08-10 12:00')[0];
    $withinMonths = $vietnamese->parseText('trong vòng 3 tháng', '2012-08-10 12:00')[0];

    expect($weekday->start->date()->toDateTimeString())->toBe('2012-08-08 12:00:00')
        ->and($weekday->start->tags())->toContain('parser/VIWeekdayParser')
        ->and($prefixedWeekday->index)->toBe(8)
        ->and($prefixedWeekday->text)->toBe('thứ hai')
        ->and($prefixedWeekday->start->get('weekday'))->toBe(1)
        ->and($abbreviatedWeekday->index)->toBe(4)
        ->and($abbreviatedWeekday->text)->toBe('t2')
        ->and($abbreviatedWeekday->start->get('weekday'))->toBe(1)
        ->and($tuesday->start->get('weekday'))->toBe(Weekday::TUESDAY->value)
        ->and($weekday->start->get('weekday'))->toBe(Weekday::WEDNESDAY->value)
        ->and($thursday->start->get('weekday'))->toBe(Weekday::THURSDAY->value)
        ->and($friday->start->get('weekday'))->toBe(Weekday::FRIDAY->value)
        ->and($saturday->start->get('weekday'))->toBe(Weekday::SATURDAY->value)
        ->and($sunday->start->get('weekday'))->toBe(Weekday::SUNDAY->value)
        ->and($vietnamese->parseText('t7', '2012-08-09')[0]->start->get('weekday'))->toBe(6)
        ->and($vietnamese->parseText('cn', '2012-08-09')[0]->start->get('weekday'))->toBe(0)
        ->and($nextWeekday->text)->toBe('thứ hai tới')
        ->and($nextWeekday->start->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($nextWeekday->start->get('day'))->toBe(13)
        ->and($nextWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($nextWeekday->start->isCertain('day'))->toBeFalse()
        ->and($followingWeekday->text)->toBe('thứ hai sau')
        ->and($followingWeekday->start->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($followingWeekday->start->get('day'))->toBe(13)
        ->and($followingWeekday->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($previousWeekday->text)->toBe('thứ hai qua')
        ->and($previousWeekday->start->get('weekday'))->toBe(Weekday::MONDAY->value)
        ->and($previousWeekday->start->get('day'))->toBe(6)
        ->and($previousWeekday->start->date()->toDateTimeString())->toBe('2012-08-06 12:00:00')
        ->and($weekdayBeforeConjunction->text)->toBe('thứ hai')
        ->and($weekdayBeforeConjunction->start->get('weekday'))->toBe(1)
        ->and($sameWeekday->start->date()->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($nextMonday->start->date()->toDateTimeString())->toBe('2012-08-20 12:00:00')
        ->and($forwardMorning->start->date()->toDateTimeString())->toBe('2012-08-11 07:00:00')
        ->and($forwardSlash->start->date()->toDateTimeString())->toBe('2013-03-15 12:00:00')
        ->and($forwardMonth->start->date()->toDateTimeString())->toBe('2013-03-01 12:00:00')
        ->and($prefixedTime->index)->toBe(9)
        ->and($prefixedTime->text)->toBe('lúc 7 giờ')
        ->and($prefixedTime->start->get('hour'))->toBe(7)
        ->and($prefixedTime->start->get('minute'))->toBe(0)
        ->and($prefixedTime->start->date()->toDateTimeString())->toBe('2012-08-10 07:00:00')
        ->and($prefixedTime->start->isCertain('hour'))->toBeTrue()
        ->and($prefixedTime->start->isCertain('meridiem'))->toBeFalse()
        ->and($time->start->get('hour'))->toBe(19)
        ->and($time->start->get('minute'))->toBe(30)
        ->and($time->start->date()->toDateTimeString())->toBe('2012-08-10 19:30:00')
        ->and($time->start->tags())->toContain('parser/VITimeExpressionParser')
        ->and($plainTime->start->get('hour'))->toBe(7)
        ->and($plainTime->start->get('minute'))->toBe(30)
        ->and($plainTime->start->date()->toDateTimeString())->toBe('2012-08-10 07:30:00')
        ->and($twentyFourHourTime->start->get('hour'))->toBe(15)
        ->and($twentyFourHourTime->start->get('minute'))->toBe(45)
        ->and($twentyFourHourTime->start->date()->toDateTimeString())->toBe('2012-08-10 15:45:00')
        ->and($colonTime->text)->toBe('lúc 15:30')
        ->and($colonTime->start->get('hour'))->toBe(15)
        ->and($colonTime->start->get('minute'))->toBe(30)
        ->and($colonTime->start->date()->toDateTimeString())->toBe('2012-08-10 15:30:00')
        ->and($morningTime->start->get('hour'))->toBe(9)
        ->and($morningTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($morningTime->start->date()->toDateTimeString())->toBe('2012-08-10 09:00:00')
        ->and($morningTime->start->isCertain('meridiem'))->toBeTrue()
        ->and($afternoonTime->start->get('hour'))->toBe(15)
        ->and($afternoonTime->start->date()->toDateTimeString())->toBe('2012-08-10 15:00:00')
        ->and($nightTime->start->get('hour'))->toBe(22)
        ->and($nightTime->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($middayTime->start->get('hour'))->toBe(13)
        ->and($middayTime->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($middayTime->start->date()->toDateTimeString())->toBe('2012-08-10 13:00:00')
        ->and($lateMorningTime->start->get('hour'))->toBe(11)
        ->and($lateMorningTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($lateMorningTime->start->date()->toDateTimeString())->toBe('2012-08-10 11:00:00')
        ->and($noonTime->start->get('hour'))->toBe(12)
        ->and($noonTime->start->get('meridiem'))->toBe(Meridiem::PM)
        ->and($noonTime->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($midnightTime->start->get('hour'))->toBe(0)
        ->and($midnightTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($midnightTime->start->date()->toDateTimeString())->toBe('2012-08-10 00:00:00')
        ->and($ago->start->date()->toDateTimeString())->toBe('2012-08-08 09:30:00')
        ->and($ago->start->tags())->toContain('parser/VITimeUnitCasualRelativeFormatParser')
        ->and($prefixedAgoDays->index)->toBe(8)
        ->and($prefixedAgoDays->text)->toBe('3 ngày trước')
        ->and($prefixedAgoDays->start->get('year'))->toBe(2012)
        ->and($prefixedAgoDays->start->get('month'))->toBe(8)
        ->and($prefixedAgoDays->start->get('day'))->toBe(7)
        ->and($agoWeeks->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($agoMonths->start->get('month'))->toBe(5)
        ->and($agoMonths->start->get('year'))->toBe(2012)
        ->and($agoYears->start->get('year'))->toBe(2007)
        ->and($agoPastMonth->start->date()->toDateTimeString())->toBe('2012-07-10 12:00:00')
        ->and($agoSpelledWeeks->start->date()->toDateTimeString())->toBe('2012-07-27 12:00:00')
        ->and($agoSpelledDays->start->date()->toDateTimeString())->toBe('2012-08-07 12:00:00')
        ->and($agoSpelledMonth->start->date()->toDateTimeString())->toBe('2012-07-10 12:00:00')
        ->and($later->start->date()->toDateTimeString())->toBe('2012-08-31 09:30:00')
        ->and($later->start->tags())->toContain('parser/VITimeUnitCasualRelativeFormatParser')
        ->and($prefixedLaterDays->index)->toBe(8)
        ->and($prefixedLaterDays->text)->toBe('3 ngày sau')
        ->and($prefixedLaterDays->start->get('month'))->toBe(8)
        ->and($prefixedLaterDays->start->get('day'))->toBe(13)
        ->and($laterWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($laterMonths->start->get('month'))->toBe(11)
        ->and($laterMonths->start->get('year'))->toBe(2012)
        ->and($laterYears->start->get('year'))->toBe(2022)
        ->and($laterSpelledDays->start->date()->toDateTimeString())->toBe('2012-08-13 12:00:00')
        ->and($laterSpelledWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($within->start->tags())->toContain('parser/VITimeUnitWithinFormatParser')
        ->and($withinDays->text)->toBe('trong 3 ngày')
        ->and($withinDays->start->get('month'))->toBe(8)
        ->and($withinDays->start->get('day'))->toBe(13)
        ->and($casual->start->date()->toDateTimeString())->toBe('2012-08-03 09:30:00')
        ->and($casual->start->tags())->toContain('parser/VITimeUnitCasualRelativeFormatParser')
        ->and($bareNextYear->start->date()->toDateTimeString())->toBe('2013-08-10 12:00:00')
        ->and($withinWeeks->start->date()->toDateTimeString())->toBe('2012-08-24 12:00:00')
        ->and($withinWeeks->start->tags())->toContain('parser/VITimeUnitWithinFormatParser')
        ->and($withinMonths->start->date()->toDateTimeString())->toBe('2012-11-10 12:00:00');
});

it('merges vietnamese dates with times ranges and weekdays', function () {
    $vietnamese = Chrono::vi();
    $dateTime = $vietnamese->parseText('ngày 15 tháng 3 năm 1975 lúc 7 giờ', '2012-08-10')[0];
    $upstreamDateTime = $vietnamese->parseText('ngày 30 tháng 4 năm 1975 lúc 11 giờ', '2012-08-10')[0];
    $dateRange = $vietnamese->parseText('ngày 15 tháng 3 đến ngày 17 tháng 3', '2012-08-10')[0];
    $prefixedDateRange = $vietnamese->parseText('từ ngày 5 tháng 8 đến ngày 10 tháng 8 năm 2012', '2012-08-10')[0];
    $endYearRange = $vietnamese->parseText('ngày 1 tháng 4 – ngày 30 tháng 4 năm 2000', '2012-08-10')[0];
    $hyphenRange = $vietnamese->parseText('ngày 3 tháng 9 - ngày 5 tháng 9 năm 1945', '2012-08-10')[0];
    $monthRange = $vietnamese->parseText('tháng 3 tới tháng 5 năm 1975', '2012-08-10')[0];
    $yearRange = $vietnamese->parseText('ngày 1 tháng 1 đến ngày 31 tháng 12 năm 2020', '2012-08-10')[0];
    $weekday = $vietnamese->parseText('thứ tư ngày 15 tháng 3', '2012-08-10')[0];

    expect($dateTime->text)->toBe('ngày 15 tháng 3 năm 1975 lúc 7 giờ')
        ->and($dateTime->start->date()->toDateTimeString())->toBe('1975-03-15 07:00:00')
        ->and($dateTime->tags())->toContain('refiner/mergeDateFollowedByTime')
        ->and($upstreamDateTime->start->date()->toDateTimeString())->toBe('1975-04-30 11:00:00')
        ->and($dateRange->tags())->toContain('refiner/mergeDateRange')
        ->and($dateRange->start->get('day'))->toBe(15)
        ->and($dateRange->start->get('month'))->toBe(3)
        ->and($dateRange->end?->get('day'))->toBe(17)
        ->and($dateRange->end?->get('month'))->toBe(3)
        ->and($prefixedDateRange->text)->toBe('ngày 5 tháng 8 đến ngày 10 tháng 8 năm 2012')
        ->and($prefixedDateRange->start->date()->toDateTimeString())->toBe('2012-08-05 12:00:00')
        ->and($prefixedDateRange->end?->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($endYearRange->start->date()->toDateTimeString())->toBe('2000-04-01 12:00:00')
        ->and($endYearRange->end?->date()->toDateTimeString())->toBe('2000-04-30 12:00:00')
        ->and($hyphenRange->start->date()->toDateTimeString())->toBe('1945-09-03 12:00:00')
        ->and($hyphenRange->end?->date()->toDateTimeString())->toBe('1945-09-05 12:00:00')
        ->and($monthRange->tags())->toContain('refiner/mergeDateRange')
        ->and($monthRange->start->date()->toDateTimeString())->toBe('1975-03-01 12:00:00')
        ->and($monthRange->end?->date()->toDateTimeString())->toBe('1975-05-01 12:00:00')
        ->and($yearRange->start->date()->toDateTimeString())->toBe('2020-01-01 12:00:00')
        ->and($yearRange->end?->date()->toDateTimeString())->toBe('2020-12-31 12:00:00')
        ->and($yearRange->start->date()->lt($yearRange->end?->date()))->toBeTrue()
        ->and($weekday->start->isCertain('weekday'))->toBeTrue();
});
