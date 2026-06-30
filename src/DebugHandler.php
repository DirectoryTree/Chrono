<?php

namespace DirectoryTree\Chrono;

interface DebugHandler
{
    /**
     * Buffer or consume a deferred debug callback.
     */
    public function debug(callable $callback): void;
}
