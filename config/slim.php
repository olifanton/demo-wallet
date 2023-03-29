<?php declare(strict_types=1);

use Olifanton\DemoWallet\Http\Server\Slim\AttributedRouterConfigurator;
use Olifanton\DemoWallet\Http\Server\Slim\HttpErrorHandler;
use Psr\Log\LoggerInterface;
use Slim\App;

return static function (App $app) {
    $app->setBasePath("/api");
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorHandler = new HttpErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory(),
        $app->getContainer()->get(LoggerInterface::class),
    );
    $errorMiddleware = $app->addErrorMiddleware(
        true,
        false,
        false,
    );
    $errorMiddleware->setDefaultErrorHandler($errorHandler);
    AttributedRouterConfigurator::configure($app);
};
