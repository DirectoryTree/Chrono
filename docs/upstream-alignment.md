# Upstream Alignment

This package should be ported source-first from `wanasit/chrono`, then verified with Pest tests that mirror upstream behavior.

The PHP implementation may adapt details for Carbon, PHP types, and Laravel-style package conventions, but parser/refiner boundaries should stay close to upstream unless there is a documented reason to diverge.

## Porting Rules

1. Identify the upstream parser/refiner/configuration file before implementing behavior.
2. Port the parser/refiner boundary first, then adapt internals to PHP.
3. Prefer shared common parsers/refiners over locale-engine inline merge/filter logic.
4. Add Pest coverage from the matching upstream test file.
5. Document intentional divergences in this file.

## Core Architecture

| Upstream | Current PHP | Status | Notes |
| --- | --- | --- | --- |
| `src/chrono.ts` | `src/Chrono.php`, `src/ChronoEngine.php`, `src/Configuration.php`, `src/ConfiguredChronoEngine.php` | Partial | PHP now has a configuration-backed engine runner used by all current locale engines. Source-style cloning is exposed through `clone()`, while upstream mutable parser/refiner-array customization maps to immutable `withParser()`, `withRefiner()`, `withoutParser()`, and `withoutRefiner()` methods. Parser results now sort by start index while preserving parser order for same-index ties like upstream. Upstream system-test replacement examples are covered for slash-date parser replacement and English casual-relative parser option replacement. |
| `src/results.ts` | `src/ParsedResult.php`, `src/ParsedComponents.php`, `src/Reference.php` | Partial | Carbon-backed results exist. Parsed components now keep separate known and implied date/time values, delete both value sets like upstream, default empty component time to implied noon like upstream `ParsingComponents`, and preserve explicitly assigned values for validity checks before Carbon normalization, including upstream-style hour/minute/second/millisecond bounds and proleptic month/day validation for before-common-era years. Constructor-provided integer component values now rebuild the backing Carbon date like upstream's component-derived `date()` behavior, while PHP-only boolean certainty markers are normalized from the provided Carbon date values. Result tags are component-derived like upstream; the PHP constructor still accepts tags as a convenience and immediately applies them to components. Component and result cloning now mirror upstream by copying structural values without carrying tags, while merge helpers explicitly re-add source tags. Component and result debug strings include tags and component state. Reference-array timezone resolution now uses the shared timezone map and custom ambiguous timezone options like upstream `toTimezoneOffset()`, and `Reference` exposes timezone-adjusted date/offset helpers corresponding to upstream `ReferenceWithTimezone`. `isOnlyTime()`, `isOnlyWeekdayComponent()`, and `isDateWithUnknownYear()` now follow upstream component predicates. Reference metadata and component behavior are not full upstream parity yet. |
| `src/timezone.ts` | `src/Timezone.php` | Partial | The upstream timezone abbreviation map is mirrored, ambiguous timezone offsets are resolved against the parsed/reference date, and custom timezone overrides support fixed and DST-sensitive offsets. `Timezone::getNthWeekdayOfMonth()` and `Timezone::getLastWeekdayOfMonth()` now mirror upstream exported DST-boundary helpers for custom ambiguous timezones. |
| `src/common/abstractRefiners.ts`, `src/common/refiners/AbstractMergeDateTimeRefiner.ts`, `src/common/refiners/AbstractMergeDateRangeRefiner.ts` | `src/Refiners/Filter.php`, `src/Refiners/MergingRefiner.php`, `src/Refiners/AbstractMergeDateTimeRefiner.php`, `src/Refiners/AbstractMergeDateRangeRefiner.php` | Partial | PHP equivalents exist. Simple locale date/time refiners for German, Finnish, Spanish, Italian, Dutch, Russian, Ukrainian, and Vietnamese now compose `AbstractMergeDateTimeRefiner`; English and Portuguese remain custom for locale-specific timezone/casual behavior. English, German, Finnish, Spanish, French, Italian, Dutch, Portuguese, Russian, Ukrainian, Chinese, Japanese, and Vietnamese date-range refiners now compose `AbstractMergeDateRangeRefiner`; the common range refiner no longer requires day/weekday endpoints, allowing upstream-style month/year ranges such as `July 2020 to August 2020`; Dutch overrides missing-component implication to preserve casual range time behavior, and Vietnamese overrides endpoint detection so month/year endpoints can form ranges. Suffix-scanning refiners remain standalone. |
| `src/configurations.ts` | `src/Configuration.php`, locale configuration factories | Partial | All current locale engines now expose source-shaped configuration factories. Parser coverage and common configuration ordering are still not full upstream parity. |
| `src/common/casualReferences.ts` | `src/CasualReferences.php` | Partial | Shared casual-reference helpers exist for now/today/yesterday/tomorrow/day offsets/day periods. Common `lastNight()` now mirrors upstream by moving to the previous date only when the reference time is before 06:00; English keeps its parser-local `last night` behavior from `ENCasualDateParser`, which moves to the previous date after 06:00. |
| `src/utils/pattern.ts` | `src/Pattern.php` | Partial | Shared pattern helpers now exist for upstream-style dictionary alternation and repeated time-unit patterns. Finnish, Dutch, Vietnamese, Swedish, Russian, and Ukrainian relative-duration traits use the shared repeated time-unit helper. Finnish, Dutch, Italian, Russian, Ukrainian, Swedish, Vietnamese, and Portuguese constants now delegate dictionary alternation to `Pattern::matchAny()`; several parser-local alternations still build patterns inline. |
| `src/utils/dates.ts` | `src/Dates.php` | Partial | Shared date/time component assignment helpers now exist and are used by casual references, relative component creation, date/time merge calculations, and direct casual parser date assignment in Russian, Ukrainian, Chinese, and Swedish. Some parser-local component assignment still remains until grouped parser internals are split further. |
| `src/calculation/duration.ts` | `src/Calculation/Duration.php` | Partial | Duration addition, shorthand unit normalization, fractional cascading, empty duration defaults, and duration reversal now have source-shaped coverage from `calculation_duration.test.ts`, including full single-unit and shorthand-unit assertions. |
| `src/calculation/weekdays.ts` | `src/Calculation/Weekdays.php` | Partial | Closest, this, last, and next weekday distances now have source-shaped coverage from `calculation_weekdays.test.ts`, including next-week Sunday behavior and closest weekday ties. Weekday component creation now mirrors upstream's timezone-adjusted reference date and default implied noon time, including JST/PST timezone assertions. |

## Common Parsers And Refiners

