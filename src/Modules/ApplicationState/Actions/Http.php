<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\ApplicationState\Actions;

use Olifanton\DemoWallet\Application\Http\HttpHelper;
use Olifanton\DemoWallet\Application\Http\Route;
use Olifanton\DemoWallet\Modules\ApplicationState\UseCases as UseCases;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class Http
{
    public function __construct(
        private UseCases\GetState\GetStateHandler $getStateHandler,
    ) {}

    /**
     * @throws \Throwable
     */
    #[Route("/state")]
    public function state(ServerRequestInterface $request,
                          ResponseInterface $response,
                          array $args): ResponseInterface
    {
        return HttpHelper::json(
            $this->getStateHandler->handle(new UseCases\GetState\GetStateCommand()),
        );
    }
}
