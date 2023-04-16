<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Modules;

use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\Bootstrapper;
use Olifanton\DemoWallet\Application\Helpers\ClassFinder;
use Olifanton\DemoWallet\Application\Helpers\ClassFinderFilter;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;

final class ModulesConfigurator
{
    /**
     * @throws \Throwable
     */
    public static function configure(ContainerBuilder $builder): void
    {
        $classes = ClassFinder::find(
            (new ClassFinderFilter())->withSubclassOf(Bootstrapper::class),
        );
        $dg = new DependencyGraph();

        foreach ($classes as $class) {
            if ($class !== ModuleBootstrapper::class) {
                $dg->requires($class, call_user_func([$class, "requires"]));
            }
        }

        foreach ($dg->getTopologicalSorted() as $bootstrapper) {
            call_user_func([$bootstrapper, "boot"], $builder);
        }
    }
}
