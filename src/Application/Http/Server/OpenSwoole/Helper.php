<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Http\Server\OpenSwoole;

use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\UploadedFile;
use OpenSwoole\Core\Psr\Stream;
use OpenSwoole\Http\Server;
use Psr\Http\Message\ServerRequestInterface;

final class Helper
{
    public static function handle(Server $server, callable $callback): void
    {
        $server->on('request', function (\OpenSwoole\HTTP\Request $request, \OpenSwoole\HTTP\Response $response) use ($callback) {
            $serverRequest = self::from($request);
            $serverResponse = $callback($serverRequest);
            \OpenSwoole\Core\Psr\Response::emit($response, $serverResponse);
        });
    }

    private static function from(\OpenSwoole\HTTP\Request $request): ServerRequestInterface
    {
        /** @var UploadedFile[] $files */
        $files = [];

        if (isset($request->files)) {
            foreach ($request->files as $name => $fileData) {
                $files[$name] = new UploadedFile(
                    Stream::createStreamFromFile($fileData['tmp_name']),
                    $fileData['size'],
                    $fileData['error'],
                    $fileData['name'],
                    $fileData['type']
                );
            }
        }

        return (new ServerRequest(
            $request->server['request_method'],
            $request->server['request_uri'],
            $request->header,
            $request->rawContent() ? $request->rawContent() : 'php://memory',
        ))
            ->withUploadedFiles($files)
            ->withCookieParams($request->cookie ?? []);
    }
}