| Upstream | Current PHP | Status | Notes |
| --- | --- | --- | --- |
| `common/parsers/AbstractParserWithWordBoundary.ts` | `Parsers/AbstractParserWithWordBoundary.php` | Partial | Shared upstream-style left-boundary parser base exists for PHP's direct `parse()` contract, including upstream-style numeric capture normalization after removing the boundary wrapper while preserving named captures. It now follows upstream's iterative parser loop for failed extractions, advancing from the failed match start so overlapping later matches can still be extracted. English, Spanish, Portuguese, French, Finnish, Dutch, Italian, German, Russian, Ukrainian, Vietnamese, and Swedish casual date/time parsers now compose it; Dutch, English, Finnish, French, German, Italian, Russian, Spanish, Swedish, Ukrainian, and Vietnamese relative time-unit parsing also compose it like upstream where matching upstream parser files exist, including German modifier-relative units and English prefixless within-duration matches with PHP's existing match trimming preserved. Other parser-local boundary checks can migrate incrementally. |
| `common/parsers/ISOFormatParser.ts` | `Parsers/IsoFormatParser.php` | Partial | Common parser namespace exists, now composes the shared upstream-style word-boundary parser base, parses upstream `T`-separated ISO timezone suffixes, preserves four-digit ISO fractional milliseconds like upstream `parseInt`, and tags results with `parser/ISOFormatParser`. Space-separated date/time text is handled by the date-time merge path instead of the ISO parser. |
| `common/parsers/SlashDateFormatParser.ts` | `Parsers/SlashDateFormatParser.php`, locale-specific slash/date parsers | Partial | Common numeric slash parser exists, tags results with `parser/SlashDateFormatParser`, accepts trailing sentence punctuation like upstream, applies upstream-style two-digit year normalization through `findMostLikelyADYear`, and English/French/Spanish compose it. Locale slash parsers still keep extras such as weekday prefixes and English month-name slash forms. |
| `common/parsers/AbstractTimeExpressionParser.ts` | `Parsers/AbstractTimeExpressionParser.php`, locale-specific time parsers | Partial | Common parser base exists and French/Spanish/Italian/German/Dutch/Finnish/English compose it through source-shaped locale wrappers; meridiem is assigned or implied by parser logic instead of being derived globally from hour-only components, matching upstream `ParsingComponents`. Following range endpoints only roll to the next day when strictly earlier than the start like upstream, so equal ranges such as `10pm-10pm` stay same-day. Legacy fallback time parsers still need cleanup. |
| `common/refiners/OverlapRemovalRefiner.ts` | `Refiners/OverlapRemovalRefiner.php` | Partial | Shared refiner exists and all current locale engines use it. Overlapping results now keep the longer result like upstream, instead of greedily keeping the first result. |
| `common/refiners/ExtractTimezoneOffsetRefiner.ts` | `Refiners/ExtractTimezoneOffsetRefiner.php` | Partial | Split common refiner exists and owns numeric offset extraction, including upstream's permissive compact-offset matching such as extracting `+0900` from `+09001`. PHP still keeps a duration-unit guard so postfix duration expressions are not reclassified as timezones. |
| `common/refiners/ExtractTimezoneAbbrRefiner.ts` | `Refiners/ExtractTimezoneAbbrRefiner.php`, `Timezone.php` | Partial | Split refiner and common lookup helper exist. The PHP abbreviation map now mirrors upstream `timezone.ts`, abbreviation suffixes use upstream's optional opening/closing parenthesis matching, range end components receive abbreviation offsets independently like upstream, and relative results now follow upstream case guarding so lowercase words such as `get` are not consumed as `GET` timezone abbreviations while uppercase `GET` remains valid. English applies abbreviation extraction before and after merge refiners until the time parser consumes abbreviation suffixes more like upstream. |
| `common/refiners/ForwardDateRefiner.ts` | `Refiners/ForwardDateRefiner.php`, parser-local forwardDate logic | Partial | Shared refiner exists and is wired into locale configurations. Some parsers still keep local forward-date handling until parser internals are split further. |
| `common/refiners/MergeWeekdayComponentRefiner.ts` | `Refiners/MergeWeekdayComponentRefiner.php`, parser-local weekday handling | Partial | Shared refiner exists and is wired into locale configurations. Existing locale parsers still directly parse many weekday-prefixed forms. |
| `common/refiners/UnlikelyFormatFilter.ts` | `Refiners/UnlikelyFormatFilter.php`, parser-local filtering | Partial | Shared filter exists and is wired into locale configurations. Strict locale configurations now pass strict mode into the filter like upstream `includeCommonConfiguration(..., strictMode)`, including weekday-only rejection for upstream cases such as Vietnamese `thứ hai`. Some parser-local filtering remains until grouped parsers are split. |

## Locale Configuration Alignment

### English

Upstream default configuration:

- `SlashDateFormatParser`
- `ENTimeUnitWithinFormatParser`
- `ENMonthNameLittleEndianParser`
- `ENMonthNameMiddleEndianParser`
- `ENWeekdayParser`
- `ENSlashMonthFormatParser`
- `ENTimeExpressionParser`
- `ENTimeUnitAgoFormatParser`
- `ENTimeUnitLaterFormatParser`
- common refiners
- English relative/date-time/year-suffix/date-range refiners

Current PHP:

- `IsoFormatParser`
- `EnYearMonthDayParser`
- `EnSlashDateParser`
- `EnSlashMonthFormatParser`
- `EnMonthNameLittleEndianDateTimeParser`
- `EnMonthNameLittleEndianParser`
- `EnMonthNameMiddleEndianParser`
- `EnMonthNameTrailingYearParser`
- `EnMonthNameRangeParser`
- `EnMonthNameOrdinalParser`
- `EnMonthNameWeekdayParser`
- `EnMonthNameParser`
- `EnCasualTimeParser`
- `EnCasualDateParser`
- `EnTimeUnitWithinFormatParser`
- `EnRelativeDateFormatParser`
- `EnTimeUnitCasualRelativeFormatParser`
- `EnTimeUnitAgoFormatParser`
- `EnTimeUnitLaterFormatParser`
- English merge/refiner operations extracted under `Locales/En/Refiners`
- `EnUnlikelyFormatFilter`

Current PHP now uses `Configuration` and `ConfiguredChronoEngine`. Date/time, time/date, time-range, relative, postfix-offset, and date-range merge behavior has been moved into English refiner classes.

