<?php

namespace DirectoryTree\Chrono;

readonly class Configuration
{
    /**
     * Create a new parser/refiner configuration.
     *
     * @param  array<int, Parser>  $parsers
     * @param  array<int, Refiner>  $refiners
     */
    public static function make(array $parsers = [], array $refiners = []): self
    {
        return new self($parsers, $refiners);
    }

    /**
     * Create a parser/refiner configuration.
     *
     * @param  array<int, Parser>  $parsers
     * @param  array<int, Refiner>  $refiners
     */
    public function __construct(
        protected readonly array $parsers = [],

        protected readonly array $refiners = [],
    ) {}

    /**
     * Get the configured parsers.
     *
     * @return array<int, Parser>
     */
    public function parsers(): array
    {
        return $this->parsers;
    }

    /**
     * Get the configured refiners.
     *
     * @return array<int, Refiner>
     */
    public function refiners(): array
    {
        return $this->refiners;
    }

    /**
     * Determine whether the configuration has the given parser.
     *
     * @param  class-string<Parser>  $parser
     */
    public function hasParser(string $parser): bool
    {
        foreach ($this->parsers as $configuredParser) {
            if ($configuredParser instanceof $parser) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the configuration has the given refiner.
     *
     * @param  class-string<Refiner>  $refiner
     */
    public function hasRefiner(string $refiner): bool
    {
        foreach ($this->refiners as $configuredRefiner) {
            if ($configuredRefiner instanceof $refiner) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return a configuration with the given parser added.
     */
    public function addParser(Parser $parser): self
    {
        return new self([...$this->parsers, $parser], $this->refiners);
    }

    /**
     * Return a configuration with the given parser added to the beginning.
     */
    public function prependParser(Parser $parser): self
    {
        return new self([$parser, ...$this->parsers], $this->refiners);
    }

    /**
     * Return a configuration without parsers matching the given class name.
     *
     * @param  class-string<Parser>  $parser
     */
    public function removeParser(string $parser): self
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
    public function addRefiner(Refiner $refiner): self
    {
        return new self($this->parsers, [...$this->refiners, $refiner]);
    }

    /**
     * Return a configuration with the given refiner added to the beginning.
     */
    public function prependRefiner(Refiner $refiner): self
    {
        return new self($this->parsers, [$refiner, ...$this->refiners]);
    }

    /**
     * Return a configuration without refiners matching the given class name.
     *
     * @param  class-string<Refiner>  $refiner
     */
    public function removeRefiner(string $refiner): self
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
