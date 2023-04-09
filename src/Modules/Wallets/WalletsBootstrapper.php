<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets;

use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;
use Olifanton\DemoWallet\Infrastructure\Ton\TonBootstrapper;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SqliteSecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SqliteWalletStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;
use function DI\autowire;

class WalletsBootstrapper extends ModuleBootstrapper
{
    public static function boot(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            SecretKeyStorage::class => autowire(SqliteSecretKeyStorage::class),
            WalletsStorage::class => autowire(SqliteWalletStorage::class),
        ]);
    }

    public static function requires(): array
    {
        return [
            TonBootstrapper::class,
        ];
    }
}