Status: behavior-rich but parser coverage remains structurally diverged. English casual date and casual time parsing now use source-shaped `EnCasualDateParser` and `EnCasualTimeParser`. English year-month-day parsing now uses source-shaped `EnYearMonthDayParser`. English slash-date parsing now composes the common `SlashDateFormatParser` through `EnSlashDateParser`, while preserving English month-name and weekday-prefixed slash extensions. `EnChrono::createBritishConfiguration()` and `Chrono::gb()` map to upstream `en.GB` by composing `EnSlashDateParser(littleEndian: true)` and `EnMonthNameMiddleEndianParser(shouldSkipYearLikeDate: true)`. English relative-date parsing now uses source-shaped time-unit parser classes for within, casual-relative, ago, later, and relative-date unit expressions; the within, ago, later, and casual-relative parsers compose the shared word-boundary base like upstream while preserving PHP's prefixless within-match trimming and trailing-time casual-relative extension. Numeric slash month/year parsing has been split into `EnSlashMonthFormatParser`; month-only/month-year parsing has been split into `EnMonthNameParser`, including upstream `forwardDate` handling, upstream-shaped two-digit `YEAR_PATTERN` handling, shared closest-year helper usage like upstream `findYearClosestToRef`, `parser/ENMonthNameParser` component tags, fallback skipping for rejected `Month Day, Year` dates, and upstream-style rejection of unlikely bare three-letter month abbreviations such as `Mar` while preserving contextual forms such as `in Jan`; common little-endian month-name dates into `EnMonthNameLittleEndianParser`, little-endian month date-time forms into `EnMonthNameLittleEndianDateTimeParser`, common middle-endian month-name dates into `EnMonthNameMiddleEndianParser` with upstream `parser/ENMonthNameMiddleEndianParser` tags, trailing-year date-time/range forms into `EnMonthNameTrailingYearParser`, numeric month-name ranges into `EnMonthNameRangeParser`, ordinal-word dates/ranges into `EnMonthNameOrdinalParser`, weekday-prefixed month-name dates into `EnMonthNameWeekdayParser`, single weekday references and weekday ranges into `EnWeekdayParser` plus merge refiners, with split English month-name parsers now sharing closest-year calculation through the common helper. `EnWeekdayParser` now uses `createParsingComponentsAtWeekday()` plus shared weekday calculation and dictionary pattern helpers like upstream, including upstream's implied-noon weekday default while preserving weekend/weekday aliases. Relative-date reference merging into `EnMergeRelativeFollowByDateRefiner` and `EnMergeRelativeAfterDateRefiner`, explicit year suffix extraction into `EnExtractYearSuffixRefiner`, and common numeric time parsing into `EnTimeExpressionParser` backed by `AbstractTimeExpressionParser`, including upstream-style rejection of unlikely loose numeric time guesses, casual numeric times such as `at 12.30`, meridiem propagation across compact ranges such as `1pm-3`, and overnight range advancement such as `11pm-3`. English date-time and time-followed-by-date merging preserve inferred component certainty and carry component tags, matching upstream `mergeDateTimeComponent`; date/time merging also recalculates ambiguous timezone abbreviation offsets after the actual date is merged, and weekday-only dates followed by time ranges keep implied endpoint dates so `forwardDate` can move both endpoints together. English refiner ordering now mirrors upstream's relative-date prepass, common timezone/weekday wrapper, late second date-time merge, year suffix extraction, and final date-range merge, while retaining PHP-only time-range merge refiners. English trailing time-range merging now skips a shorter endpoint when a richer parsed endpoint exists at the same position, allowing upstream-style ranges such as `10:30 JST today to 10:30 pst tomorrow` to merge with timezone-aware dates on both endpoints. English date-range merging now implies missing components across endpoints like upstream `AbstractMergeDateRangeRefiner`. English unlikely-format filtering rejects percent-encoded byte fragments that would otherwise be parsed as compact times, matching upstream negative URL cases. Shared English month and weekday names, pattern helpers, and year parsing now live in `EnConstants`, matching upstream `constants.ts` more closely; split English month and slash parsers now compose those pattern helpers instead of rebuilding dictionary alternations locally. The older grouped English time parser has been removed.

### Spanish

Upstream files:

- `ESCasualDateParser`
- `ESCasualTimeParser`
- `ESMonthNameLittleEndianParser`
- `ESTimeExpressionParser`
- `ESTimeUnitWithinFormatParser`
- `ESWeekdayParser`
- `ESMergeDateRangeRefiner`
- `ESMergeDateTimeRefiner`

Current PHP has comparable parser coverage plus the PHP extensions `EsScheduleDateTimeParser`, `EsMonthNameParser`, and `EsTimeUnitAgoFormatParser` under `Locales/Es/Parsers`, and now uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `EsChrono::createStrictConfiguration()` now exposes a source-shaped parser list matching the upstream Spanish parser family, including source-common ISO parsing, and excludes those PHP-only extensions, while the casual configuration preserves the behavior-rich PHP additions. Spanish casual date, casual time, month-name, slash-date, weekday, time-unit-within, and time-unit-ago parsing now live in source-shaped parser classes, while preserving the existing bare `mañana` date behavior, Spanish month-name extras, weekday-prefixed slash dates, schedule-style slash date-times, and the PHP extension for `hace ...` past-relative durations. Spanish weekday parsing now composes the shared word-boundary base and shared weekday calculation helper like upstream, while preserving PHP's concrete start-of-day date output and `forwardDate` behavior. Spanish time-unit-within parsing now composes the shared word-boundary base and shared relative component creation like upstream; the PHP-only `hace ...` parser also composes that base and relative component path. Spanish slash-date parsing composes the common `SlashDateFormatParser` for little-endian numeric dates, and its PHP weekday-prefixed slash extension now reuses the shared Spanish weekday pattern helper. Spanish little-endian month-name parsing now uses the shared closest-year helper like upstream `findYearClosestToRef`; Spanish month-name parsers now share month dictionary/pattern helpers through `EsConstants`, while the grouped month-name extension still keeps local range behavior. Spanish numeric time parsing now composes the PHP `AbstractTimeExpressionParser` through the source-shaped `EsTimeExpressionParser`, matching upstream `ESTimeExpressionParser` structure without the older duplicate fallback parser. Date/time and date-range merging now use source-shaped Spanish refiners inside the upstream common-refiner wrapper, including timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: partial and structurally mixed. Future Spanish work should split grouped parser classes toward the upstream parser family before expanding behavior.

### French

Upstream files:

- `FRCasualDateParser`
- `FRCasualTimeParser`
- `FRMonthNameLittleEndianParser`
- `FRSpecificTimeExpressionParser`
- `FRTimeExpressionParser`
- `FRTimeUnitAgoFormatParser`
- `FRTimeUnitRelativeFormatParser`
- `FRTimeUnitWithinFormatParser`
- `FRWeekdayParser`
- `FRMergeDateRangeRefiner`
- `FRMergeDateTimeRefiner`

Current PHP has most behavior grouped differently, but now uses `Configuration` and `ConfiguredChronoEngine`. `FrChrono::createStrictConfiguration()` now exposes a source-shaped parser list matching the upstream French parser family, including source-common ISO parsing, and excludes PHP-only extensions such as `FrMonthNameParser`, while the casual configuration preserves those additions after the upstream-prepended relative-time, casual-time, and casual-date parsers. French casual date, casual time, slash-date, month-name extensions, and weekday parsing now live in source-shaped parser classes. French weekday parsing now composes the shared word-boundary base, shared weekday calculation helper, and shared French weekday dictionary helper like upstream, while preserving PHP's noon default for standalone weekdays. French slash-date parsing composes the common `SlashDateFormatParser` for little-endian numeric dates, while preserving the PHP weekday-prefixed slash-date extension. French day-month and same-month day-range parsing now mirrors upstream `FRMonthNameLittleEndianParser` and uses the shared closest-year helper like upstream `findYearClosestToRef`; French month-name parsers now share month dictionary/pattern helpers through `FrConstants`. Additional French month-name behavior such as weekday-prefixed month names, cross-month ranges, repeated month ranges, eras, and inline times lives in `FrMonthNameParser` with local range behavior. French specific numeric time parsing now mirrors upstream `FRSpecificTimeExpressionParser`; common numeric time parsing now composes the PHP `AbstractTimeExpressionParser`, matching upstream `FRTimeExpressionParser` structure, and the older duplicate fallback time parser has been removed. French time-unit within/ago/relative parsers now mirror the upstream parser family with PHP duration helpers in `Locales/Fr/Parsers`; the within, ago, and modifier-relative parsers now compose the shared word-boundary base and shared relative component creation like upstream, and the older unregistered grouped relative-date parser has been removed. Date/time and date-range merging have been moved into source-shaped French refiners, and timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass now follow upstream common-refiner order.

