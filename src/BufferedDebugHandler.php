<?php

namespace DirectoryTree\Chrono;

class BufferedDebugHandler implements DebugHandler
{
    /**
     * @var array<int, callable>
     */
    protected array $buffer = [];

    /**
     * Buffer a deferred debug callback.
     */
    public function debug(callable $callback): void
    {
        $this->buffer[] = $callback;
    }

    /**
     * Execute and clear the buffered debug callbacks.
     *
     * @return array<int, mixed>
     */
    public function executeBufferedBlocks(): array
    {
        $logs = array_map(fn (callable $callback): mixed => $callback(), $this->buffer);

        $this->buffer = [];

        return $logs;
    }
}
