<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\SendTransaction;

use Brick\Math\BigInteger;
use Olifanton\DemoWallet\Application\Exceptions\EntityNotFoundException;
use Olifanton\DemoWallet\Application\Exceptions\InvalidParamsException;
use Olifanton\DemoWallet\Application\Helpers\Validation;
use Olifanton\Interop\Address;
use Olifanton\Interop\Units;
use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;

readonly class SendTransactionCommand
{
    public function __construct(
        private string $walletId,
        private Address|string $destination,
        private BigInteger $amountNano,
        private ?string $comment,
    ) {}

    /**
     * @throws EntityNotFoundException
     * @throws InvalidParamsException
     */
    public static function fromRequest(ServerRequestInterface $request, ?string $walletId): self
    {
        if (!$walletId) {
            throw new EntityNotFoundException();
        }

        $params = $request->getParsedBody();
        $v = new Validator($params);
        $v->rule("required", ["destination", "amount"]);
        $v->rule("address", "destination");

        if (!$v->validate()) {
            throw new InvalidParamsException(
                Validation::implodeValitronMessages($v),
            );
        }

        $amount = Units::toNano($params["amount"]);

        if ($amount->isZero()) {
            throw new InvalidParamsException("Incorrect amount");
        }

        return new self(
            $walletId,
            $params["destination"],
            $amount,
            trim((string)($params["comment"] ?? "")),
        );
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }

    public function getDestinationAddress(): string|Address
    {
        return str_contains($this->destination, ".ton")
            ? mb_strtolower($this->destination)
            : new Address($this->destination);
    }

    public function getAmountNano(): BigInteger
    {
        return $this->amountNano;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
