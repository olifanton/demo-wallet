<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Http;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
readonly class Route
{
    public function __construct(
        public string $pattern,
        public array  $method = ["GET"],
    )
    {
    }
}