Status: partial. Split French month/time behavior toward upstream parser classes and move timezone extraction methods fully into common refiners before adding broader French coverage.

### German

Upstream files:

- `DECasualDateParser`
- `DECasualTimeParser`
- `DEMonthNameLittleEndianParser`
- `DESpecificTimeExpressionParser`
- `DETimeExpressionParser`
- `DETimeUnitRelativeFormatParser`
- `DETimeUnitWithinFormatParser`
- `DEWeekdayParser`
- `DEMergeDateRangeRefiner`
- `DEMergeDateTimeRefiner`

Current PHP:

- `DeCasualDateParser`
- `DeCasualTimeParser`
- `SlashDateFormatParser(littleEndian: true)`
- `DeMonthNameLittleEndianParser`
- `DeMonthNameParser`
- `DeDashDateParser`
- `DeSpecificTimeExpressionParser`
- `DeTimeExpressionExtensionParser`
- `Parsers\DeTimeExpressionParser`
- `DeWeekdayParser`
- `DeTimeUnitRelativeFormatParser`
- `DeTimeUnitWithinFormatParser`
- `DeMergeDateRangeRefiner`
- `DeMergeDateTimeRefiner`

Current PHP now uses `Configuration` and `ConfiguredChronoEngine`. `DeChrono::createStrictConfiguration()` now exposes a source-shaped parser list matching the upstream German parser family and excludes PHP-only extensions such as `DeMonthNameParser`, `DeDashDateParser`, and `DeTimeExpressionExtensionParser`, while the casual configuration preserves those additions after the upstream-prepended modifier-relative, casual-date, and casual-time parsers. Date range and date/time merging have been moved into source-shaped German refiners and now run in upstream order. German casual date, casual time, weekday, little-endian month-name, month-name extension, and German-specific time now live in source-shaped parser classes; German weekday parsing now uses the shared weekday calculation and German dictionary pattern helpers like upstream while preserving PHP's weekday-range extension and noon default. German little-endian month-name parsing now uses the shared closest-year helper like upstream `findYearClosestToRef`, and German month-name parsers now share month dictionary/pattern helpers through `DeConstants`; the grouped month-name extension still keeps local range behavior. German strict and casual configurations now include the common ISO parser before little-endian slash parsing like upstream, and compose the common `SlashDateFormatParser(littleEndian: true)` for unprefixed numeric slash/dash/dot dates, while `DeDashDateParser` preserves the PHP weekday-prefixed dash/dot extension. German common numeric time parsing now composes the PHP `AbstractTimeExpressionParser`, matching upstream `DETimeExpressionParser` structure. German common timezone offset/abbreviation extraction and the second overlap-removal pass are composed around the locale merge refiners like upstream `includeCommonConfiguration()`. Additional German suffix, timezone, midday, and range behavior now lives in `DeTimeExpressionExtensionParser` rather than a duplicate upstream-named root fallback. Within/casual/modifier-relative time-unit parsing has also been split into source-shaped parser classes; the within and modifier-relative parsers now compose the shared word-boundary base and shared relative component creation like upstream, with existing PHP match trimming preserved for postmodifier forms, and the older unregistered grouped relative-date parser has been removed.

Status: source-shaped parser/refiner coverage now exists for all named upstream German parser/refiner files, with legacy grouped classes kept as fallbacks for behavior not yet fully migrated.

### Italian

Upstream files:

- `ITCasualDateParser`
- `ITCasualTimeParser`
- `ITCasualYearMonthDayParser`
- `ITMonthNameLittleEndianParser`
- `ITMonthNameMiddleEndianParser`
- `ITMonthNameParser`
- `ITRelativeDateFormatParser`
- `ITSlashMonthFormatParser`
- `ITTimeExpressionParser`
- `ITTimeUnitAgoFormatParser`
- `ITTimeUnitCasualRelativeFormatParser`
- `ITTimeUnitLaterFormatParser`
- `ITTimeUnitWithinFormatParser`
- `ITWeekdayParser`
- Italian merge refiners

Current PHP:

- `ItCasualDateParser`
- `ItCasualTimeParser`
- `ItMonthNameParser`
- `ItMonthNameLittleEndianParser`
- `ItMonthNameMiddleEndianParser`
- `ItWeekdayParser`
- `ItCasualYearMonthDayParser`
- `ItConstants`
- `ItSlashMonthFormatParser`
- `ItTimeExpressionParser`
- `ItRelativeDateFormatParser`
- `ItTimeUnitAgoFormatParser`
- `ItTimeUnitCasualRelativeFormatParser`
- `ItTimeUnitLaterFormatParser`
- `ItTimeUnitWithinFormatParser`
- `ItMergeDateRangeRefiner`
- `ItMergeDateTimeRefiner`
- `ItMergeRelativeDateRefiner`
- `ItChrono`

