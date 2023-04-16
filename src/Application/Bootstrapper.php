<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application;

use DI\ContainerBuilder;

interface Bootstrapper
{
    public static function boot(ContainerBuilder $builder): void;

    /**
     * @return array<class-string<Bootstrapper>>
     */
    public static function requires(): array;
}
