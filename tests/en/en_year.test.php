<?php

use Chrono\Chrono;
use Chrono\Meridiem;

it('parses month name dates with era labels', function () {
    $bce = Chrono::parse('10 August 234 BCE', '2012-08-10')[0];
    $ce = Chrono::parse('10 August 88 CE', '2012-08-10')[0];
    $bc = Chrono::parse('10 August 234 BC', '2012-08-10')[0];
    $ad = Chrono::parse('10 August 88 AD', '2012-08-10')[0];
    $be = Chrono::parse('10 August 2555 BE', '2012-08-10')[0];
    $middleEndianBe = Chrono::parse('The Deadline is August 10 2555 BE', '2012-08-10')[0];
    $middleEndianBc = Chrono::parse('The Deadline is August 10, 345 BC', '2012-08-10')[0];
    $middleEndianAd = Chrono::parse('The Deadline is August 10, 8 AD', '2012-08-10')[0];

    expect($bce->text)->toBe('10 August 234 BCE')
        ->and($bce->start->get('year'))->toBe(-234)
        ->and($bce->start->get('month'))->toBe(8)
        ->and($bce->start->get('day'))->toBe(10)
        ->and($bce->start->date()->format('Y-m-d H:i:s'))->toBe('-0234-08-10 12:00:00')
        ->and($ce->text)->toBe('10 August 88 CE')
        ->and($ce->start->get('year'))->toBe(88)
        ->and($ce->start->get('month'))->toBe(8)
        ->and($ce->start->get('day'))->toBe(10)
        ->and($ce->start->date()->format('Y-m-d H:i:s'))->toBe('0088-08-10 12:00:00')
        ->and($bc->text)->toBe('10 August 234 BC')
        ->and($bc->start->get('year'))->toBe(-234)
        ->and($bc->start->get('month'))->toBe(8)
        ->and($bc->start->get('day'))->toBe(10)
        ->and($bc->start->date()->format('Y-m-d H:i:s'))->toBe('-0234-08-10 12:00:00')
        ->and($ad->text)->toBe('10 August 88 AD')
        ->and($ad->start->get('year'))->toBe(88)
        ->and($ad->start->get('month'))->toBe(8)
        ->and($ad->start->get('day'))->toBe(10)
        ->and($ad->start->date()->format('Y-m-d H:i:s'))->toBe('0088-08-10 12:00:00')
        ->and($be->text)->toBe('10 August 2555 BE')
        ->and($be->start->get('year'))->toBe(2012)
        ->and($be->start->get('month'))->toBe(8)
        ->and($be->start->get('day'))->toBe(10)
        ->and($be->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($middleEndianBe->text)->toBe('August 10 2555 BE')
        ->and($middleEndianBe->start->get('year'))->toBe(2012)
        ->and($middleEndianBc->text)->toBe('August 10, 345 BC')
        ->and($middleEndianBc->start->get('year'))->toBe(-345)
        ->and($middleEndianAd->text)->toBe('August 10, 8 AD')
        ->and($middleEndianAd->start->get('year'))->toBe(8);
});

it('parses trailing years after date time expressions', function () {
    $dateTime = Chrono::parse('Thu Oct 26 11:00:09 2023', '2016-10-01 08:00')[0];
    $timezoneDateTime = Chrono::parse('Thu Oct 26 11:00:09 EDT 2023', '2016-10-01 08:00')[0];

    expect($dateTime->start->get('year'))->toBe(2023)
        ->and($dateTime->start->get('month'))->toBe(10)
        ->and($dateTime->start->get('day'))->toBe(26)
        ->and($dateTime->start->get('hour'))->toBe(11)
        ->and($dateTime->start->get('minute'))->toBe(0)
        ->and($dateTime->start->get('second'))->toBe(9)
        ->and($dateTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($timezoneDateTime->start->get('year'))->toBe(2023)
        ->and($timezoneDateTime->start->get('month'))->toBe(10)
        ->and($timezoneDateTime->start->get('day'))->toBe(26)
        ->and($timezoneDateTime->start->get('hour'))->toBe(11)
        ->and($timezoneDateTime->start->get('minute'))->toBe(0)
        ->and($timezoneDateTime->start->get('second'))->toBe(9)
        ->and($timezoneDateTime->start->get('meridiem'))->toBe(Meridiem::AM)
        ->and($timezoneDateTime->start->get('timezoneOffset'))->toBe(-240);
});

it('parses trailing years after date time range expressions', function () {
    $dateRange = Chrono::parse('Thu Oct 26 - 28, 11:00:09 2023', '2016-10-01 08:00')[0];
    $timeRange = Chrono::parse('Thu Oct 26, 10:00 - 11:00:09 2023', '2016-10-01 08:00')[0];

    expect($dateRange->start->get('year'))->toBe(2023)
        ->and($dateRange->start->get('month'))->toBe(10)
        ->and($dateRange->start->get('day'))->toBe(26)
        ->and($dateRange->start->get('hour'))->toBe(11)
        ->and($dateRange->start->get('second'))->toBe(9)
        ->and($dateRange->end?->get('year'))->toBe(2023)
        ->and($dateRange->end?->get('month'))->toBe(10)
        ->and($dateRange->end?->get('day'))->toBe(28)
        ->and($dateRange->end?->get('hour'))->toBe(11)
        ->and($dateRange->end?->get('second'))->toBe(9)
        ->and($timeRange->start->get('year'))->toBe(2023)
        ->and($timeRange->start->get('month'))->toBe(10)
        ->and($timeRange->start->get('day'))->toBe(26)
        ->and($timeRange->start->get('hour'))->toBe(10)
        ->and($timeRange->start->get('second'))->toBe(0)
        ->and($timeRange->end?->get('year'))->toBe(2023)
        ->and($timeRange->end?->get('month'))->toBe(10)
        ->and($timeRange->end?->get('day'))->toBe(26)
        ->and($timeRange->end?->get('hour'))->toBe(11)
        ->and($timeRange->end?->get('second'))->toBe(9);
});
