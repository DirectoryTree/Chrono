<?php

namespace DirectoryTree\Chrono;

interface Parser
{
    /**
     * Parse the text into date results.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array;
}
