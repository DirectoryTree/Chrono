<?php

use DirectoryTree\Chrono\Chrono;

it('parses japanese casual date references', function () {
    $japanese = Chrono::ja();
    $today = $japanese->parse('今日', '2012-08-10 09:30')[0];
    $tonight = $japanese->parse('今夜', '2012-08-10 09:30')[0];

    expect($today->start->date()->toDateTimeString())->toBe('2012-08-10 12:00:00')
        ->and($today->start->tags())->toContain('parser/JPCasualDateParser')
        ->and($japanese->parse('今日感じたことを忘れずに', '2012-08-10 12:00')[0]->text)->toBe('今日')
        ->and($japanese->parse('きょう感じたことを忘れずに', '2012-08-10 12:00')[0]->text)->toBe('きょう')
        ->and($japanese->parse('本日はお日柄もよく', '2012-08-10 12:00')[0]->text)->toBe('本日')
        ->and($japanese->parse('ほんじつはお日柄もよく', '2012-08-10 12:00')[0]->text)->toBe('ほんじつ')
        ->and($japanese->date('昨日', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-09 12:00:00')
        ->and($japanese->parse('昨日の全国観測値ランキング', '2012-08-10 12:00')[0]->text)->toBe('昨日')
        ->and($japanese->parse('きのうの全国観測値ランキング', '2012-08-10 12:00')[0]->text)->toBe('きのう')
        ->and($japanese->date('明日', '2012-08-10 09:30')?->toDateTimeString())->toBe('2012-08-11 12:00:00')
        ->and($japanese->parse('明日の天気は晴れです', '2012-08-10 12:00')[0]->text)->toBe('明日')
        ->and($japanese->parse('あしたの天気は晴れです', '2012-08-10 12:00')[0]->text)->toBe('あした')
        ->and($tonight->start->date()->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($japanese->parse('こんやには雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('こんや')
        ->and($japanese->date('今夕には雨が降るでしょう', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 22:00:00')
        ->and($japanese->parse('こんゆうには雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('こんゆう')
        ->and($japanese->parse('今晩には雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('今晩')
        ->and($japanese->parse('こんばんには雨が降るでしょう', '2012-08-10 12:00')[0]->text)->toBe('こんばん')
        ->and($japanese->parse('今朝食べたパンは美味しかった', '2012-08-10 12:00')[0]->text)->toBe('今朝')
        ->and($japanese->date('けさ食べたパンは美味しかった', '2012-08-10 12:00')?->toDateTimeString())->toBe('2012-08-10 06:00:00');
});
