<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Http\Server\Slim;

use Olifanton\DemoWallet\Application\Exceptions\EntityNotFoundException;
use Olifanton\DemoWallet\Application\Exceptions\InvalidParamsException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Slim\Handlers\ErrorHandler;

class HttpErrorHandler extends ErrorHandler
{
    protected function respond(): ResponseInterface
    {
        $statusCode = 500;
        $message = "Application error";
        $exception = $this->tryConvertDomainException($this->exception);

        $this->logger->error(
            "[HttpErrorHandler] Unhandled error: " . $exception->getMessage(),
            [
                "exception" => $exception,
                "code" => $exception->getCode(),
                "uri" => ($exception instanceof HttpException)
                    ? (string)$exception->getRequest()->getUri()
                    : null,
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
            "is_success" => false,
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

    private function tryConvertDomainException(\Throwable $exception): \Throwable
    {
        if ($exception instanceof EntityNotFoundException) {
            return new HttpNotFoundException(
                $this->request,
                $exception->getMessage(),
                $exception,
            );
        }

        if ($exception instanceof InvalidParamsException) {
            return new HttpBadRequestException(
                $this->request,
                $exception->getMessage(),
                $exception,
            );
        }

        return $exception;
    }
}