Italian uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `ItChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list with source-common ISO parsing, the common slash-date parser, and strict ago/later parsers, while the casual configuration preserves casual date, casual time, casual-relative, relative-date, and month-only additions in upstream prepend order. Casual date, casual time, weekday, casual year-month-day, slash-month, month-name, and time-expression parsing now live in source-shaped parser classes; casual dates include upstream casual references such as `stasera`, `questa sera`, `dmn`, and `ieri sera`; casual time references now mirror upstream `ITCasualTimeParser`; month-only/month-year parsing now mirrors upstream `ITMonthNameParser`; day-month and same-month day-range parsing now mirrors upstream `ITMonthNameLittleEndianParser`; month-day and same-month month-day-range parsing now mirrors upstream `ITMonthNameMiddleEndianParser`; Italian month-name parsers now use shared year heuristics and closest-year calculation helpers like upstream `findMostLikelyADYear` and `findYearClosestToRef`; weekday parsing now composes the shared word-boundary base and shared weekday calculation like upstream `ITWeekdayParser`/`createParsingComponentsAtWeekday`, while preserving canonical modifier mapping and parser tags for diagnostics; casual year-month-day parsing now mirrors upstream `ITCasualYearMonthDayParser`; numeric month/year parsing now mirrors upstream `ITSlashMonthFormatParser`; default numeric slash-date parsing now composes the shared `SlashDateFormatParser`, matching upstream Italian's middle-endian default; numeric time parsing now composes the PHP `AbstractTimeExpressionParser`, matching upstream `ITTimeExpressionParser` structure for prefixes, meridiem suffixes, and dash-style ranges; Italian time-unit within/ago/later/casual-relative and relative-date parsers now mirror the upstream parser family with PHP duration helpers in `Locales/It/Parsers`; the within, ago, later, and casual-relative parsers now compose the shared word-boundary base like upstream, with prefixless within-match text preserved for PHP compatibility, strict-pattern constructors available for ago/later, and Italian reverse words preserved for `ultimo`/`passato`; date-time, date-range, and relative-date reference merging now use source-shaped Italian refiners inside the upstream common-refiner wrapper, including timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: source-shaped parser/refiner coverage now exists for all named upstream Italian parser/refiner files. Continue Italian by comparing behavior against upstream tests and tightening edge cases.

### Finnish

Upstream files:

- `FICasualDateParser`
- `FICasualTimeParser`
- `FIMonthNameLittleEndianParser`
- `FITimeExpressionParser`
- `FITimeUnitAgoFormatParser`
- `FITimeUnitCasualRelativeFormatParser`
- `FITimeUnitWithinFormatParser`
- `FIWeekdayParser`
- `FIMergeDateRangeRefiner`
- `FIMergeDateTimeRefiner`

Current PHP:

- `IsoFormatParser`
- `SlashDateFormatParser`
- `FiCasualDateParser`
- `FiCasualTimeParser`
- `FiMonthNameLittleEndianParser`
- `FiWeekdayParser`
- `FiTimeExpressionParser`
- `FiTimeUnitAgoFormatParser`
- `FiTimeUnitCasualRelativeFormatParser`
- `FiTimeUnitWithinFormatParser`
- `FiMergeDateRangeRefiner`
- `FiMergeDateTimeRefiner`
- `FiConstants`
- `FiChrono`

Finnish uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `FiChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list, while the casual configuration preserves casual date, casual time, and casual-relative parser additions in upstream prepend order. Common ISO and little-endian slash parsing are wired into the Finnish configuration. Casual date, casual time, month-name little-endian, weekday, and time-expression parsing now live in source-shaped parser classes. Casual date and casual time parsing now mirror the upstream parser boundaries for `nyt`, `tänään`, `huomenna`, `ylihuomenna`, `eilen`, `toissapäivänä`, `viime yönä`, and Finnish casual time words. Finnish day-month and same-month day-range parsing now mirrors upstream `FIMonthNameLittleEndianParser` and uses the shared closest-year helper like upstream `findYearClosestToRef`. Finnish weekday parsing now composes the shared word-boundary base and shared weekday calculation like upstream `FIWeekdayParser`/`createParsingComponentsAtWeekday`, while preserving parser tags for diagnostics. Finnish numeric time parsing now composes the PHP `AbstractTimeExpressionParser`, matching upstream `FITimeExpressionParser` structure for `klo`/`kello` prefixes, compact times, meridiem values, and dash-style ranges such as `klo 10:00-12:00`. Finnish time-unit within/ago/casual-relative parsers now mirror the upstream parser family with PHP duration helpers in `Locales/Fi/Parsers`, including compact signed units such as `+15min` and `-3vuotta`; all three now compose the shared word-boundary base like upstream. Finnish date-range and date-time refiners now run in upstream order, with range merging before date-time merging, and the surrounding common refiners now include timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: source-shaped parser/refiner coverage now exists for all named upstream Finnish parser/refiner files plus common ISO/slash parser composition. Continue Finnish by comparing behavior against upstream tests and tightening edge cases.

### Dutch

Upstream files:

- `NLCasualDateParser`
- `NLCasualDateTimeParser`
- `NLCasualTimeParser`
- `NLCasualYearMonthDayParser`
- `NLMonthNameMiddleEndianParser`
- `NLMonthNameParser`
- `NLRelativeDateFormatParser`
- `NLSlashMonthFormatParser`
- `NLTimeExpressionParser`
- `NLTimeUnitAgoFormatParser`
- `NLTimeUnitCasualRelativeFormatParser`
- `NLTimeUnitLaterFormatParser`
- `NLTimeUnitWithinFormatParser`
- `NLWeekdayParser`
- Dutch merge refiners

Current PHP:

- `SlashDateFormatParser`
- `NlCasualDateParser`
- `NlCasualDateTimeParser`
- `NlCasualTimeParser`
- `NlCasualYearMonthDayParser`
- `NlConstants`
- `NlMonthNameMiddleEndianParser`
- `NlMonthNameParser`
- `NlRelativeDateFormatParser`
- `NlSlashMonthFormatParser`
- `NlTimeExpressionParser`
- `NlTimeUnitAgoFormatParser`
- `NlTimeUnitCasualRelativeFormatParser`
- `NlTimeUnitLaterFormatParser`
- `NlTimeUnitWithinFormatParser`
- `NlWeekdayParser`
- `NlMergeDateRangeRefiner`
- `NlMergeDateTimeRefiner`
- `NlChrono`

