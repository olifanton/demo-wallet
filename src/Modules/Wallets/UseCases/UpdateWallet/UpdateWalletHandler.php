<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\UpdateWallet;

use Olifanton\DemoWallet\Application\Exceptions\EntityNotFoundException;
use Olifanton\DemoWallet\Application\Http\ApiAnswer;
use Olifanton\DemoWallet\Application\Http\GenericApiAnswer;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsFilter;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;

readonly class UpdateWalletHandler
{
    public function __construct(
        private WalletsStorage $walletsStorage,
    ) {}

    /**
     * @throws EntityNotFoundException
     */
    public function handle(UpdateWalletCommand $command): ApiAnswer
    {
        $wallet = $this
            ->walletsStorage
            ->getList(
                (new WalletsFilter())->withId($command->walletId),
            )[0] ?? null;

        if (!$wallet) {
            throw new EntityNotFoundException();
        }

        if (isset($command->params["name"]) && !empty($command->params["name"])) {
            $wallet = $wallet->withName($command->params["name"]);
        }

        $this->walletsStorage->save($wallet);

        return GenericApiAnswer::success();
    }
}
