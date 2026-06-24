<?php

namespace Chrono\Locales\Zh\Hant\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhDateParser;
use Chrono\Locales\Zh\ZhConstants;

class ZhHantDateParser extends AbstractZhDateParser
{
    protected function numbers(): array { return ZhConstants::HANT_NUMBERS; }
    protected function daySuffix(): string { return '日|號'; }
    protected function tag(): string { return 'parser/ZHHantDateParser'; }
}
