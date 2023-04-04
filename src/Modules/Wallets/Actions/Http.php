<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Actions;

use Olifanton\DemoWallet\Application\Http\HttpHelper;
use Olifanton\DemoWallet\Application\Http\Route;
use Olifanton\DemoWallet\Modules\Wallets\UseCases as UseCases;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class Http
{
    public function __construct(
        private UseCases\GenerateWords\GenerateWordsHandler $generateWordsHandler,
    )
    {
    }

    /**
     * @throws \Throwable
     */
    #[Route("/wallet/generate-words", ["POST"])]
    public function generateWords(ServerRequestInterface $request,
                          ResponseInterface $response,
                          array $args): ResponseInterface
    {
        return HttpHelper::json(
            $this->generateWordsHandler->handle(new UseCases\GenerateWords\GenerateWordsCommand()),
        );
    }
}
