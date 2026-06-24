<?php

namespace Chrono\Locales\Zh\Hans\Parsers;

use Chrono\Locales\Zh\Parsers\AbstractZhTimeExpressionParser;
use Chrono\Locales\Zh\ZhConstants;

class ZhHansTimeExpressionParser extends AbstractZhTimeExpressionParser
{
    protected function numbers(): array { return ZhConstants::HANS_NUMBERS; }
    protected function timeWordPattern(): string { return '上午|早上|下午|晚上|夜晚?|中午|凌晨'; }
    protected function pointCharacter(): string { return '点'; }
    protected function tag(): string { return 'parser/ZHHansTimeExpressionParser'; }
}
