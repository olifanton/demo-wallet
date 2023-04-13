<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\WalletState;

use Olifanton\DemoWallet\Application\Exceptions\EntityNotFoundException;
use Olifanton\DemoWallet\Application\Exceptions\InvalidParamsException;
use Olifanton\DemoWallet\Application\Http\ApiAnswer;
use Olifanton\DemoWallet\Application\Http\GenericApiAnswer;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsFilter;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;

readonly class WalletStateHandler
{
    public function __construct(
        private WalletsStorage $walletsStorage,
    )
    {
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidParamsException
     */
    public function handle(GetStateCommand $command): ApiAnswer
    {
        $walletId = $command->getWalletId();

        if (!$walletId) {
            throw new InvalidParamsException("Wallet identifier is required");
        }

        $wallets = $this
            ->walletsStorage
            ->getList(
                (new WalletsFilter())->withId($walletId)
            );

        if (!$wallets) {
            throw new EntityNotFoundException("Wallet with id " . $walletId . " not found");
        }

        $wallet = $wallets[0];

        return new GenericApiAnswer(
            true,
            data: [
                "id" => $wallet->getId(),
                "name" => $wallet->getName(),
            ],
        );
    }
}
