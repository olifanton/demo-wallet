<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Models;

use Olifanton\DemoWallet\Application\Helpers\Sqlite\AutomapperColumn;
use Olifanton\DemoWallet\Application\Models\AutomapperUlidIdTrait;
use Olifanton\DemoWallet\Modules\Wallets\Storages\Serializers\KeyPairDeserializer;
use Olifanton\Interop\KeyPair;
use Olifanton\Mnemonic\TonMnemonic;
use Ulid\Ulid;

class SecretKey
{
    use AutomapperUlidIdTrait;

    #[AutomapperColumn("secret_key", AutomapperColumn::CUSTOM, KeyPairDeserializer::class)]
    protected ?KeyPair $keyPair = null;

    #[AutomapperColumn("seed")]
    protected ?string $seed = null;

    public function getKeyPair(): ?KeyPair
    {
        return $this->keyPair;
    }

    public function getMnemonicPhrase(): ?string
    {
        return $this->seed;
    }

    /**
     * @throws \Olifanton\Mnemonic\Exceptions\TonMnemonicException
     */
    public static function create(string $mnemonicPhrase): self
    {
        $instance = new self();
        $keyPair = TonMnemonic::mnemonicToKeyPair(explode(" ", $mnemonicPhrase));
        $instance->keyPair = $keyPair;
        $instance->seed = $mnemonicPhrase;
        $instance->id = (string)Ulid::generate();

        return $instance;
    }
}
