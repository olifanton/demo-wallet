<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Services;

use Olifanton\Ton\Contracts\ContractOptions;
use Olifanton\Ton\Contracts\Wallets as Wallets;
use Olifanton\Ton\Contracts\Wallets\Wallet as WalletContract;
use Olifanton\TypedArrays\Uint8Array;

/**
 * @template T of WalletContract
 */
class WalletContractFactory
{
    /**
     * @var class-string<T>[]
     */
    private static array $contracts = [
        Wallets\Simple\SimpleWalletR1::class,
        Wallets\Simple\SimpleWalletR2::class,
        Wallets\Simple\SimpleWalletR3::class,
        Wallets\V2\WalletV2R1::class,
        Wallets\V2\WalletV2R2::class,
        Wallets\V3\WalletV3R1::class,
        Wallets\V3\WalletV3R2::class,
        Wallets\V4\WalletV4R1::class,
        Wallets\V4\WalletV4R2::class,
    ];

    public function getContract(string $name, Uint8Array $publicKey): WalletContract
    {
        foreach (self::$contracts as $contractClass) {
            if (call_user_func([$contractClass, "getName"]) === $name) {
                return match ($contractClass) {
                    Wallets\Simple\SimpleWalletR1::class,
                    Wallets\Simple\SimpleWalletR2::class,
                    Wallets\Simple\SimpleWalletR3::class,
                    Wallets\V2\WalletV2R1::class,
                    Wallets\V2\WalletV2R2::class => $this->createSimpleWallet($contractClass, $publicKey),

                    Wallets\V3\WalletV3R1::class,
                    Wallets\V3\WalletV3R2::class => $this->createV3Wallet($contractClass, $publicKey),

                    Wallets\V4\WalletV4R1::class,
                    Wallets\V4\WalletV4R2::class => $this->createV4Wallet($contractClass, $publicKey),

                    default => throw new \RuntimeException("Not found creator for wallet " . $contractClass),
                };
            }
        }

        throw new \InvalidArgumentException(sprintf('Unknown wallet with type "%s"', $name));
    }

    private function createSimpleWallet(string $walletClass, Uint8Array $publicKey): WalletContract
    {
        return new $walletClass(new ContractOptions(
            $publicKey,
        ));
    }

    private function createV3Wallet(string $walletClass, Uint8Array $publicKey): WalletContract
    {
        return new $walletClass(new Wallets\V3\WalletV3Options(
            $publicKey,
        ));
    }

    private function createV4Wallet(string $walletClass, Uint8Array $publicKey): WalletContract
    {
        return new $walletClass(new Wallets\V4\WalletV4Options(
            $publicKey,
        ));
    }
}
