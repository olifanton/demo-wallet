<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Helpers;

use HaydenPierce\ClassFinder\ClassFinder as HaydenPierceClassFinder;

final class ClassFinder
{
    private static ?array $classCache = null;

    /**
     * @return class-string[]
     */
    public static function find(?ClassFinderFilter $filter = null): array
    {
        $classes = self::ensureClasses();

        if ($filter) {
            if ($subclassOf = $filter->getSubclassOf()) {
                $classes = array_filter(
                    $classes,
                    static function (string $class) use ($subclassOf) {
                        return is_subclass_of($class, $subclassOf);
                    },
                );
            }
        }

        return $classes;
    }

    private static function ensureClasses(): array
    {
        if (!self::$classCache) {
            HaydenPierceClassFinder::setAppRoot(ApplicationHelper::getRootDirectory() . "/");
            HaydenPierceClassFinder::disablePSR4Vendors();
            self::$classCache = HaydenPierceClassFinder::getClassesInNamespace(
                ApplicationHelper::getNs(),
                HaydenPierceClassFinder::RECURSIVE_MODE,
            );
        }

        return self::$classCache;
    }
}
