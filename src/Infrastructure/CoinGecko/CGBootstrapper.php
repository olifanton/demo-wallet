<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Infrastructure\CoinGecko;

use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\Infrastructure\TonPriceFetcher;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;
use function DI\autowire;

class CGBootstrapper extends ModuleBootstrapper
{
    public static function boot(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            TonPriceFetcher::class => autowire(CGPriceFetcher::class),
        ]);
    }
}
