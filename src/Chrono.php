<?php

namespace Chrono;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Chrono\Locales\De\DeChrono;
use Chrono\Locales\En\EnChrono;
use Chrono\Locales\Es\EsChrono;
use Chrono\Locales\Fi\FiChrono;
use Chrono\Locales\Fr\FrChrono;
use Chrono\Locales\It\ItChrono;
use Chrono\Locales\Ja\JaChrono;
use Chrono\Locales\Nl\NlChrono;
use Chrono\Locales\Pt\PtChrono;
use Chrono\Locales\Ru\RuChrono;
use Chrono\Locales\Sv\SvChrono;
use Chrono\Locales\Uk\UkChrono;
use Chrono\Locales\Vi\ViChrono;
use Chrono\Locales\Zh\ZhChrono;
use Chrono\Locales\Zh\ZhHansChrono;
use Chrono\Locales\Zh\ZhHantChrono;

class Chrono
{
    /**
     * Create a Chrono facade around the given parsing engine.
     *
     * @param  array<string, mixed>  $defaultOptions
     */
    public function __construct(
        /**
         * The parser engine implementation.
         */
        protected readonly ChronoEngine $engine = new EnChrono(),

        /**
         * The default options applied to each parse call.
         *
         * @var array<string, mixed>
         */
        protected readonly array $defaultOptions = [],
    ) {}

    /**
     * Create the default casual English parser.
     */
    public static function casual(): self
    {
        return new self(EnChrono::casual());
    }

