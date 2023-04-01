<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Modules;

use DI\ContainerBuilder;
use HaydenPierce\ClassFinder\ClassFinder;
use Olifanton\DemoWallet\Application\Helpers\ApplicationHelper;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;

final class ModulesConfigurator
{
    /**
     * @throws \Throwable
     */
    public static function configure(ContainerBuilder $builder): void
    {
        ClassFinder::setAppRoot(ApplicationHelper::getRootDirectory() . "/");
        ClassFinder::disablePSR4Vendors();
        $classes = ClassFinder::getClassesInNamespace(
            ApplicationHelper::getNs(),
            ClassFinder::RECURSIVE_MODE,
        );

        $dg = new DependencyGraph();

        foreach ($classes as $class) {
            if (is_subclass_of($class, ModuleBootstrapper::class)) {
                $dg->requires($class, call_user_func([$class, "requires"]));
            }
        }

        foreach ($dg->getTopologicalSorted() as $bootstrapper) {
            call_user_func([$bootstrapper, "boot"], $builder);
        }
    }
}
