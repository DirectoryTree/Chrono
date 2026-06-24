<?php

namespace Chrono;

use Carbon\CarbonImmutable;

class ParsedResult
{
    /**
     * Create a parsed result.
     *
     * @param  array<int, string>  $tags
     */
    public function __construct(
        public readonly int $index,
        public string $text,
        public readonly ParsedComponents $start,
        public readonly ?ParsedComponents $end = null,
        array $tags = [],
    ) {
        $this->addTags($tags);
    }

    /**
     * Get the parsed start date.
     */
    public function date(): CarbonImmutable
    {
        return $this->start->date();
    }

    /**
     * Clone the result and its component objects.
     */
    public function clone(): self
    {
        return new self(
            $this->index,
            $this->text,
            $this->start->clone(),
            $this->end?->clone(),
        );
    }

    /**
     * Add a tag to the result and its components.
     */
    public function addTag(string $tag): self
    {
        $this->start->addTag($tag);
        $this->end?->addTag($tag);

        return $this;
    }

    /**
     * Add tags to the result and its components.
     *
     * @param  iterable<string>  $tags
     */
    public function addTags(iterable $tags): self
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * Get the unique tags attached to the result and its components.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return array_values(array_unique([
            ...$this->start->tags(),
            ...($this->end?->tags() ?? []),
        ]));
    }

    /**
     * Convert the result to a debug string.
     */
    public function __toString(): string
    {
        $tags = $this->tags();
        sort($tags);

        return sprintf("[ParsedResult {index: %d, text: '%s', tags: %s ...}]", $this->index, $this->text, json_encode($tags, JSON_UNESCAPED_SLASHES));
    }
}
