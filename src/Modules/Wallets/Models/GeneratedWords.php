<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Models;

use Olifanton\DemoWallet\Application\Http\ApiAnswer;

readonly class GeneratedWords implements ApiAnswer
{
    /**
     * @param string[] $words
     */
    public function __construct(
        public array $words,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            "is_success" => true,
            "data" => array_values($this->words),
        ];
    }
}