    /**
     * Create the default strict English parser.
     */
    public static function strict(): self
    {
        return new self(EnChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a British English parser.
     */
    public static function british(): self
    {
        return new self(EnChrono::british());
    }

    /**
     * Create a British English parser.
     */
    public static function gb(): self
    {
        return self::british();
    }

    /**
     * Create a British English parser.
     */
    public static function enGb(): self
    {
        return self::british();
    }

    /**
     * Create a Spanish parser.
     */
    public static function spanish(): self
    {
        return new self(new EsChrono());
    }

    /**
     * Create a strict Spanish parser.
     */
    public static function strictSpanish(): self
    {
        return new self(EsChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Spanish parser.
     */
    public static function es(): self
    {
        return self::spanish();
    }

    /**
     * Create a German parser.
     */
    public static function german(): self
    {
        return new self(new DeChrono());
    }

    /**
     * Create a strict German parser.
     */
    public static function strictGerman(): self
    {
        return new self(DeChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a German parser.
     */
    public static function de(): self
    {
        return self::german();
    }

    /**
     * Create a French parser.
     */
    public static function french(): self
    {
        return new self(new FrChrono());
    }

    /**
     * Create a strict French parser.
     */
    public static function strictFrench(): self
    {
        return new self(FrChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a French parser.
     */
    public static function fr(): self
    {
        return self::french();
    }

    /**
     * Create an Italian parser.
     */
    public static function italian(): self
    {
        return new self(new ItChrono());
    }

    /**
     * Create a strict Italian parser.
     */
    public static function strictItalian(): self
    {
        return new self(ItChrono::strict(), ['strict' => true]);
    }

    /**
     * Create an Italian parser.
     */
    public static function it(): self
    {
        return self::italian();
    }

    /**
     * Create a Finnish parser.
     */
    public static function finnish(): self
    {
        return new self(new FiChrono());
    }

    /**
     * Create a strict Finnish parser.
     */
    public static function strictFinnish(): self
    {
        return new self(FiChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Finnish parser.
     */
    public static function fi(): self
    {
        return self::finnish();
    }

    /**
     * Create a Dutch parser.
     */
    public static function dutch(): self
    {
        return new self(new NlChrono());
    }

    /**
     * Create a strict Dutch parser.
     */
    public static function strictDutch(): self
    {
        return new self(NlChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Dutch parser.
     */
    public static function nl(): self
    {
        return self::dutch();
    }

    /**
     * Create a Swedish parser.
     */
    public static function swedish(): self
    {
        return new self(new SvChrono());
    }

    /**
     * Create a strict Swedish parser.
     */
    public static function strictSwedish(): self
    {
        return new self(SvChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Swedish parser.
     */
    public static function sv(): self
    {
        return self::swedish();
    }

    /**
     * Create a Ukrainian parser.
     */
    public static function ukrainian(): self
    {
        return new self(new UkChrono());
    }

    /**
     * Create a strict Ukrainian parser.
     */
    public static function strictUkrainian(): self
    {
        return new self(UkChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Ukrainian parser.
     */
    public static function uk(): self
    {
        return self::ukrainian();
    }

    /**
     * Create a Portuguese parser.
     */
    public static function portuguese(): self
    {
        return new self(new PtChrono());
    }

    /**
     * Create a strict Portuguese parser.
     */
    public static function strictPortuguese(): self
    {
        return new self(PtChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Portuguese parser.
     */
    public static function pt(): self
    {
        return self::portuguese();
    }

    /**
     * Create a Russian parser.
     */
    public static function russian(): self
    {
        return new self(new RuChrono());
    }

    /**
     * Create a strict Russian parser.
     */
    public static function strictRussian(): self
    {
        return new self(RuChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Russian parser.
     */
    public static function ru(): self
    {
        return self::russian();
    }

    /**
     * Create a Japanese parser.
     */
    public static function japanese(): self
    {
        return new self(new JaChrono());
    }

    /**
     * Create a strict Japanese parser.
     */
    public static function strictJapanese(): self
    {
        return new self(JaChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Japanese parser.
     */
    public static function ja(): self
    {
        return self::japanese();
    }

    /**
     * Create a Vietnamese parser.
     */
    public static function vietnamese(): self
    {
        return new self(new ViChrono());
    }

    /**
     * Create a strict Vietnamese parser.
     */
    public static function strictVietnamese(): self
    {
        return new self(ViChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a Vietnamese parser.
     */
    public static function vi(): self
    {
        return self::vietnamese();
    }

    /**
     * Create a generic Chinese parser.
     */
    public static function chinese(): self
    {
        return new self(new ZhChrono());
    }

    /**
     * Create a strict Chinese parser.
     */
    public static function strictChinese(): self
    {
        return new self(ZhChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a generic Chinese parser.
     */
    public static function zh(): self
    {
        return self::chinese();
    }

    /**
     * Create a simplified Chinese parser.
     */
    public static function zhHans(): self
    {
        return new self(new ZhHansChrono());
    }

    /**
     * Create a strict simplified Chinese parser.
     */
    public static function strictZhHans(): self
    {
        return new self(ZhHansChrono::strict(), ['strict' => true]);
    }

    /**
     * Create a traditional Chinese parser.
     */
    public static function zhHant(): self
    {
        return new self(new ZhHantChrono());
    }

    /**
     * Create a strict traditional Chinese parser.
     */
    public static function strictZhHant(): self
    {
        return new self(ZhHantChrono::strict(), ['strict' => true]);
    }

    /**
     * Return a parser instance with the given parser added.
     */
    public function withParser(Parser $parser, bool $prepend = false): self
    {
        return new self($this->engine->withParser($parser, $prepend), $this->defaultOptions);
    }

    /**
     * Return a parser instance without parsers matching the given class name.
     *
     * @param  class-string<Parser>  $parser
     */
    public function withoutParser(string $parser): self
    {
        return new self($this->engine->withoutParser($parser), $this->defaultOptions);
    }

    /**
     * Create a shallow copy of this parser with the same parser/refiner configuration.
     */
    public function clone(): self
    {
        return new self($this->engine->clone(), $this->defaultOptions);
    }

    /**
     * Return a parser instance with the given refiner added.
     */
    public function withRefiner(Refiner $refiner, bool $prepend = false): self
    {
        return new self($this->engine->withRefiner($refiner, $prepend), $this->defaultOptions);
    }

    /**
     * Return a parser instance without refiners matching the given class name.
     *
     * @param  class-string<Refiner>  $refiner
     */
    public function withoutRefiner(string $refiner): self
    {
        return new self($this->engine->withoutRefiner($refiner), $this->defaultOptions);
    }

    /**
     * Return a parser instance with the given parsers added.
     *
     * @param  iterable<Parser>  $parsers
     */
    public function withParsers(iterable $parsers, bool $prepend = false): self
    {
        $chrono = $this;
        $parsers = is_array($parsers) ? $parsers : iterator_to_array($parsers);

        if ($prepend) {
            $parsers = array_reverse($parsers);
        }

        foreach ($parsers as $parser) {
            $chrono = $chrono->withParser($parser, $prepend);
        }

        return $chrono;
    }

    /**
     * Return a parser instance with the given refiners added.
     *
     * @param  iterable<Refiner>  $refiners
     */
    public function withRefiners(iterable $refiners, bool $prepend = false): self
    {
        $chrono = $this;
        $refiners = is_array($refiners) ? $refiners : iterator_to_array($refiners);

        if ($prepend) {
            $refiners = array_reverse($refiners);
        }

        foreach ($refiners as $refiner) {
            $chrono = $chrono->withRefiner($refiner, $prepend);
        }

        return $chrono;
    }

    /**
     * Parse the text with the default casual English parser.
     *
     * @param  CarbonInterface|string|array<string, mixed>|null  $reference
     * @param  array<string, mixed>  $options
     * @return array<int, ParsedResult>
     */
    public static function parse(
        string $text,
        CarbonInterface|string|array|null $reference = null,
        array $options = []
    ): array {
        return (new self())->parseText($text, $reference, $options);
    }

    /**
     * Parse the text and return the first result as a Carbon instance.
     *
     * @param  CarbonInterface|string|array<string, mixed>|null  $reference
     * @param  array<string, mixed>  $options
     */
    public static function parseDate(
        string $text,
        CarbonInterface|string|array|null $reference = null,
        array $options = []
    ): ?CarbonImmutable {
        $result = self::parse($text, $reference, $options)[0] ?? null;

        return $result?->start->date();
    }

    /**
     * Parse the text with this configured parser.
     *
     * @param  CarbonInterface|string|array<string, mixed>|null  $reference
     * @param  array<string, mixed>  $options
     * @return array<int, ParsedResult>
     */
    public function parseText(
        string $text,
        CarbonInterface|string|array|null $reference = null,
        array $options = []
    ): array {
        $options = new Options([...$this->defaultOptions, ...$options]);

        return $this->engine->parse($text, Reference::make($reference, $options), $options);
    }

    /**
     * Parse the text with this configured parser and return the first result as a Carbon instance.
     *
     * @param  CarbonInterface|string|array<string, mixed>|null  $reference
     * @param  array<string, mixed>  $options
     */
    public function parseDateText(
        string $text,
        CarbonInterface|string|array|null $reference = null,
        array $options = []
    ): ?CarbonImmutable {
        $result = $this->parseText($text, $reference, $options)[0] ?? null;

        return $result?->start->date();
    }
}
