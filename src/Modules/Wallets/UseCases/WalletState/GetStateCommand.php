<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\WalletState;

readonly class GetStateCommand
{
    public function __construct(
        private ?string $walletId,
    ) {}

    public function getWalletId(): ?string
    {
        return $this->walletId;
    }
}
