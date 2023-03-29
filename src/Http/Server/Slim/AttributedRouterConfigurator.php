<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Http\Server\Slim;

use HaydenPierce\ClassFinder\ClassFinder;
use Olifanton\DemoWallet\Http\Attributes\Route;
use ReflectionClass;
use ReflectionMethod;
use Slim\App;

class AttributedRouterConfigurator
{
    /**
     * @throws \Exception
     */
    public static function configure(App $app): void
    {
        ClassFinder::setAppRoot(ROOT_DIR . "/");
        ClassFinder::disablePSR4Vendors();
        $classes = ClassFinder::getClassesInNamespace(
            'Olifanton\DemoWallet',
            ClassFinder::RECURSIVE_MODE,
        );

        foreach ($classes as $class) {
            $reflection = new ReflectionClass($class);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->isStatic()) {
                    continue;
                }

                $routeAttributes = $method->getAttributes(Route::class);

                foreach ($routeAttributes as $routeAttribute) {
                    $routeAttrArgs = self::normalizeAttributeArguments($routeAttribute->getArguments());
                    $app->map(
                        $routeAttrArgs["method"],
                        $routeAttrArgs["pattern"],
                        sprintf("%s:%s", $class, $method->name),
                    );
                }
            }
        }
    }

    private static function normalizeAttributeArguments(array $routeAttrArgs): array
    {
        $args = [
            "pattern",
            "method",
        ];
        $result = array_fill_keys($args, null);
        $result["method"] = ["GET"];

        foreach ($args as $index => $name) {
            $result[$name] = $routeAttrArgs[$name] ?? $routeAttrArgs[$index] ?? $result[$name];
        }

        return $result;
    }
}
