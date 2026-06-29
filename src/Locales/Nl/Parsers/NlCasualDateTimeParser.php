<?php

namespace Chrono\Locales\Nl\Parsers;

use Chrono\Locales\Nl\CreatesParsedComponents;
use Chrono\Options;
use Chrono\ParsedResult;
use Chrono\Parser;
use Chrono\Reference;

readonly class NlCasualDateTimeParser implements Parser
{
    use CreatesParsedComponents;

    /**
     * Parse compound Dutch casual date-time references.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        preg_match_all('/(?<![\pL\pN])(?<date>gisteren|morgen|van)(?<time>ochtend|middag|namiddag|avond|nacht)(?=\W|$)/iu', $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        return array_map(function (array $match) use ($reference): ParsedResult {
            $dateText = mb_strtolower($match['date'][0]);
            $timeText = mb_strtolower($match['time'][0]);
            $date = match ($dateText) {
                'gisteren' => $reference->date->subDay(),
                'morgen' => $reference->date->addDay(),
                default => $reference->date,
            };

            $components = $this->components($date, [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ]);

            $components = (new NlCasualTimeParser)->timeComponents($components, $timeText);
            $components->addTag('parser/NLCasualDateTimeParser');

            return new ParsedResult($match[0][1], trim($match[0][0]), $components);
        }, $matches);
    }
}
