<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Olifanton\DemoWallet\Application\Http\Server\OpenSwoole\Helper;
use Olifanton\DemoWallet\Application\Http\Server\OpenSwoole\ServerFactory;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

date_default_timezone_set("UTC");

define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$diConfigurator = require ROOT_DIR . "/config/di.php";
$appConfigurator = require ROOT_DIR . "/config/slim.php";

$containerBuilder = new ContainerBuilder();
$diConfigurator($containerBuilder);
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$appConfigurator($app);

$serverFactory = $container->get(ServerFactory::class);

$server = $serverFactory->createServer();
Helper::handle($server, function (ServerRequestInterface $request) use ($app) {
    return $app->handle($request);
});

$server->start();
