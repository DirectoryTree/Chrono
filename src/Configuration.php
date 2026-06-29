<?php

namespace Chrono;

readonly class Configuration
{
    /**
     * Create a parser/refiner configuration.
     *
     * @param  array<int, Parser>  $parsers
     * @param  array<int, Refiner>  $refiners
     */
    public function __construct(
        public readonly array $parsers = [],

        public readonly array $refiners = [],
    ) {}

    /**
     * Return a configuration with the given parser added.
     */
    public function withParser(Parser $parser, bool $prepend = false): self
    {
        return new self(
            $prepend ? [$parser, ...$this->parsers] : [...$this->parsers, $parser],
            $this->refiners,
        );
    }

    /**
     * Return a configuration without parsers matching the given class name.
     *
     * @param  class-string<Parser>  $parser
     */
    public function withoutParser(string $parser): self
    {
        return new self(
            array_values(array_filter(
                $this->parsers,
                fn (Parser $configuredParser): bool => ! $configuredParser instanceof $parser,
            )),
            $this->refiners,
        );
    }

    /**
     * Return a configuration with the given refiner added.
     */
    public function withRefiner(Refiner $refiner, bool $prepend = false): self
    {
        return new self(
            $this->parsers,
            $prepend ? [$refiner, ...$this->refiners] : [...$this->refiners, $refiner],
        );
    }

    /**
     * Return a configuration without refiners matching the given class name.
     *
     * @param  class-string<Refiner>  $refiner
     */
    public function withoutRefiner(string $refiner): self
    {
        return new self(
            $this->parsers,
            array_values(array_filter(
                $this->refiners,
                fn (Refiner $configuredRefiner): bool => ! $configuredRefiner instanceof $refiner,
            )),
        );
    }
}
