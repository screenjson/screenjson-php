<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\DerivationInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Derivation implements DerivationInterface, JsonSerializable
{
    public function __construct (
        protected ?string $type = null,
        protected ?ContentInterface $title = null,
        protected ?UuidInterface $id = null,
        protected ?MetaInterface $meta = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function jsonSerialize() : array
    {
        return [
            'id'    => $this->id?->toString(),
            'type'  => $this->type,
            'title' => $this->title,
            'meta'  => $this->meta,
        ];
    }
}