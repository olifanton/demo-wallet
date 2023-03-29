<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Http\Actions\Application;

use Nyholm\Psr7\Stream;
use Olifanton\DemoWallet\Http\Attributes\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class State
{
    #[Route("/state")]
    public function state(ServerRequestInterface $request,
                          ResponseInterface $response,
                          array $args): ResponseInterface
    {
        // FIXME

        return $response->withBody(Stream::create("Not implemented"));
    }
}
