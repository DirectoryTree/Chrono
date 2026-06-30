<?php

namespace DirectoryTree\Chrono;

interface ChronoEngine
{
    /**
     * Parse the given text into Chrono results.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array;

    /**
     * Create a shallow copy of this engine with the same parser/refiner configuration.
     */
    public function clone(): self;

    /**
     * Return an engine instance with the given parser added.
     */
    public function withParser(Parser $parser, bool $prepend = false): self;

    /**
     * Return an engine instance with parsers matching the given class name removed.
     *
     * @param  class-string<Parser>  $parser
     */
    public function withoutParser(string $parser): self;

    /**
     * Return an engine instance with the given refiner added.
     */
    public function withRefiner(Refiner $refiner, bool $prepend = false): self;

    /**
     * Return an engine instance with refiners matching the given class name removed.
     *
     * @param  class-string<Refiner>  $refiner
     */
    public function withoutRefiner(string $refiner): self;
}
