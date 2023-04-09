<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Http;

class GenericApiAnswer implements ApiAnswer
{
    public function __construct(
        private readonly bool $isSuccess,
        private readonly ?string $message = null,
        private readonly ?array $data = null,
    )
    {
    }

    public static function success(?array $data = null, ?string $message = "Successful action"): self
    {
        return new self(
            true,
            $message,
            $data,
        );
    }

    public static function fail(?string $message = "Failed action", ?array $data = null): self
    {
        return new self(
            false,
            $message,
            $data,
        );
    }

    public function jsonSerialize(): array
    {
        $answer = [
            "is_success" => $this->isSuccess,
        ];

        if ($this->data !== null) {
            $answer["data"] = $this->data;
        }

        if ($this->message !== null) {
            $answer["message"]  = $this->message;
        }

        return $answer;
    }
}
