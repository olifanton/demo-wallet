<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\GenerateWords;

use Olifanton\DemoWallet\Modules\Wallets\Models\GeneratedWords;
use Olifanton\Mnemonic\TonMnemonic;

class GenerateWordsHandler
{
    /**
     * @throws \Olifanton\Mnemonic\Exceptions\TonMnemonicException
     */
    public function handle(GenerateWordsCommand $command): GeneratedWords
    {
        return new GeneratedWords(
            words: TonMnemonic::generate(),
        );
    }
}
