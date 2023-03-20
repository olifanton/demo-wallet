<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Http\Server\Slim;

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler;

class HttpErrorHandler extends ErrorHandler
{
    protected function respond(): ResponseInterface
    {
        $statusCode = 500;
        $message = "Application error";
        $exception = $this->exception;

        $this->logger->error(
            "[HttpErrorHandler] Unhandled error: " . $exception->getMessage(),
            [
                "exception" => $exception,
                "code" => $exception->getCode(),
            ],
        );

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $message = $exception->getMessage();
        }

        if (!$exception instanceof HttpException && $this->displayErrorDetails) {
            $message = $exception->getMessage();
        }

        if (!$exception instanceof HttpException || $this->displayErrorDetails) {
            $this->logger->error(
                "[HttpErrorHandler] Unhandled error: " . $exception->getMessage(),
                [
                    "exception" => $exception,
                ],
            );
        }

        $answer = [
            "is_error" => true,
            "message" => $message,
        ];

        if ($this->displayErrorDetails) {
            $answer["trace"] = $exception->getTraceAsString();
        }

        $body = \json_encode((object)$answer, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $contentType = "application/json";

        $response = $this->responseFactory->createResponse((int)$statusCode);
        $response->getBody()->write($body);

        return $response->withHeader('Content-Type', $contentType);
    }
}