Dutch uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `NlChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list with source-common ISO parsing and strict ago/later parsers, while the casual configuration preserves casual date/time, compound casual date-time, relative-date, and casual-relative additions in upstream prepend order. Casual date, casual time, compound casual date-time, month-name, month-name middle-endian, weekday, casual year-month-day, slash-month, and numeric time parsing now live in source-shaped parser classes and mirror the upstream parser boundaries for `nu`, `vandaag`, `morgen`, `morgend`, `gisteren`, Dutch casual time words, and compound words such as `morgenochtend` and `vanavond`. Compound casual date-time parsing preserves the date certainty when assigning the time, matching upstream behavior for merged ranges such as `vandaag tot morgennamiddag`. Little-endian numeric slash parsing is now wired through the shared common parser. Dutch month-only/month-year parsing now mirrors upstream `NLMonthNameParser`; day-month and same-month day-range parsing now mirrors upstream `NLMonthNameMiddleEndianParser`; both month-name parsers now use the shared closest-year helper like upstream `findYearClosestToRef`; weekday parsing now composes the shared word-boundary base and shared weekday calculation like upstream `NLWeekdayParser`/`createParsingComponentsAtWeekday`, while preserving Dutch noon defaults and parser tags; numeric month/year parsing now mirrors upstream `NLSlashMonthFormatParser`; casual year-month-day parsing now mirrors upstream `NLCasualYearMonthDayParser`; numeric time parsing now composes the PHP `AbstractTimeExpressionParser`, matching upstream `NLTimeExpressionParser` structure for `om` prefixes, `uur` suffixes, Dutch range separators, and year-like rejection. Dutch time-unit within/ago/later/casual-relative and relative-date parsers now mirror the upstream parser family with PHP duration helpers in `Locales/Nl/Parsers`; the within, ago, later, and casual-relative parsers now compose the shared word-boundary base like upstream, with strict-pattern constructors available for ago/later. Date-time and date-range merging now use source-shaped Dutch refiners inside the upstream common-refiner wrapper, including timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: source-shaped parser/refiner coverage now exists for all named upstream Dutch parser/refiner files. Dutch relative time-unit coverage now includes upstream strict later expressions, within-duration certainty by unit granularity, and default-English negative cases from the upstream Dutch ago tests. Continue Dutch by comparing behavior against upstream tests and tightening edge cases.

### Swedish

Upstream files:

- `SVCasualDateParser`
- `SVMonthNameLittleEndianParser`
- `SVTimeUnitCasualRelativeFormatParser`
- `SVWeekdayParser`

Current PHP:

- `IsoFormatParser`
- `SlashDateFormatParser`
- `SvCasualDateParser`
- `SvMonthNameLittleEndianParser`
- `SvTimeUnitCasualRelativeFormatParser`
- `SvWeekdayParser`

Swedish uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `SvChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list, including upstream's strict inclusion of `SvTimeUnitCasualRelativeFormatParser`, while the casual configuration adds casual date parsing. Common ISO and little-endian slash parsing are wired into the Swedish configuration, and common timezone offset/abbreviation extraction plus the second overlap-removal pass are composed like upstream `includeCommonConfiguration()`. Casual date, month-name little-endian, weekday, and casual relative time-unit parsing now live in source-shaped parser classes and mirror the upstream parser boundaries for `nu`, `idag`, `imorgon`, `imorn`, `övermorgon`, `igår`, `förrgår`, Swedish casual time suffixes including `vid midnatt`, Swedish day-month forms and same-month ranges, weekday modifiers, `nästa`/`förra` relative time-unit phrases, number-word relative units, compact signed units such as `-2tim5min`, and bare-duration negative cases. Swedish day-month parsing now uses the shared closest-year helper like upstream `findYearClosestToRef`. Swedish casual dates now compose the shared word-boundary base and shared casual-reference helpers, preserving the reference clock as implied time like upstream. Swedish weekday parsing now composes the shared word-boundary base and shared weekday calculation like upstream `SVWeekdayParser`/`createParsingComponentsAtWeekday`, while preserving parser tags for diagnostics. Swedish casual relative time units now compose the shared word-boundary base and shared relative component creation instead of custom certainty logic.

Status: initial source-shaped parser coverage exists for all named upstream Swedish parser files plus common ISO/slash parser composition. Swedish month-name coverage now includes upstream single-expression, abbreviated month, same-month range, and impossible-date cases. Swedish weekday coverage now includes upstream bare/prefixed weekdays, next/last modifiers, and weekday variation cases. Swedish casual-relative coverage now includes upstream positive/negative modifier phrases, number-word units, compact signed units, and explicit signed forms such as `+15 minuter`, `+1min`, and `-3år`. Continue Swedish by comparing behavior against upstream tests and tightening edge cases.

### Portuguese

Upstream files:

- `PTCasualDateParser`
- `PTCasualTimeParser`
- `PTMonthNameLittleEndianParser`
- `PTTimeExpressionParser`
- `PTWeekdayParser`
- `PTMergeDateRangeRefiner`
- `PTMergeDateTimeRefiner`

Current PHP:

- `SlashDateFormatParser`
- `PtCasualDateParser`
- `PtCasualTimeParser`
- `PtMonthNameLittleEndianParser`
- `PtTimeExpressionParser`
- `PtWeekdayParser`
- `PtMergeDateRangeRefiner`
- `PtMergeDateTimeRefiner`

Portuguese uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `PtChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list with source-common ISO parsing, while the casual configuration adds casual date and casual time parsing. Little-endian slash parsing is wired through the shared common parser, including upstream weekday-prefixed slash date/time cases such as `Terça-feira 9/2/2016` and compact schedule ranges. Casual date, casual time, month-name little-endian, numeric time, and weekday parsing now live in source-shaped parser classes. Portuguese weekday parsing now composes the shared word-boundary base and shared weekday calculation like upstream `PTWeekdayParser`/`createParsingComponentsAtWeekday`, while preserving PHP's existing start-of-day weekday default and parser tags. Portuguese casual date references now preserve the reference time like upstream `today`/`tomorrow`/`yesterday` helpers, while keeping only date components certain. Portuguese casual time references now merge with following explicit times like upstream, so `esta noite às 8` uses the evening meridiem hint and resolves to 20:00. Portuguese month-name parsing now accepts BCE era suffixes such as `234 AC`, matching upstream `parseYear()`, and uses the shared closest-year helper like upstream `findYearClosestToRef`. Portuguese month-name ranges cover same-month connectors including `até`, and cross-month date-range merging now implies missing components so an explicit end year applies to both endpoints like upstream. Portuguese date-time and date-range merging now use source-shaped Portuguese refiners, and common timezone offset/abbreviation extraction plus the second overlap-removal pass are composed like upstream `includeCommonConfiguration()`.

Status: initial source-shaped parser/refiner coverage exists for all named upstream Portuguese parser/refiner files plus common slash parser composition. Portuguese time-expression coverage now includes upstream single/range/date-time/meridiem-certainty cases and the random date-time text-preservation examples from `pt_time_exp.test.ts`, including weekday-prefixed compact times and compact ranges. Continue Portuguese by comparing behavior against upstream tests and tightening edge cases.

### Japanese

Upstream files:

- `JPCasualDateParser`
- `JPSlashDateFormatParser`
- `JPStandardParser`
- `JPTimeExpressionParser`
- `JPWeekdayParser`
- `JPWeekdayWithParenthesesParser`
- `JPMergeDateRangeRefiner`
- `JPMergeDateTimeRefiner`
- `JPMergeWeekdayComponentRefiner`

Current PHP:

- `JaCasualDateParser`
- `JaSlashDateFormatParser`
- `JaStandardParser`
- `JaTimeExpressionParser`
- `JaWeekdayParser`
- `JaWeekdayWithParenthesesParser`
- `JaMergeDateRangeRefiner`
- `JaMergeDateTimeRefiner`
- `JaMergeWeekdayComponentRefiner`

Japanese uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `JaChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list with source-common ISO parsing, while the casual configuration preserves casual date parsing. The Japanese configuration now includes upstream common timezone offset/abbreviation refiners and keeps the Japanese-specific weekday merge refiner while excluding the generic common weekday merge refiner, matching upstream's Japanese `includeCommonConfiguration()` adjustment. Casual date, standard Japanese date, big-endian slash date, weekday, parenthesized weekday, and Japanese numeric time parsing now live in source-shaped parser classes. Japanese casual date coverage now includes upstream kana and alternate aliases such as `きょう`, `本日`, `ほんじつ`, `きのう`, `あした`, `今夕`, and `けさ`. Standard date coverage includes Japanese eras, `令和元年`, and current-year markers such as `本年`; big-endian slash dates include upstream slash ranges such as `2013/12/26~2014/1/7`. Japanese slash dates now accept a following `の` particle so slash date-times such as `12/9の16:00` merge like upstream. Japanese bare weekday parsing now uses the shared weekday calculation helper like upstream, choosing the closest weekday unless the text has an explicit modifier such as `次の` while preserving PHP's midnight default; weekday-only date ranges use upstream-style range ordering so ranges such as `土曜日～月曜日` stay chronological. Japanese time parsing keeps byte offsets aligned when trimming leading whitespace so multibyte date-time merges preserve complete result text, supports Japanese numeral ranges such as `本日午前八時十分から午後11時32分`, and rejects invalid range endpoints instead of returning a partial start time. Japanese date-time, date-range, and date-followed-by-weekday merging now use source-shaped Japanese refiners.

