<?php

namespace Chrono;

class ConfiguredChronoEngine implements ChronoEngine
{
    /**
     * Create a Chrono engine from the given configuration.
     */
    public function __construct(
        protected readonly Configuration $configuration,
    ) {}

    /**
     * Parse the given text into ordered Chrono results.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $results = array_merge(...array_map(
            fn (Parser $parser) => $parser->parse($text, $reference, $options),
            $this->configuration->parsers,
        ));

        $results = $this->attachReference($results, $reference);

        $results = array_map(
            fn (ParsedResult $result, int $order): array => [$result, $order],
            $results,
            array_keys($results),
        );

        usort($results, fn (array $a, array $b): int => $a[0]->index <=> $b[0]->index ?: $a[1] <=> $b[1]);

        $results = array_map(fn (array $result): ParsedResult => $result[0], $results);

        foreach ($this->configuration->refiners as $refiner) {
            $results = $this->attachReference(
                $refiner->refine($text, $results, $reference, $options),
                $reference,
            );
        }

        return array_values($results);
    }

    /**
     * Create a shallow copy of this engine with the same parser/refiner configuration.
     */
    public function clone(): self
    {
        return $this->newInstance(new Configuration(
            parsers: [...$this->configuration->parsers],
            refiners: [...$this->configuration->refiners],
        ));
    }

    /**
     * Return an engine instance with the given parser added.
     */
    public function withParser(Parser $parser, bool $prepend = false): self
    {
        return $this->newInstance($this->configuration->withParser($parser, $prepend));
    }

    /**
     * Return an engine instance with parsers matching the given class name removed.
     *
     * @param  class-string<Parser>  $parser
     */
    public function withoutParser(string $parser): self
    {
        return $this->newInstance($this->configuration->withoutParser($parser));
    }

    /**
     * Return an engine instance with the given refiner added.
     */
    public function withRefiner(Refiner $refiner, bool $prepend = false): self
    {
        return $this->newInstance($this->configuration->withRefiner($refiner, $prepend));
    }

    /**
     * Return an engine instance with refiners matching the given class name removed.
     *
     * @param  class-string<Refiner>  $refiner
     */
    public function withoutRefiner(string $refiner): self
    {
        return $this->newInstance($this->configuration->withoutRefiner($refiner));
    }

    /**
     * Create a new configured engine instance.
     */
    protected function newInstance(Configuration $configuration): static
    {
        return new static($configuration);
    }

    /**
     * Attach upstream-style reference metadata to each parsed result.
     *
     * @param  array<int, ParsedResult>  $results
     * @return array<int, ParsedResult>
     */
    protected function attachReference(array $results, Reference $reference): array
    {
        return array_map(
            fn (ParsedResult $result): ParsedResult => $result->withReference($reference),
            $results,
        );
    }
}
