<?php

namespace DirectoryTree\Chrono;

readonly class Pattern
{
    /**
     * Build a regex pattern that matches repeated time-unit fragments.
     */
    public static function repeatedTimeunitPattern(
        string $prefix,
        string $singleTimeunitPattern,
        string $connectorPattern = '\\s{0,5},?\\s{0,5}'
    ): string {
        $singleTimeunitPattern = preg_replace('/\((?!\?)/', '(?:', $singleTimeunitPattern) ?? $singleTimeunitPattern;

        return "{$prefix}{$singleTimeunitPattern}(?:{$connectorPattern}{$singleTimeunitPattern}){0,10}";
    }

    /**
     * Build an alternation pattern from dictionary terms.
     *
     * @param  array<int|string, mixed>  $dictionary
     */
    public static function matchAny(array $dictionary): string
    {
        $terms = array_is_list($dictionary) ? $dictionary : array_keys($dictionary);

        usort($terms, fn (string $left, string $right): int => strlen($right) <=> strlen($left));

        return '(?:'.implode('|', array_map(fn (string $term): string => preg_quote($term, '/'), $terms)).')';
    }
}
