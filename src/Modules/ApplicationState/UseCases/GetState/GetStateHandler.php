<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\ApplicationState\UseCases\GetState;

use Olifanton\DemoWallet\Modules\ApplicationState\Models\ApplicationState;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;

readonly class GetStateHandler
{
    public function __construct(
        private WalletsStorage $walletsStorage,
    )
    {
    }

    public function handle(GetStateCommand $command): ApplicationState
    {
        return new ApplicationState(
            isApplicationInitialized: $this->walletsStorage->getCount() > 0,
        );
    }
}
