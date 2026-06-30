<?php

use DirectoryTree\Chrono\Chrono;

it('does not parse reporting-period prose as a relative duration', function () {
    $result = Chrono::parse('Statement of comprehensive income for the year ended Dec. 2021', '2012-08-10')[0];

    expect($result->text)->toBe('Dec. 2021')
        ->and($result->start->date()->toDateTimeString())->toBe('2021-12-01 12:00:00');
});

it('swaps year month day order when month is impossible and day can be month', function () {
    $result = Chrono::parse('2024/13/1', '2012-08-10')[0];
    $strict = Chrono::strict()->parseText('2024/13/1', '2012-08-10');

    expect($result->start->get('year'))->toBe(2024)
        ->and($result->start->get('month'))->toBe(1)
        ->and($result->start->get('day'))->toBe(13)
        ->and($strict)->toBe([]);
});

it('does not parse impossible year month day expressions', function () {
    expect(Chrono::parse('2014-08-32'))->toBe([])
        ->and(Chrono::parse('2014-02-30'))->toBe([])
        ->and(Chrono::parse('2012/80/10'))->toBe([])
        ->and(Chrono::parse('2012 80 10'))->toBe([])
        ->and(Chrono::parse('2012-14'))->toBe([])
        ->and(Chrono::parse('2012-1400'))->toBe([])
        ->and(Chrono::parse('2200-25'))->toBe([]);
});

it('rejects upstream casual false positives', function () {
    expect(Chrono::parse('notoday'))->toBe([])
        ->and(Chrono::parse('tdtmr'))->toBe([])
        ->and(Chrono::parse('xyesterday'))->toBe([])
        ->and(Chrono::parse('nowhere'))->toBe([])
        ->and(Chrono::parse('noway'))->toBe([])
        ->and(Chrono::parse('knowledge'))->toBe([])
        ->and(Chrono::parse('mar'))->toBe([])
        ->and(Chrono::parse('jan'))->toBe([])
        ->and(Chrono::parse('do I have the money'))->toBe([])
        ->and(Chrono::parse('I may by here. May the force be with you. Theresa may become PM soon.'))->toBe([])
        ->and(Chrono::parse('XXX is set to be released in the second half of 2025'))->toBe([])
        ->and(Chrono::casual()->parseText('do I have the money'))->toBe([])
        ->and(Chrono::gb()->parseText('do I have the money'))->toBe([]);
});

it('rejects upstream random non-date patterns', function () {
    expect(Chrono::parse(' 3'))->toBe([])
        ->and(Chrono::parse('       1'))->toBe([])
        ->and(Chrono::parse('  11 '))->toBe([])
        ->and(Chrono::parse(' 0.5 '))->toBe([])
        ->and(Chrono::parse(' 35.49 '))->toBe([])
        ->and(Chrono::parse('12.53%'))->toBe([])
        ->and(Chrono::parse('6358fe2310> *5.0* / 5 Outstanding'))->toBe([])
        ->and(Chrono::parse('6358fe2310> *1.5* / 5 Outstanding'))->toBe([])
        ->and(Chrono::parse('Total: $1,194.09 [image: View Reservation'))->toBe([])
        ->and(Chrono::parse('at 6.5 kilograms'))->toBe([])
        ->and(Chrono::parse('ah that is unusual', null, ['forwardDate' => true]))->toBe([]);
});

it('rejects upstream url encoded and hyphenated number patterns', function () {
    expect(Chrono::parse('%e7%b7%8a'))->toBe([])
        ->and(Chrono::parse('https://tenor.com/view/%e3%83%89%e3%82%ad%e3%83%89%e3%82%ad-%e7%b7%8a%e5%bc%b5-%e5%a5%bd%e3%81%8d-%e3%83%8f%e3%83%bc%e3%83%88-%e5%8f%af%e6%84%9b%e3%81%84-gif-15876325'))->toBe([])
        ->and(Chrono::parse('1-2'))->toBe([])
        ->and(Chrono::parse('1-2-3'))->toBe([])
        ->and(Chrono::parse('4-5-6'))->toBe([])
        ->and(Chrono::parse('20-30-12'))->toBe([])
        ->and(Chrono::parse('2012'))->toBe([])
        ->and(Chrono::parse('2012-14'))->toBe([])
        ->and(Chrono::parse('2012-1400'))->toBe([])
        ->and(Chrono::parse('2200-25'))->toBe([]);
});

