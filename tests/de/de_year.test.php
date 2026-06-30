<?php

use DirectoryTree\Chrono\Chrono;

it('parses german year numbers with era labels', function () {
    $german = Chrono::de();

    $beforeCommonEra = $german->parseText('10. August 234 v.u.Z.', '2012-08-10')[0];
    $commonEra = $german->parseText('10. August 88 nuZ', '2012-08-10')[0];
    $commonEraShort = $german->parseText('10. August 88 uZ', '2012-08-10')[0];
    $christBefore = $german->parseText('10. August 234 v.Chr.', '2012-08-10')[0];
    $christAfter = $german->parseText('10. August 88 nC', '2012-08-10')[0];
    $beforeCurrentEra = $german->parseText('10. August 234 v.d.Z.', '2012-08-10')[0];
    $currentEra = $german->parseText('10. August 88 ndZ', '2012-08-10')[0];

    expect($beforeCommonEra->index)->toBe(0)
        ->and($beforeCommonEra->text)->toBe('10. August 234 v.u.Z.')
        ->and($beforeCommonEra->start->get('year'))->toBe(-234)
        ->and($beforeCommonEra->start->get('month'))->toBe(8)
        ->and($beforeCommonEra->start->get('day'))->toBe(10)
        ->and($commonEra->text)->toBe('10. August 88 nuZ')
        ->and($commonEra->start->get('year'))->toBe(88)
        ->and($commonEra->start->get('month'))->toBe(8)
        ->and($commonEra->start->get('day'))->toBe(10)
        ->and($commonEraShort->text)->toBe('10. August 88 uZ')
        ->and($commonEraShort->start->get('year'))->toBe(88)
        ->and($christBefore->text)->toBe('10. August 234 v.Chr.')
        ->and($christBefore->start->get('year'))->toBe(-234)
        ->and($christAfter->text)->toBe('10. August 88 nC')
        ->and($christAfter->start->get('year'))->toBe(88)
        ->and($beforeCurrentEra->text)->toBe('10. August 234 v.d.Z.')
        ->and($beforeCurrentEra->start->get('year'))->toBe(-234)
        ->and($currentEra->text)->toBe('10. August 88 ndZ')
        ->and($currentEra->start->get('year'))->toBe(88);
});
