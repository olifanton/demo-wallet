<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Http;

use Nyholm\Psr7\Response;

final class HttpHelper
{
    /**
     * @throws \JsonException
     */
    public static function json(array | \JsonSerializable $body, int $status = 200): Response
    {
        return new Response(
            $status,
            [
                "Content-Type" => "application/json",
            ],
            json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
        );
    }
}
