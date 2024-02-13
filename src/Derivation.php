<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\DerivationInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Derivation implements DerivationInterface, JsonSerializable
{
    protected UuidInterface $id;

    protected string $type;

    protected ContentInterface $title;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'id'    => $this->id->toString(),
            'type'  => $this->type,
            'title' => $this->title,
            'meta'  => $this->meta,
        ];
    }
}