Status: initial source-shaped parser/refiner coverage exists for all named upstream Japanese parser/refiner files. Continue Japanese by comparing behavior against upstream tests and tightening edge cases.

### Vietnamese

Upstream files:

- `VICasualDateParser`
- `VICasualTimeParser`
- `VIMonthYearParser`
- `VIStandardParser`
- `VITimeExpressionParser`
- `VITimeUnitAgoFormatParser`
- `VITimeUnitCasualRelativeFormatParser`
- `VITimeUnitLaterFormatParser`
- `VITimeUnitWithinFormatParser`
- `VIWeekdayParser`
- `VIYearParser`
- `VIMergeDateRangeRefiner`
- `VIMergeDateTimeRefiner`
- `VIMergeWeekdayComponentRefiner`

Vietnamese uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `ViChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list with strict relative-unit parsers, while the casual configuration preserves casual date, casual time, and casual-relative additions. Common ISO and little-endian slash parsing are wired into the Vietnamese configuration, including punctuation-terminated slash dates such as `30/04/1975.`. Casual date/time, standard date, month-year, year, weekday, numeric time, and time-unit relative parsing now live in source-shaped parser classes. Vietnamese casual date references preserve the reference clock as implied time while only marking year/month/day certain, then delete implied meridiem like upstream casual references so later explicit day-period/time merges can override it. Vietnamese casual time and time-unit relative behavior now has upstream-backed coverage for dawn/midnight/day-period words, date + casual-time merging, bare relative units, and `trong`/`trong vòng` duration phrases. Vietnamese time-unit within/ago/later/casual-relative parsers now compose the shared word-boundary base and shared relative component creation like upstream, while preserving parser tags for diagnostics. Vietnamese weekday parsing now composes the shared word-boundary base and shared weekday calculation like upstream `VIWeekdayParser`/`createParsingComponentsAtWeekday`, preserving the reference clock and leaving `forwardDate` behavior to the shared refiner. Vietnamese numeric time parsing now follows upstream `VITimeExpressionParser` directly, requiring `giờ` or `HH:MM` forms instead of accepting loose numeric ranges or compact meridiem fragments. Vietnamese standard and year parsing accept `TCN` BCE years like upstream `parseYear()`, use the shared closest-year helper like upstream `findYearClosestToRef`, and invalid full date shapes no longer fall through to standalone month/year results. Vietnamese date-range merging now implies missing endpoint components, supports month-only endpoints such as `tháng 3 tới tháng 5 năm 1975`, and applies an explicit end year to both endpoints like upstream `AbstractMergeDateRangeRefiner`. Vietnamese date-time, date-range, and weekday merge behavior now uses source-shaped refiners inside the upstream common-refiner wrapper, including timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: initial source-shaped parser/refiner coverage exists for all named upstream Vietnamese parser/refiner files plus common ISO/slash parser composition. Continue Vietnamese by comparing behavior against upstream tests and tightening edge cases.

### Chinese

Upstream files:

- Simplified: `ZHHansCasualDateParser`, `ZHHansDateParser`, `ZHHansDeadlineFormatParser`, `ZHHansRelationWeekdayParser`, `ZHHansTimeExpressionParser`, `ZHHansWeekdayParser`, `ZHHansMergeDateRangeRefiner`, `ZHHansMergeDateTimeRefiner`
- Traditional: `ZHHantCasualDateParser`, `ZHHantDateParser`, `ZHHantDeadlineFormatParser`, `ZHHantRelationWeekdayParser`, `ZHHantTimeExpressionParser`, `ZHHantWeekdayParser`, `ZHHantMergeDateRangeRefiner`, `ZHHantMergeDateTimeRefiner`

Chinese uses shared `ZhChrono`, `ZhHansChrono`, and `ZhHantChrono` configurations. `ZhChrono::createStrictConfiguration()`, `ZhHansChrono::createStrictConfiguration()`, and `ZhHantChrono::createStrictConfiguration()` now expose upstream-shaped strict parser lists, while the casual configurations preserve casual date parsing before source-common ISO parsing like upstream `unshift()`. Source-shaped Hans/Hant parser classes are present for casual dates, dates, deadlines, relation weekdays, weekdays, and time expressions, with shared PHP base classes for the parallel behavior. Chinese weekday parsing now delegates closest and forward-only weekday distances to the shared weekday calculation helper while preserving upstream's relation-week semantics for prefixes such as `上`, `下`, `这`, and `這`; weekday-only and relation-weekday components now inherit the upstream component default of implied noon. Chinese configurations now include the common ISO parser, generic weekday merge refiner, abbreviation timezone extraction, second overlap-removal pass, forward-date refiner, and unlikely-format filter like upstream `includeCommonConfiguration`, while still omitting numeric timezone extraction as upstream does. Chinese time expressions now consume relative-day/day-period prefixes such as `明天早上8点` and `明天早上8點` in a single result, matching upstream Hans/Hant time parsers. Chinese date-range merging now implies missing endpoint components, so an explicit end year applies to both endpoints like upstream `AbstractMergeDateRangeRefiner`. Date-time and date-range merging use source-shaped Chinese refiners.

Status: initial source-shaped parser/refiner coverage exists for all named upstream Chinese parser/refiner files. Continue Chinese by comparing behavior against upstream tests and tightening edge cases.

### Russian

Upstream files:

- `RUCasualDateParser`
- `RUCasualTimeParser`
- `RUMonthNameLittleEndianParser`
- `RUMonthNameParser`
- `RURelativeDateFormatParser`
- `RUTimeExpressionParser`
- `RUTimeUnitAgoFormatParser`
- `RUTimeUnitCasualRelativeFormatParser`
- `RUTimeUnitWithinFormatParser`
- `RUWeekdayParser`
- `RUMergeDateRangeRefiner`
- `RUMergeDateTimeRefiner`

Current PHP:

- `SlashDateFormatParser`
- `RuCasualDateParser`
- `RuCasualTimeParser`
- `RuMonthNameLittleEndianParser`
- `RuMonthNameParser`
- `RuRelativeDateFormatParser`
- `RuTimeExpressionParser`
- `RuTimeUnitAgoFormatParser`
- `RuTimeUnitCasualRelativeFormatParser`
- `RuTimeUnitWithinFormatParser`
- `RuWeekdayParser`
- `RuMergeDateRangeRefiner`
- `RuMergeDateTimeRefiner`
- `RuConstants`
- `RuChrono`

Russian uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `RuChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list, while the casual configuration preserves casual date/time, month-only, relative-date, and casual-relative additions in upstream prepend order. Common ISO and little-endian slash parsing are wired into the Russian configuration. Casual date/time, month-name, relative date, time-unit relative, weekday, and numeric time parsing now live in source-shaped parser classes. Russian casual date parsing now preserves the reference clock for `сегодня`, `завтра`, `послезавтра`, `послепослезавтра`, `вчера`, `позавчера`, and `позапозавчера` like upstream, while `сейчас` marks the full reference instant certain. Russian weekday parsing now composes the shared word-boundary base and shared weekday calculation helper like upstream, while preserving PHP's start-of-day weekday default and `forwardDate` behavior. Russian month-name parsers now use the shared closest-year helper like upstream `findYearClosestToRef`, while keeping Russian-specific year text parsing in `RuConstants`. Russian time-unit within/ago/casual-relative parsers now compose the shared word-boundary base and shared relative component creation like upstream, including `forwardDate` bare-duration matching for the within parser. Russian relative date parsing now preserves the reference time, shifts previous/next week by one week instead of normalizing to week start, keeps previous/next year on the reference month/day, and only normalizes same-year expressions to January 1, matching upstream `ru_relative.test.ts`. Russian date-range merging now implies missing endpoint components, so an explicit end year applies to both endpoints like upstream `AbstractMergeDateRangeRefiner`. Russian date-time and date-range merging now use source-shaped Russian refiners inside the upstream common-refiner wrapper, including timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: initial source-shaped parser/refiner coverage exists for all named upstream Russian parser/refiner files plus common slash parser composition. Russian time-expression coverage now includes upstream single-time, time-range, casual number time, meridiem range, and strict negative cases from `ru_time_exp.test.ts`; strict Russian time parsing rejects loose prefixed numeric times and bare numeric ranges like upstream `AbstractTimeExpressionParser`. Continue Russian by comparing behavior against upstream tests and tightening edge cases.

