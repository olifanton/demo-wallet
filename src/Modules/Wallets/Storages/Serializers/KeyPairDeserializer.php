<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages\Serializers;

use Olifanton\DemoWallet\Application\Storage\CustomDeserializer;
use Olifanton\Interop\Bytes;
use Olifanton\Interop\KeyPair;

class KeyPairDeserializer implements CustomDeserializer
{
    public static function deserialize(string $data): KeyPair
    {
        return KeyPair::fromSecretKey(Bytes::hexStringToBytes($data));
    }
}
