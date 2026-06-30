<?php

use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parsers\AbstractParserWithWordBoundary;
use DirectoryTree\Chrono\Pattern;
use DirectoryTree\Chrono\Reference;

it('builds regex patterns like upstream helpers', function () {
    $any = Pattern::matchAny(['jan' => 1, 'january' => 1, 'mar.' => 3]);
    $repeated = Pattern::repeatedTimeunitPattern('', '(\d+)\s*(hours?|minutes?)');

    expect($any)->toBe('(?:january|mar\.|jan)')
        ->and(preg_match("/^{$repeated}$/", '2 hours, 30 minutes'))->toBe(1)
        ->and(preg_match_all("/{$repeated}/", '2 hours, 30 minutes', $matches))->toBe(1)
        ->and(array_key_exists(1, $matches))->toBeFalse();
});

it('normalizes word-boundary parser captures like upstream', function () {
    $parser = new class extends AbstractParserWithWordBoundary
    {
        protected function innerPattern(Reference $reference, Options $options): string
        {
            return '(foo)(?<named>bar)';
        }

        protected function innerExtract(array $match, Reference $reference, Options $options): ParsedResult
        {
            return new ParsedResult(
                $match[0][1],
                $match[1][0].'|'.$match[2][0].'|'.$match['named'][0],
                new ParsedComponents($reference->date),
            );
        }
    };

    $result = $parser->parse('x foobar', Reference::make('2026-06-23'), new Options)[0];

    expect($result->index)->toBe(2)
        ->and($result->text)->toBe('foo|bar|bar');
});

it('continues word-boundary parser matching after failed extraction like upstream', function () {
    $parser = new class extends AbstractParserWithWordBoundary
    {
        protected function innerPattern(Reference $reference, Options $options): string
        {
            return 'aa';
        }

        protected function innerExtract(array $match, Reference $reference, Options $options): ?ParsedComponents
        {
            if ($match[0][1] === 0) {
                return null;
            }

            return new ParsedComponents($reference->date);
        }

        protected function patternLeftBoundary(): string
        {
            return '()';
        }
    };

    $results = $parser->parse('aaa', Reference::make('2026-06-23'), new Options);

    expect($results)->toHaveCount(1)
        ->and($results[0]->index)->toBe(1)
        ->and($results[0]->text)->toBe('aa');
});
