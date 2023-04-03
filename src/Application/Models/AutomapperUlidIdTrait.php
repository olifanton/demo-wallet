<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Models;

use Olifanton\DemoWallet\Application\Helpers\Sqlite\AutomapperColumn;
use function DeepCopy\deep_copy;

trait AutomapperUlidIdTrait
{
    #[AutomapperColumn("id")]
    protected ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): self
    {
        $instance = deep_copy($this);
        $instance->id = $id;

        return $instance;
    }
}
