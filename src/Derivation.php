<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    ContentInterface,
    DerivationInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

class Derivation extends Surface implements DerivationInterface, JsonSerializable
{
    public function __construct (
        protected ?string $type = null,
        protected ?ContentInterface $title = null,
        protected ?string $id = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'id'    => $this->id?->toString(),
            'type'  => $this->type,
            'title' => $this->title,
        ], $this->meta?->all() ?? []);
    }
}