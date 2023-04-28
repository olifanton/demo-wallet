<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\SendTransaction;

use Olifanton\DemoWallet\Application\Exceptions\EntityNotFoundException;
use Olifanton\DemoWallet\Application\Http\ApiAnswer;
use Olifanton\DemoWallet\Application\Http\GenericApiAnswer;
use Olifanton\DemoWallet\Modules\Wallets\Services\WalletContractFactory;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsFilter;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;
use Olifanton\Interop\Address;
use Olifanton\Interop\Boc\Exceptions\CellException;
use Olifanton\Interop\Boc\Exceptions\SliceException;
use Olifanton\Interop\Units;
use Olifanton\Ton\Contracts\Messages\Exceptions\MessageException;
use Olifanton\Ton\Contracts\Wallets\Exceptions\WalletException;
use Olifanton\Ton\Contracts\Wallets\TransferMessageOptions;
use Olifanton\Ton\Dns\DnsClient;
use Olifanton\Ton\Dns\Exceptions\DnsException;
use Olifanton\Ton\Exceptions\TransportException;
use Olifanton\Ton\SendMode;
use Olifanton\Ton\Transport;

readonly class SendTransactionHandler
{
    public function __construct(
        private WalletsStorage $walletsStorage,
        private SecretKeyStorage $secretKeyStorage,
        private WalletContractFactory $walletContractFactory,
        private DnsClient $dnsClient,
        private Transport $transport,
    ) {}

    /**
     * @throws EntityNotFoundException
     * @throws CellException
     * @throws SliceException
     * @throws MessageException
     * @throws WalletException
     * @throws DnsException
     * @throws TransportException
     */
    public function handle(SendTransactionCommand $command): ApiAnswer
    {
        $wallet = $this
            ->walletsStorage
            ->getList(
                (new WalletsFilter())->withId($command->getWalletId()),
            )[0] ?? null;

        if (!$wallet) {
            throw new EntityNotFoundException();
        }

        $kp = $this->secretKeyStorage->getById($wallet->getSecretKeyId())->getKeyPair();
        $smc = $this->walletContractFactory->getContract($wallet->getType(), $kp->publicKey);
        [$address, $domain] = $this->resolveAddress($command);

        $seqno = $smc->seqno($this->transport); // FIXME !!! Bad seqno for non-existed contract
        $transfer = $smc->createTransferMessage(new TransferMessageOptions(
            dest: $address,
            amount: Units::toNano(0.01),
            seqno: $seqno, // 0
            payload: $command->getComment(),
            sendMode: SendMode::PAY_GAS_SEPARATELY,
        ));
        $this->transport->send($transfer->sign($kp->secretKey));

        return GenericApiAnswer::success(
            [
                "address" => $address->toString(true, true, false),
                "domain" => $domain,
            ],
            message: "Transfer started",
        );
    }

    /**
     * @return array{Address, string|null}
     * @throws CellException
     * @throws DnsException
     * @throws SliceException
     */
    private function resolveAddress(SendTransactionCommand $command): array
    {
        $a = $command->getDestinationAddress();

        if (!is_string($a)) {
            return [$a, null];
        }

        $domain = $this->dnsClient->resolve($a);

        if (!$domain || !$domain->getWallet()) {
            throw new \RuntimeException("Failed to resolve wallet address by domain");
        }

        return [$domain->getWallet(), $a];
    }
}