### Ukrainian

Upstream files:

- `UKCasualDateParser`
- `UKCasualTimeParser`
- `UKMonthNameLittleEndianParser`
- `UKMonthNameParser`
- `UKRelativeDateFormatParser`
- `UKTimeExpressionParser`
- `UKTimeUnitAgoFormatParser`
- `UKTimeUnitCasualRelativeFormatParser`
- `UKTimeUnitWithinFormatParser`
- `UKWeekdayParser`
- `UKMergeDateRangeRefiner`
- `UKMergeDateTimeRefiner`

Current PHP:

- `IsoFormatParser`
- `SlashDateFormatParser`
- `UkCasualDateParser`
- `UkCasualTimeParser`
- `UkMonthNameLittleEndianParser`
- `UkMonthNameParser`
- `UkRelativeDateFormatParser`
- `UkTimeExpressionParser`
- `UkTimeUnitAgoFormatParser`
- `UkTimeUnitCasualRelativeFormatParser`
- `UkTimeUnitWithinFormatParser`
- `UkWeekdayParser`
- `UkMergeDateRangeRefiner`
- `UkMergeDateTimeRefiner`
- `UkConstants`
- `UkChrono`

Ukrainian uses the shared `Configuration` and `ConfiguredChronoEngine` runner. `UkChrono::createStrictConfiguration()` now exposes the upstream-shaped strict parser list, while the casual configuration preserves casual date/time, month-only, relative-date, and casual-relative additions in upstream prepend order. Common ISO and little-endian slash parsing are wired into the Ukrainian configuration. Casual date/time, month-name, relative date, time-unit relative, weekday, and numeric time parsing now live in source-shaped parser classes. Ukrainian casual date parsing now preserves the reference time like upstream casual references, while keeping only date components certain so explicit time expressions can still merge over it. Ukrainian `зараз` now marks the full reference instant certain, including milliseconds and timezone offset. Ukrainian casual `минулої ночі` now follows upstream `lastNight()` behavior by using the previous date only when the reference time is before 06:00. Ukrainian weekday parsing now composes the shared word-boundary base and shared weekday calculation helper like upstream, while preserving PHP's start-of-day weekday default and `forwardDate` behavior. Ukrainian relative date parsing now preserves the reference time and only normalizes "this" week/month/year expressions like upstream, while next/last unit expressions shift the reference date directly. Ukrainian casual relative duration parsing preserves fractional units such as `півгодини` and uses upstream's empty-number default of one unit for bare durations such as `протягом хвилини`. Ukrainian time-unit within/ago/casual-relative parsers now compose the shared word-boundary base and shared relative component creation like upstream, including `forwardDate` bare-duration matching for the within parser. Ukrainian time-expression parsing applies Ukrainian meridiem suffixes to both endpoints of time ranges, so evening ranges like `із 10 до 11 вечора` resolve to 22:00-23:00. Ukrainian date-range merging now implies missing endpoint components, so an explicit end year applies to both endpoints like upstream `AbstractMergeDateRangeRefiner`. Ukrainian date-time merging now follows upstream `mergeDateTimeResult` more closely for date ranges followed by a single time, applying the time to both range endpoints. Ukrainian month-name year suffix parsing now follows upstream `YEAR_PATTERN`, preserving two-digit `50-99` years while leaving comma-prefixed numeric times available for date-time merging, and month-name parsers use the shared closest-year helper like upstream `findYearClosestToRef`. Ukrainian little-endian month-name results keep trimmed text byte-aligned with parser indexes so merged multibyte ranges preserve their full source text. Ukrainian date-time and date-range merging now use source-shaped Ukrainian refiners inside the upstream common-refiner wrapper, including timezone offset extraction, timezone abbreviation extraction, and the second overlap-removal pass.

Status: initial source-shaped parser/refiner coverage exists for all named upstream Ukrainian parser/refiner files plus common ISO/slash parser composition. Ukrainian time-expression coverage now includes upstream single-time, time-range, casual number time, meridiem range, and strict negative cases from `uk_time_exp.test.ts`; strict Ukrainian time parsing rejects loose prefixed numeric times and bare numeric ranges like upstream `AbstractTimeExpressionParser`. Continue Ukrainian by comparing behavior against upstream tests and tightening edge cases.

## Intentional PHP Adaptations

- Public parsing returns Carbon instances through `ParsedComponents::date()`.
- Public APIs use `Chrono::englishLike()` static constructors instead of JS module exports.
- Parser/refiner objects should remain framework-agnostic and avoid Laravel container dependencies.

## Next Alignment Steps

1. Move any remaining adjacent-result merge refiners onto `MergingRefiner`.
2. Split grouped parser classes into upstream-shaped parser classes.
3. Compare each locale against upstream tests and tighten edge cases.
4. Split grouped parser classes into upstream-shaped parser classes, starting with English month-name and relative-date parsers.
5. For each locale, port parser/refiner classes in upstream configuration order and attach Pest tests from the matching upstream file.
