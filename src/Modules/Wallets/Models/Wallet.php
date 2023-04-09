<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Models;

use Olifanton\DemoWallet\Application\Helpers\Sqlite\AutomapperColumn;
use Olifanton\DemoWallet\Application\Models\AutomapperUlidIdTrait;
use Olifanton\Ton\Contracts\Wallets\V3\WalletV3R2;
use Ulid\Ulid;
use function DeepCopy\deep_copy;

class Wallet
{
    use AutomapperUlidIdTrait;

    #[AutomapperColumn("secret_key_id")]
    protected ?string $secretKeyId = null;

    #[AutomapperColumn("name")]
    protected ?string $name = null;

    #[AutomapperColumn("wallet_type")]
    protected ?string $type = null;

    public function getSecretKeyId(): ?string
    {
        return $this->secretKeyId;
    }

    public function withSecretKeyId(string $secretKeyId): self
    {
        $instance = deep_copy($this);
        $instance->secretKeyId = $secretKeyId;

        return $instance;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function withName(?string $name): self
    {
        $instance = deep_copy($this);
        $instance->name = $name;

        return $instance;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function withType(?string $type): self
    {
        $instance = deep_copy($this);
        $instance->type = $type;

        return $instance;
    }

    public static function create(string $secretKeyId): self
    {
        $instance = new self();
        $instance->secretKeyId = $secretKeyId;
        $instance->id = (string)Ulid::generate();
        $instance->name = "Wallet " . date("d.M.Y H:i");
        $instance->type = WalletV3R2::getName();

        return $instance;
    }
}
