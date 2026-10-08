<h1 align="center">Chrono</h1>

<p align="center">Parse natural language dates into immutable Carbon instances.</p>

<p align="center">
    <a href="https://github.com/DirectoryTree/Chrono/actions/workflows/run-tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/DirectoryTree/Chrono/run-tests.yml?branch=master&amp;style=flat-square" alt="Tests"></a>
    <a href="https://packagist.org/packages/directorytree/chrono"><img src="https://img.shields.io/packagist/dt/directorytree/chrono.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/directorytree/chrono"><img src="https://img.shields.io/packagist/v/directorytree/chrono.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/directorytree/chrono"><img src="https://img.shields.io/packagist/l/directorytree/chrono.svg?style=flat-square" alt="License"></a>
</p>

<p align="center">
    <a href="#installation">Installation</a>
    <span> · </span>
    <a href="#usage">Usage</a>
    <span> · </span>
    <a href="#locales">Locales</a>
    <span> · </span>
    <a href="#testing">Testing</a>
</p>

---

Chrono is a PHP natural language date parser inspired by [`wanasit/chrono`](https://github.com/wanasit/chrono).

It parses text like `tomorrow at 4pm`, `next Friday`, `10 August - 12 September`, and `mañana` into immutable Carbon instances.

## Installation

Install Chrono with Composer:

```bash
composer require directorytree/chrono
```

## Usage

Parse the first date from a string:

```php
use DirectoryTree\Chrono\Chrono;

$date = Chrono::date('tomorrow at 4pm', '2026-06-23 09:00');

$date->toDateTimeString(); // 2026-06-24 16:00:00
```

Parse all dates from a string:

```php
$dates = Chrono::dates('Review from Monday to Friday', '2026-06-23');

$dates[0]->toDateString(); // 2026-06-22
```

Parse detailed results from a string:

```php
$results = Chrono::parse('Review from Monday to Friday', '2026-06-23');

$results[0]->text; // Monday to Friday

$results[0]->start->date()->toDateString(); // 2026-06-22
$results[0]->end?->date()->toDateString(); // 2026-06-26
```

## Parser Instances

Create a parser when you want to reuse a locale or configuration:

```php
$chrono = Chrono::english();

$chrono->date('next Friday', '2026-06-23');
$chrono->parse('today, tomorrow, and Friday', '2026-06-23');
```

Chrono also includes casual and strict English variants:

```php
Chrono::casual()->date('tomorrow');

Chrono::strict()->parse('tomorrow'); // []
```

## Locales

Chrono includes parser factories for supported locales:

```php
Chrono::spanish()->date('mañana');
Chrono::french()->date('demain');
Chrono::german()->date('morgen');
Chrono::italian()->date('domani');
Chrono::russian()->date('завтра');
Chrono::ukrainian()->date('завтра');
Chrono::japanese()->date('明日');
Chrono::chinese()->date('明天');
```

Locale aliases are also available:

```php
Chrono::es();
Chrono::fr();
Chrono::de();
Chrono::it();
Chrono::nl();
Chrono::fi();
Chrono::sv();
Chrono::pt();
Chrono::ru();
Chrono::uk();
Chrono::ja();
Chrono::vi();
Chrono::zh();
```

Use `Chrono::british()`, `Chrono::gb()`, or `Chrono::enGb()` for British English slash dates:

```php
Chrono::gb()->date('6/10/2018', '2018-01-01');
```

## Reference Dates

Relative expressions are resolved against the reference date you provide:

```php
Chrono::date('next week', '2026-06-23');
```

You may pass a `CarbonInterface`, a date string, or a reference array with a timezone:

```php
Chrono::date('tomorrow at 9am', [
    'instant' => '2026-06-23 12:00:00',
    'timezone' => 'America/Toronto',
]);
```

## Results

`parse()` returns parsed result objects with the original text, start components, and optional end components:

```php
$result = Chrono::parse('10am - 11am tomorrow', '2026-06-23')[0];

$result->text; // 10am - 11am tomorrow

$result->start->date()->toDateTimeString(); // 2026-06-24 10:00:00
$result->end?->date()->toDateTimeString(); // 2026-06-24 11:00:00
```

Parsed components expose known and implied date parts for cases where you need more detail than the final Carbon instance:

```php
$result->start->isCertain('day');
$result->start->get('hour');
$result->start->date();
```

## Customization

Chrono follows a parser and refiner pipeline. Parsers extract possible dates from text, and refiners clean up, merge, filter, or adjust those results.

Add custom parsers or refiners to an existing parser instance:

```php
$chrono = Chrono::casual()
    ->withParser(new CompanyHolidayParser)
    ->withRefiner(new BusinessHoursRefiner);
```

Remove a parser or refiner by class name:

```php
$chrono = $chrono
    ->withoutParser(CompanyHolidayParser::class)
    ->withoutRefiner(BusinessHoursRefiner::class);
```

Parser instances are immutable. Each customization method returns a new instance:

```php
$default = Chrono::english();

$custom = $default->withParser(new CompanyHolidayParser);
```

## Testing

Run the test suite:

```bash
./vendor/bin/pest
```

Run the style checker:

```bash
./vendor/bin/pint --test
```

## License

Chrono is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
