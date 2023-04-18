<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\WalletState;

use Olifanton\DemoWallet\Application\Exceptions\EntityNotFoundException;
use Olifanton\DemoWallet\Application\Exceptions\InvalidParamsException;
use Olifanton\DemoWallet\Application\Http\ApiAnswer;
use Olifanton\DemoWallet\Application\Http\GenericApiAnswer;
use Olifanton\DemoWallet\Application\Infrastructure\TonPriceFetcher;
use Olifanton\DemoWallet\Modules\Wallets\Services\WalletContractFactory;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsFilter;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;
use Olifanton\Interop\Units;
use Olifanton\Ton\Contracts\Exceptions\ContractException;
use Olifanton\Ton\Transports\Toncenter\Exceptions\ToncenterException;
use Olifanton\Ton\Transports\Toncenter\ToncenterV2Client;
use Psr\Log\LoggerInterface;

readonly class WalletStateHandler
{
    public function __construct(
        private WalletsStorage        $walletsStorage,
        private SecretKeyStorage      $secretKeyStorage,
        private WalletContractFactory $walletContractFactory,
        private ToncenterV2Client     $toncenterV2Client,
        private TonPriceFetcher       $priceFetcher,
        private LoggerInterface       $logger,
    )
    {
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidParamsException
     * @throws ContractException
     * @throws ToncenterException
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
        $secretKey = $this->secretKeyStorage->getById($wallet->getSecretKeyId());
        $walletContract = $this
            ->walletContractFactory
            ->getContract(
                $wallet->getType(),
                $secretKey->getKeyPair()->publicKey,
            );
        $this->logger->debug("[WalletStateHandler] Start balance fetching...");
        $balance = $this
            ->toncenterV2Client
            ->getAddressBalance(
                $walletContract->getAddress(),
            );
        $this->logger->debug("[WalletStateHandler] Balance fetched");
        $usdBalance = null;
        $usdPrice = $this->priceFetcher->getCurrentUSDPrice();

        if ($usdPrice !== null) {
            $usdBalance = Units::fromNano($balance)
                ->toBigDecimal()
                ->multipliedBy($usdPrice)
                ->toFloat();
        }

        return GenericApiAnswer::success([
            "id" => $wallet->getId(),
            "name" => $wallet->getName(),
            "address" => $walletContract
                ->getAddress()
                ->toString(true, true, false),
            "balance" => [
                "nano" => $balance->toBase(10),
                "wei" => Units::fromNano($balance)->toFloat(),
                "usd" => $usdBalance,
            ],
        ]);
    }
}
