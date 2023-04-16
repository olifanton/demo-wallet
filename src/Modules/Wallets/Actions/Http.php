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
        private UseCases\SaveWallet\SaveWalletHandler $saveWalletHandler,
        private UseCases\WalletState\WalletStateHandler $walletStateHandler,
        private UseCases\WalletsList\WalletsListHandler $walletsListHandler,
    )
    {
    }

    /**
     * @throws \Throwable
     */
    #[Route("/wallet/generate-words", ["POST"])]
    public function generateWords(ServerRequestInterface $request,
                                  ResponseInterface $response,
                                  array $args,
    ): ResponseInterface
    {
        return HttpHelper::json(
            $this->generateWordsHandler->handle(new UseCases\GenerateWords\GenerateWordsCommand()),
        );
    }

    /**
     * @throws \Throwable
     */
    #[Route("/wallet/save-wallet", ["POST"])]
    public function save(ServerRequestInterface $request,
                         ResponseInterface $response,
                         array $args,
    ): ResponseInterface
    {
        return HttpHelper::json(
            $this->saveWalletHandler->handle(
                UseCases\SaveWallet\SaveWalletCommand::fromRequest($request),
            ),
        );
    }

    /**
     * @throws \Throwable
     */
    #[Route("/wallet/state/{walletId}")]
    public function getState(ServerRequestInterface $request,
                             ResponseInterface $response,
                             array $args,

    ): ResponseInterface
    {
        return HttpHelper::json(
            $this->walletStateHandler->handle(
                new UseCases\WalletState\GetStateCommand($args["walletId"] ?? null),
            ),
        );
    }

    /**
     * @throws \Throwable
     */
    #[Route("/wallets")]
    public function getList(ServerRequestInterface $request,
                             ResponseInterface $response,
                             array $args,

    ): ResponseInterface
    {
        return HttpHelper::json(
            $this->walletsListHandler->handle(
                new UseCases\WalletsList\WalletsListCommand(),
            ),
        );
    }
}
