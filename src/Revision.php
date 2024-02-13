<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RevisionInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Revision implements RevisionInterface, JsonSerializable
{
    protected UuidInterface $id;

    protected ?UuidInterface $parent;

    protected int $index;

    protected array $authors = [];

    protected string $version;

    protected Carbon $created;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'id'       => $this->id->toString(),
            'parent'   => $this->parent?->toString(),
            'index'    => $this->index,
            'authors'  => $this->authors,
            'version'  => $this->version,
            'created'  => $this->created?->format('c'),
            'meta'     => $this->meta,
        ];
    }
}