<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application;

use DI\ContainerBuilder;

abstract class ModuleBootstrapper
{
    private function __construct()
    {
        // Constructor is prohibited
    }

    abstract public static function boot(ContainerBuilder $builder): void;

    /**
     * @return array<class-string<ModuleBootstrapper>>
     */
    public static function requires(): array
    {
        return [];
    }
}
