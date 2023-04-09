<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\SaveWallet;

use Olifanton\DemoWallet\Application\Http\ApiAnswer;
use Olifanton\DemoWallet\Application\Http\GenericApiAnswer;
use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\DemoWallet\Modules\Wallets\Models\Wallet;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsStorage;
use Olifanton\Mnemonic\Exceptions\TonMnemonicException;
use Olifanton\Mnemonic\TonMnemonic;

readonly class SaveWalletHandler
{
    public function __construct(
        private SecretKeyStorage $keyStorage,
        private WalletsStorage $walletsStorage,
    )
    {
    }

    /**
     * @throws TonMnemonicException
     */
    public function handle(SaveWalletCommand $command): ApiAnswer
    {
        $words = $command->getWords();

        if (!TonMnemonic::validate($words)) {
            throw new \InvalidArgumentException("Invalid mnemonic");
        }

        $keyPair = TonMnemonic::mnemonicToKeyPair($words);
        $key = $this->keyStorage->getBySecretKeyValue($keyPair->secretKey);

        if (!$key) {
            $key = SecretKey::createFromKeyPair($keyPair, $words);
            $this->keyStorage->add($key);
            $wallet = Wallet::create($key->getId());
            $this->walletsStorage->save($wallet);
        }

        return GenericApiAnswer::success();
    }
}
