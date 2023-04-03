<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Storage;

interface CustomDeserializer
{
    public static function deserialize(string $data);
}
