<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Olifanton\DemoWallet\Http\Server\SwooleServerFactory;

date_default_timezone_set("UTC");


define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$di = require ROOT_DIR . "/config/di.php";
$di($containerBuilder);
$container = $containerBuilder->build();

$serverFactory = $container->get(SwooleServerFactory::class);

$server = $serverFactory->createServer(static function () {
    // FIXME
});

$server->start();
