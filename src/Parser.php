<?php

namespace Chrono;

interface Parser
{
    /**
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array;
}
