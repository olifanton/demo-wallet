<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\ApplicationState\Models;

use Olifanton\DemoWallet\Application\Http\ApiAnswer;

readonly class ApplicationState implements ApiAnswer
{
    public function __construct(
        public bool $isApplicationInitialized,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            "is_success" => true,
            "data" => [
                "is_initialized" => $this->isApplicationInitialized,
            ],
        ];
    }
}
