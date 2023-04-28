<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\ApplicationState;

use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;
use Olifanton\DemoWallet\Modules\Wallets\WalletsBootstrapper;

class ApplicationStateBootstrapper extends ModuleBootstrapper
{
    public static function boot(ContainerBuilder $builder): void {}

    public static function requires(): array
    {
        return [
            WalletsBootstrapper::class,
        ];
    }
}
