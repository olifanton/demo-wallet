<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\WalletsList;

use Olifanton\DemoWallet\Application\Http\ApiAnswer;
use Olifanton\DemoWallet\Application\Http\GenericApiAnswer;
use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\DemoWallet\Modules\Wallets\Models\Wallet;
use Olifanton\DemoWallet\Modules\Wallets\Services\WalletContractFactory;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyFilter;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;

readonly class WalletsListHandler
{
    public function __construct(
        private WalletsStorage $walletsStorage,
        private SecretKeyStorage $secretKeyStorage,
        private WalletContractFactory $walletContractFactory,
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle(WalletsListCommand $command): ApiAnswer
    {
        $result = [];

        $wallets = $this
            ->walletsStorage
            ->getList();
        $secretKeysIds = array_unique(array_map(static fn (Wallet $wallet) => $wallet->getSecretKeyId(), $wallets));
        /** @var array<string, SecretKey> $secretKeysMap */
        $secretKeysMap = [];

        foreach ($this->secretKeyStorage->getList((new SecretKeyFilter())->withId(...$secretKeysIds)) as $secretKey) {
            $secretKeysMap[$secretKey->getId()] = $secretKey;
        }

        foreach ($wallets as $wallet) {
            $walletContract = $this
                ->walletContractFactory
                ->getContract(
                    $wallet->getType(),
                    $secretKeysMap[$wallet->getSecretKeyId()]->getKeyPair()->publicKey,
                );
            $result[] = [
                "id" => $wallet->getId(),
                "name" => $wallet->getName(),
                "address" => $walletContract
                    ->getAddress()
                    ->toString(true, true, false),
            ];
        }

        return GenericApiAnswer::success([
            "wallets" => $result,
        ]);
    }
}
