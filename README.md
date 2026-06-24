# Chrono

A framework-agnostic PHP port of [`wanasit/chrono`](https://github.com/wanasit/chrono) that returns Carbon instances.

This repository is intentionally being built as a PHP package rather than a transpiled JavaScript artifact. The shape follows chrono's parser/refiner model so each locale and parser can be ported and tested independently.

Porting should follow upstream chrono source structure first, then Pest coverage. See [docs/upstream-alignment.md](docs/upstream-alignment.md) for the current source-alignment checklist and known divergences.

```php
use Chrono\Chrono;

$date = Chrono::parseDate('tomorrow at 4pm', '2026-06-23 09:00');

echo $date->toDateTimeString(); // 2026-06-24 16:00:00

$strict = Chrono::strict();

$strict->parseText('tomorrow'); // []

$custom = Chrono::casual()->withParser(new CompanyHolidayParser());
$copy = $custom->clone();
$custom = $custom->withoutParser(CompanyHolidayParser::class);
$custom = $custom->withRefiner(new BusinessHoursRefiner());
$custom = $custom->withoutRefiner(BusinessHoursRefiner::class);

$spanishDate = Chrono::spanish()->parseDateText('mañana', '2026-06-23 09:00');
$frenchDate = Chrono::french()->parseDateText('demain', '2026-06-23 09:00');
$germanDate = Chrono::german()->parseDateText('morgen', '2026-06-23 09:00');
$italianDate = Chrono::italian()->parseDateText('domani', '2026-06-23 09:00');
$russianDate = Chrono::russian()->parseDateText('завтра', '2026-06-23 09:00');
$ukrainianDate = Chrono::ukrainian()->parseDateText('завтра', '2026-06-23 09:00');
$britishDate = Chrono::gb()->parseDateText('6/10/2018', '2012-08-10');
```

## Current Port Status

- English ISO dates
- Native JavaScript/RFC date strings such as `Sat, 21 Feb 2015 11:50:48 -0500` and `Sat Nov 05 1994 22:45:30 GMT+0900 (JST)`
- English slash dates
- English month-name dates and simple ranges
- English little-endian month-name dates and ranges such as `3rd Feb 82` and `10 - 22 August 2012`
- English cross-month ranges such as `10 August - 12 September 2013`
- English year/month/day expressions such as `2012/8/10`, `2014.12.28`, and `2018 March 18`
- English month-name dates with `BC`, `BCE`, `AD`, `CE`, and Buddhist Era `BE` year labels
- English casual dates: today, tomorrow, yesterday, tonight, now
- English casual times: morning, afternoon, evening, night, midnight, midday, noon
- English weekdays: this, next, last
- English relative dates: `5 days ago`, `2 weeks from now`, `3w later`, `within half an hour`, `for 1 hour, 5 minutes, and 30 seconds`, `this month`, `next year`
- English standalone time expressions and time ranges
- English timezone offsets and common abbreviations
- DST-aware ambiguous timezone abbreviations such as `ET`, `CT`, `MT`, `PT`, and `CET`
- Timezone-aware reference arrays such as `['instant' => ..., 'timezone' => 'JST']`
- English GB / UK-style slash dates via `Chrono::british()` / `Chrono::gb()`
- English time-followed-by-date merging, such as `10:30 PST today`
- English date-followed-by-time merging, such as `tomorrow morning`
- English numeric date/time merging, such as `05/31/2024.14:15` and `14:15 05/31/2024`
- English time range meridiem inference, such as `8pm - 11` and `8 - 11pm`
- Result components for milliseconds and meridiem, including ISO fractional seconds such as `2016-05-07T23:45:00.487+01:00`
- Casual and strict English variants via `Chrono::casual()` and `Chrono::strict()`
- Source-shaped parser cloning via `clone()`
- Custom parser registration via `withParser()` and `withParsers()`
- Custom parser and refiner removal via `withoutParser()` and `withoutRefiner()`
- Custom refiner registration via `withRefiner()` and `withRefiners()`
- Spanish locale support via `Chrono::spanish()` / `Chrono::es()` for casual dates, casual times, slash dates, month-name dates/ranges, relative durations, weekdays, time expressions, and date/time merge refiners
- French locale support via `Chrono::french()` / `Chrono::fr()` for casual dates, casual times, slash dates, month-name dates/ranges, ISO date-time ranges, relative durations, weekdays, time expressions, timezone extraction, and merge refiners
- German locale support via `Chrono::german()` / `Chrono::de()` for casual dates, casual times, numeric dates, month-name dates/ranges, relative durations, weekdays, specific time expressions, timezone extraction, and merge refiners
- Italian locale support via `Chrono::italian()` / `Chrono::it()` for casual dates/times, year-month-day dates, slash month dates, month-name dates/ranges, relative durations, weekdays, time expressions, and merge refiners
- Dutch locale support via `Chrono::dutch()` / `Chrono::nl()` for casual dates/times, year-month-day dates, slash month dates, month-name dates, relative durations, weekdays, time expressions, and merge refiners
- Finnish locale support via `Chrono::finnish()` / `Chrono::fi()` for casual dates/times, month-name dates, relative durations, weekdays, time expressions, and merge refiners
- Swedish locale support via `Chrono::swedish()` / `Chrono::sv()` for casual dates, numeric dates, month-name dates/ranges, relative durations, weekdays, and merge refiners
- Portuguese locale support via `Chrono::portuguese()` / `Chrono::pt()` for casual dates, casual times, numeric dates, month-name dates/ranges, weekdays, time expressions, and merge refiners
- Japanese locale support via `Chrono::japanese()` / `Chrono::ja()` for casual dates, standard dates, slash dates, weekdays, weekday parentheses, time expressions, and merge refiners
- Vietnamese locale support via `Chrono::vietnamese()` / `Chrono::vi()` for casual dates/times, standard dates, month/year parsing, relative durations, weekdays, time expressions, and merge refiners
- Chinese locale support via `Chrono::chinese()` / `Chrono::zh()`, plus `Chrono::zhHans()` and `Chrono::zhHant()`, for casual dates, dates, deadlines, weekdays, relative weekdays, time expressions, and merge refiners
- Russian locale support via `Chrono::russian()` / `Chrono::ru()` for casual dates/times, month-name dates/ranges, relative durations, weekdays, time expressions, and merge refiners
- Ukrainian locale support via `Chrono::ukrainian()` / `Chrono::uk()` for casual dates/times, month-name dates/ranges, relative durations, weekdays, time expressions, and merge refiners

## Porting Roadmap

- Continue tightening parser behavior against upstream locale test fixtures.
- Keep porting edge cases from chrono's result/reference model, especially timezone and DST behavior.
- Preserve Pest fixtures that mirror upstream behavior as each parser/refiner is ported.
- Keep package docs and public API examples aligned with the implemented parser/refiner surface.

## Testing

```bash
./vendor/bin/pest
```
