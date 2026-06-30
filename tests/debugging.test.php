<?php

use DirectoryTree\Chrono\BufferedDebugHandler;

it('buffers debug callbacks until explicitly executed', function () {
    $debugHandler = new BufferedDebugHandler;
    $calls = [];

    $debugHandler->debug(function () use (&$calls) {
        $calls[] = 'first';

        return 'a';
    });

    $debugHandler->debug(function () use (&$calls) {
        $calls[] = 'second';

        return 'b';
    });

    expect($calls)->toBe([])
        ->and($debugHandler->executeBufferedBlocks())->toBe(['a', 'b'])
        ->and($calls)->toBe(['first', 'second'])
        ->and($debugHandler->executeBufferedBlocks())->toBe([]);
});
