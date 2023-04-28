<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\UpdateWallet;

use Olifanton\DemoWallet\Application\Exceptions\InvalidParamsException;
use Olifanton\DemoWallet\Application\Helpers\Validation;
use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;

readonly class UpdateWalletCommand
{
    public function __construct(
        public string $walletId,
        public array $params,
    ) {}

    /**
     * @throws InvalidParamsException
     */
    public static function fromRequest(ServerRequestInterface $request, ?string $walletId): self
    {
        if (!$walletId) {
            throw new InvalidParamsException("Wallet identifier is required");
        }

        $params = $request->getParsedBody();
        $v = new Validator($params);
        $v->rule("lengthMin", "name", 1);

        if (!$v->validate()) {
            throw new InvalidParamsException(
                Validation::implodeValitronMessages($v),
            );
        }

        return new self(
            $walletId,
            $params,
        );
    }
}
