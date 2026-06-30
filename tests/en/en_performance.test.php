<?php

use DirectoryTree\Chrono\Chrono;

it('does not backtrack excessively across whitespace-only duration fragments', function () {
    $text = 'BGR3                                                                                         '.
        '                                                                                        186          '.
        '                                      days                                                           '.
        '                                                                                                     '.
        '                                                                                                     '.
        '           18                                                hours                                   '.
        '                                                                                                     '.
        '                                                                                                     '.
        '                                   37                                                minutes         '.
        '                                                                                                     '.
        '                                                                                                     '.
        '                                                             01                                      '.
        '          seconds';

    $startedAt = microtime(true);

    $results = Chrono::parse($text);

    expect($results)->toBe([])
        ->and((microtime(true) - $startedAt) * 1000)->toBeLessThan(1000);
});
