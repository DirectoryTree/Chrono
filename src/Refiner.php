<?php

namespace DirectoryTree\Chrono;

interface Refiner
{
    /**
     * Refine the parsed date results.
     *
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    public function refine(string $text, array $results, Reference $reference, Options $options): array;
}
