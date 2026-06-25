<?php

use Chrono\Chrono;

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
