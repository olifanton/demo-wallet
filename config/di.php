<?php /** @noinspection PhpVariableIsUsedOnlyInClosureInspection */

declare(strict_types=1);

use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\Modules\ModulesConfigurator;

$configs = [
    "cache",
    "http-client",
    "monolog",
    "swoole",
    "sqlite",
    "valitron",
];

return static function (ContainerBuilder $builder) use ($configs) {
    $builder->useAutowiring(true);
    $builder->useAttributes(false);

    /**
     * @param string[] $files
     */
    $loadConfig = static function (ContainerBuilder $builder, array $files): void {
        foreach ($files as $file) {
            $scriptName = __DIR__ . DIRECTORY_SEPARATOR . basename($file) . ".php";

            if (!file_exists($scriptName)) {
                throw new \RuntimeException('Unable to find config "'. $file .'"');
            }

            /** @noinspection UsingInclusionOnceReturnValueInspection */
            $fn = require_once $scriptName;
            $fn($builder);
        }
    };
    $loadConfig($builder, $configs);

    $builder->addDefinitions([
        //
    ]);

    ModulesConfigurator::configure($builder);
};
