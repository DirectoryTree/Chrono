<?php

namespace Chrono;

class Options
{
    /**
     * Create a parser options bag.
     *
     * @param  array<string, mixed>  $options
     */
    public function __construct(protected readonly array $options = []) {}

    /**
     * Determine whether inferred dates should prefer the future.
     */
    public function forwardDate(): bool
    {
        return (bool) ($this->options['forwardDate'] ?? false);
    }

    /**
     * Determine whether strict parsing is enabled.
     */
    public function strict(): bool
    {
        return (bool) ($this->options['strict'] ?? false);
    }

    /**
     * Get custom timezone aliases and rules.
     *
     * @return array<string, mixed>
     */
    public function timezones(): array
    {
        return $this->options['timezones'] ?? [];
    }
}
