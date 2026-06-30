<?php

namespace DirectoryTree\Chrono\Parsers;

use Carbon\CarbonImmutable;
use DirectoryTree\Chrono\Meridiem;
use DirectoryTree\Chrono\Options;
use DirectoryTree\Chrono\ParsedComponents;
use DirectoryTree\Chrono\ParsedResult;
use DirectoryTree\Chrono\Parser;
use DirectoryTree\Chrono\Reference;

abstract class AbstractTimeExpressionParser implements Parser
{
    /**
     * The options.
     */
    protected ?Options $options = null;

    /**
     * Return the locale-specific prefix allowed before the primary time.
     */
    abstract protected function primaryPrefix(): string;

    /**
     * Return the locale-specific connector allowed before a following time.
     */
    abstract protected function followingPhase(): string;

    /**
     * Parse numeric time expressions and ranges.
     *
     * @return array<int, ParsedResult>
     */
    public function parse(string $text, Reference $reference, Options $options): array
    {
        $this->options = $options;

        $prefix = $this->primaryPrefix();
        $suffix = $this->primarySuffix();

        preg_match_all(
            "/(^|\\s|T|\\b){$prefix}(?<hour>\\d{1,4})(?:(?:\\.|:|：)(?<minute>\\d{1,2})(?:(?::|：)(?<second>\\d{2})(?:\\.(?<millisecond>\\d{1,6}))?)?)?(?:\\s*(?<meridiem>a\\.?m\\.?|p\\.?m\\.?|am?|pm?))?{$suffix}/iu",
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        return array_values(array_filter(array_map(fn (array $match): ?ParsedResult => $this->result($text, $match, $reference), $matches)));
    }

    /**
     * Get the primary time suffix pattern.
     */
    protected function primarySuffix(): string
    {
        return '(?!\/)(?=\W|$)';
    }

    /**
     * Get the following time suffix pattern.
     */
    protected function followingSuffix(): string
    {
        return '(?!\/)(?=\W|$)';
    }

    /**
     * Get result.
     */
    protected function result(string $text, array $match, Reference $reference): ?ParsedResult
    {
        $start = $this->primaryTimeComponents($match, $reference);

        if ($start === null) {
            return null;
        }

        $leading = $match[1][0] ?? '';
        $index = $match[0][1] + strlen($leading);
        $resultText = substr($match[0][0], strlen($leading));
        $remainingText = substr($text, $match[0][1] + strlen($match[0][0]));
        $end = null;

        if (preg_match($this->followingPattern(), $remainingText, $followingMatch) === 1) {
            if ($this->looksLikeYearRange($resultText, $followingMatch[0])) {
                return null;
            }

            $end = $this->followingTimeComponents($this->offsetlessMatch($followingMatch), $reference, $start);

            if ($end !== null) {
                $resultText .= $followingMatch[0];
            }
        }

        if ($this->shouldRejectResult($match, $resultText, $end)) {
            return null;
        }

        return new ParsedResult($index, trim($resultText), $start, $end);
    }

    /**
     * Get the parser pattern.
     */
    protected function followingPattern(): string
    {
        $phase = $this->followingPhase();
        $suffix = $this->followingSuffix();

        return "/^({$phase})(?<hour>\\d{1,4})(?:(?:\\.|:|：)(?<minute>\\d{1,2})(?:(?:\\.|:|：)(?<second>\\d{1,2})(?:\\.(?<millisecond>\\d{1,6}))?)?)?(?:\\s*(?<meridiem>a\\.?m\\.?|p\\.?m\\.?|am?|pm?))?{$suffix}/iu";
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function primaryTimeComponents(array $match, Reference $reference): ?ParsedComponents
    {
        $time = $this->time($match, false);

        if ($time === null) {
            return null;
        }

        $date = $reference->date
            ->hour($time['hour'])
            ->minute($time['minute'])
            ->second($time['second'])
            ->millisecond($time['millisecond']);

        return $this->components($date, $time);
    }

    /**
     * Resolve parsed date components from the match.
     */
    protected function followingTimeComponents(array $match, Reference $reference, ParsedComponents $start): ?ParsedComponents
    {
        $time = $this->time($match, true);

        if ($time === null) {
            return null;
        }

        if (! $time['meridiemCertain'] && $this->hasAssignedMeridiem($start) && $start->get('hour') > 12) {
            if (((int) $start->get('hour') - 12) > $time['hour']) {
                $time['meridiem'] = Meridiem::AM->value;
            } elseif ($time['hour'] <= 12) {
                $time['hour'] += 12;
                $time['meridiem'] = Meridiem::PM->value;
                $time['meridiemCertain'] = true;
            }
        }

        $date = $reference->date
            ->hour($time['hour'])
            ->minute($time['minute'])
            ->second($time['second'])
            ->millisecond($time['millisecond']);

        if (! $this->hasAssignedMeridiem($start) && ($match['meridiem'][0] ?? '') !== '') {
            if ($time['meridiem'] === Meridiem::AM->value && $start->get('hour') === 12) {
                $start->imply('meridiem', Meridiem::AM->value);
                $start->assign('hour', 0);
            } elseif ($time['meridiem'] === Meridiem::PM->value && $start->get('hour') !== 12) {
                $start->imply('meridiem', Meridiem::PM->value);
                $start->assign('hour', (int) $start->get('hour') + 12);
            } else {
                $start->imply('meridiem', $time['meridiem']);
            }
        }

        if ($date->lessThan($start->date())) {
            $date = $date->addDay();
        }

        return $this->components($date, $time);
    }

    /**
     * @return array{hour: int, minute: int, second: int, millisecond: int, meridiem: int, meridiemCertain: bool}|null
     */
    protected function time(array $match, bool $following): ?array
    {
        $rawHour = $match['hour'][0];
        $hour = (int) $rawHour;
        $minute = 0;
        $second = 0;
        $millisecond = 0;
        $meridiem = null;

        if ($hour > 100) {
            if (strlen($rawHour) === 4 && ($match['minute'][0] ?? '') === '' && ($match['meridiem'][0] ?? '') === '') {
                return null;
            }

            if (($match['minute'][0] ?? '') !== '') {
                return null;
            }

            $minute = $hour % 100;
            $hour = intdiv($hour, 100);
        }

        if ($hour > 24) {
            return null;
        }

        if (($match['minute'][0] ?? '') !== '') {
            if (! $following && strlen($match['minute'][0]) === 1 && ($match['meridiem'][0] ?? '') === '') {
                return null;
            }

            $minute = (int) $match['minute'][0];
        }

        if ($minute >= 60) {
            return null;
        }

        if ($hour > 12) {
            $meridiem = Meridiem::PM->value;
        }

        if (($match['meridiem'][0] ?? '') !== '') {
            if ($hour > 12) {
                return null;
            }

            $ampm = strtolower(substr(str_replace('.', '', $match['meridiem'][0]), 0, 1));

            if ($ampm === 'a') {
                $meridiem = Meridiem::AM->value;
                $hour = $hour === 12 ? 0 : $hour;
            } elseif ($ampm === 'p') {
                $meridiem = Meridiem::PM->value;
                $hour = $hour === 12 ? 12 : $hour + 12;
            }
        }

        if (($match['second'][0] ?? '') !== '') {
            $second = (int) $match['second'][0];

            if ($second >= 60) {
                return null;
            }
        }

        if (($match['millisecond'][0] ?? '') !== '') {
            $millisecond = (int) substr($match['millisecond'][0], 0, 3);

            if ($millisecond >= 1000) {
                return null;
            }
        }

        return [
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'millisecond' => $millisecond,
            'meridiem' => $meridiem ?? ($hour < 12 ? Meridiem::AM->value : Meridiem::PM->value),
            'meridiemCertain' => $meridiem !== null,
        ];
    }

    /**
     * @param  array{hour: int, minute: int, second: int, millisecond: int, meridiem: int, meridiemCertain: bool}  $time
     */
    protected function components(CarbonImmutable $date, array $time): ParsedComponents
    {
        $components = new ParsedComponents($date, []);
        $components->assign('hour', $time['hour']);
        $components->assign('minute', $time['minute']);

        if ($time['second'] !== 0) {
            $components->assign('second', $time['second']);
        }

        if ($time['millisecond'] !== 0) {
            $components->assign('millisecond', $time['millisecond']);
        }

        if ($time['meridiemCertain']) {
            $components->assign('meridiem', $time['meridiem']);
            $components->addTag('meridiem');
        } else {
            $components->imply('meridiem', $time['meridiem']);
        }

        return $components;
    }

    /**
     * Determine whether the text looks like a year range.
     */
    protected function looksLikeYearRange(string $text, string $followingText): bool
    {
        if (preg_match('/^\d{3,4}/', $text) !== 1) {
            return false;
        }

        return preg_match('/^\s*([+-])\s*\d{2,4}$/', $followingText) === 1
            || preg_match('/^\s*([+-])\s*\d{2}\W\d{2}/', $followingText) === 1;
    }

    /**
     * Determine whether the parsed result should be rejected.
     */
    protected function shouldRejectResult(array $match, string $text, ?ParsedComponents $end): bool
    {
        return false;
    }

    /**
     * Determine whether the components have an assigned meridiem.
     */
    protected function hasAssignedMeridiem(ParsedComponents $components): bool
    {
        return in_array('meridiem', $components->tags(), true);
    }

    /**
     * @return array<string, array{0: string}>
     */
    protected function offsetlessMatch(array $match): array
    {
        return array_map(fn (string $value): array => [$value], $match);
    }
}
