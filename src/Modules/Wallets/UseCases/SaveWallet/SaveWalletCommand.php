<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\UseCases\SaveWallet;

use Olifanton\DemoWallet\Application\Exceptions\InvalidParamsException;
use Olifanton\DemoWallet\Application\Helpers\Validation;
use Olifanton\Mnemonic\Wordlist\Bip39English;
use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;

readonly class SaveWalletCommand
{
    /**
     * @param string[] $words
     */
    public function __construct(
        private array $words,
    ) {}

    /**
     * @throws InvalidParamsException
     */
    public static function fromRequest(ServerRequestInterface $request): self
    {
        $params = $request->getParsedBody();
        $v = new Validator($params);
        $v->rule("required", "words");
        $v->rule("subset", "words", Bip39English::WORDS);

        if (!$v->validate()) {
            throw new InvalidParamsException(
                Validation::implodeValitronMessages($v),
            );
        }

        return new self(
            $params["words"],
        );
    }

    /**
     * @return string[]
     */
    public function getWords(): array
    {
        return $this->words;
    }
}
