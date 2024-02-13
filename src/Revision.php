<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RevisionInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Revision implements RevisionInterface, JsonSerializable
{
    public function __construct (
        protected ?string $version = null,
        protected ?int $index = null,
        protected array $authors = [],
        protected ?UuidInterface $id = null,
        protected ?UuidInterface $parent = null,
        protected ?Carbon $created = null,
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
            'id'       => $this->id?->toString(),
            'parent'   => $this->parent?->toString(),
            'index'    => $this->index,
            'authors'  => $this->authors,
            'version'  => $this->version,
            'created'  => $this->created?->format('c'),
            'meta'     => $this->meta,
        ];
    }
}