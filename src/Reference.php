<?php

namespace DirectoryTree\Chrono;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

readonly class Reference
{
    /**
     * The original reference instant before timezone adjustment.
     */
    public CarbonImmutable $instant;

    /**
     * Create a reference date wrapper.
     */
    public function __construct(
        public CarbonImmutable $date,
        public ?int $timezoneOffset = null,
        ?CarbonImmutable $instant = null,
    ) {
        $this->instant = $instant ?? $date;
    }

    /**
     * Get the reference date adjusted into the requested reference timezone.
     */
    public function getDateWithAdjustedTimezone(): CarbonImmutable
    {
        return $this->date;
    }

    /**
     * Get the minute adjustment between the given date offset and the reference timezone.
     */
    public function getSystemTimezoneAdjustmentMinute(?CarbonInterface $date = null, ?int $overrideTimezoneOffset = null): int
    {
        $date = $date?->toImmutable() ?? CarbonImmutable::now();
        $currentTimezoneOffset = $date->offsetMinutes;
        $targetTimezoneOffset = $overrideTimezoneOffset ?? $this->timezoneOffset ?? $currentTimezoneOffset;

        return $currentTimezoneOffset - $targetTimezoneOffset;
    }

    /**
     * Get the reference timezone offset in minutes.
     */
    public function getTimezoneOffset(): int
    {
        return $this->timezoneOffset ?? $this->date->offsetMinutes;
    }

    /**
     * Create a reference date from Chrono-compatible input.
     *
     * @param  CarbonInterface|string|array{instant?: CarbonInterface|string|null, timezone?: int|string|null}|null  $reference
     */
    public static function make(CarbonInterface|string|array|null $reference = null, ?Options $options = null): self
    {
        if (is_array($reference)) {
            $instant = self::date($reference['instant'] ?? null);
            $timezoneOffset = self::timezoneOffset($reference['timezone'] ?? null, $instant, $options);
            $date = $instant;

            if (($timezone = self::timezoneName($timezoneOffset)) !== null) {
                $date = $date->setTimezone($timezone);
            }

            return new self($date, timezoneOffset: $timezoneOffset, instant: $instant);
        }

        return new self(self::date($reference));
    }

    /**
     * Create the base reference date.
     */
    protected static function date(CarbonInterface|string|null $reference = null): CarbonImmutable
    {
        if ($reference instanceof CarbonInterface) {
            return $reference->toImmutable();
        }

        if (is_string($reference) && ($date = self::javascriptDateString($reference)) !== null) {
            return $date;
        }

        return new CarbonImmutable($reference ?? 'now');
    }

    /**
     * Parse a JavaScript date string into a Carbon instance.
     */
    protected static function javascriptDateString(string $reference): ?CarbonImmutable
    {
        $reference = trim(preg_replace('/\s*\([^)]*\)\s*$/', '', $reference) ?? $reference);

        if (preg_match('/^[A-Z][a-z]{2}\s+[A-Z][a-z]{2}\s+\d{1,2}\s+\d{4}\s+\d{2}:\d{2}:\d{2}\s+GMT[+-]\d{4}$/', $reference) !== 1) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat(
            'M j Y H:i:s \G\M\TO',
            preg_replace('/^[A-Z][a-z]{2}\s+/', '', $reference) ?? $reference,
        );

        return $date === false ? null : CarbonImmutable::instance($date);
    }

    /**
     * Resolve a Chrono-compatible timezone into an offset in minutes.
     */
    protected static function timezoneOffset(int|string|null $timezone, CarbonImmutable $date, ?Options $options): ?int
    {
        if ($timezone === null || $timezone === '') {
            return null;
        }

        if (is_int($timezone) || is_numeric($timezone)) {
            return (int) $timezone;
        }

        return Timezone::offset($timezone, $date, $options);
    }

    /**
     * Resolve a timezone offset into a PHP timezone name.
     */
    protected static function timezoneName(?int $offset): ?string
    {
        return $offset === null ? null : self::timezoneNameFromOffset($offset);
    }

    /**
     * Resolve the timezone offset.
     */
    protected static function timezoneNameFromOffset(int $offset): string
    {
        $sign = $offset < 0 ? '-' : '+';
        $offset = abs($offset);

        return sprintf('%s%02d:%02d', $sign, intdiv($offset, 60), $offset % 60);
    }
}
