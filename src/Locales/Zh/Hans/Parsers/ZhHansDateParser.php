<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhDateParser;
use Chrono\Locales\Zh\ZhConstants;

class ZhHansDateParser extends AbstractZhDateParser
{
    protected function numbers(): array { return ZhConstants::HANS_NUMBERS; }
    protected function daySuffix(): string { return '日|号'; }
    protected function tag(): string { return 'parser/ZHHansDateParser'; }
}
