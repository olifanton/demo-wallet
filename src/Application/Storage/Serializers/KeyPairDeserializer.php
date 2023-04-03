<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Storage\Serializers;

use Olifanton\DemoWallet\Application\Storage\CustomDeserializer;
use Olifanton\Interop\Bytes;
use Olifanton\Interop\KeyPair;

class KeyPairDeserializer implements CustomDeserializer
{
    public static function deserialize(string $data): KeyPair
    {
        $publicKey = substr($data, 0, 64);
        $secretKey = substr($data, 64);

        return new KeyPair(
            Bytes::hexStringToBytes($publicKey),
            Bytes::hexStringToBytes($secretKey),
        );
    }
}