it('rejects upstream impossible dates and times', function () {
    expect(Chrono::parse('February 29, 2022'))->toBe([])
        ->and(Chrono::parse('02/29/2022'))->toBe([])
        ->and(Chrono::parse('June 31, 2022'))->toBe([])
        ->and(Chrono::parse('06/31/2022'))->toBe([])
        ->and(Chrono::parse('14PM'))->toBe([])
        ->and(Chrono::parse('25:12'))->toBe([])
        ->and(Chrono::parse('An appointment on 13/31/2018'))->toBe([])
        ->and(Chrono::parse('February 20 - 29, 2022'))->toBe([])
        ->and(Chrono::parse('June 10 - 31, 2022'))->toBe([]);
});

it('does not parse random numeric text as dates or times', function () {
    expect(Chrono::parse(' 3', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('       1', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('  11 ', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse(' 0.5 ', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse(' 35.49 ', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('12.53%', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('6358fe2310> *5.0* / 5 Outstanding', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('6358fe2310> *1.5* / 5 Outstanding', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('Total: $1,194.09 [image: View Reservation', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('%e7%b7%8a', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('https://tenor.com/view/%e3%83%89%e3%82%ad%e3%83%89%e3%82%ad-%e7%b7%8a%e5%bc%b5-%e5%a5%bd%e3%81%8d-%e3%83%8f%e3%83%bc%e3%83%88-%e5%8f%af%e6%84%9b%e3%81%84-gif-15876325', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('at 6.5 kilograms', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('ah that is unusual', '2012-08-10', ['forwardDate' => true]))
        ->toBe([])
        ->and(Chrono::parse('14PM', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('25:12', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('1-2', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('1-2-3', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('4-5-6', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('20-30-12', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2012', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2012-14', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2012-1400', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('2200-25', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('An appointment on 13/31/2018', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('for the year', '2012-08-10'))
        ->toBe([]);
});

it('does not parse version numbers as dates', function () {
    expect(Chrono::parse('Version: 1.1.3', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('Version: 1.1.30', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('Version: 1.10.30', '2012-08-10'))
        ->toBe([])
        ->and(Chrono::parse('1.5.3 - 2015-09-24', '2012-08-10')[0]->text)
        ->toBe('2015-09-24')
        ->and(Chrono::parse('1.5.30 - 2015-09-24', '2012-08-10')[0]->text)
        ->toBe('2015-09-24')
        ->and(Chrono::parse('1.50.30 - 2015-09-24', '2012-08-10')[0]->text)
        ->toBe('2015-09-24');
});

it('rejects upstream casual relative negative cases', function () {
    $casual = Chrono::casual();

    expect($casual->parseText('3y', '2015-07-10 12:14'))->toBe([])
        ->and($casual->parseText('1 m', '2015-07-10 12:14'))->toBe([])
        ->and($casual->parseText('the day', '2015-07-10 12:14'))->toBe([])
        ->and($casual->parseText('a day', '2015-07-10 12:14'))->toBe([])
        ->and(Chrono::parse('+am'))->toBe([])
        ->and(Chrono::parse('+them'))->toBe([]);
});

it('rejects upstream year-like and strict time expression false positives', function () {
    $strict = Chrono::strict();

    expect(Chrono::parse('2020'))->toBe([])
        ->and(Chrono::parse('2020  '))->toBe([])
        ->and(Chrono::parse('2019 to 2020'))->toBe([])
        ->and($strict->parseText("I'm at 101,194 points!"))->toBe([])
        ->and($strict->parseText("I'm at 101 points!"))->toBe([])
        ->and($strict->parseText("I'm at 10.1"))->toBe([])
        ->and($strict->parseText("I'm at 10"))->toBe([])
        ->and($strict->parseText('2020'))->toBe([])
        ->and($strict->parseText("I'm at 10.1 - 10.12"))->toBe([])
        ->and($strict->parseText("I'm at 10 - 10.1"))->toBe([])
        ->and($strict->parseText("I'm at 10 - 20"))->toBe([])
        ->and($strict->parseText('7-730'))->toBe([]);
});
