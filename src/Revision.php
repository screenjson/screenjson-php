<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    RevisionInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

class Revision extends Surface implements RevisionInterface, JsonSerializable
{
    public function __construct (
        protected ?string $version = null,
        protected ?int $index = null,
        protected array $authors = [],
        protected ?Carbon $created = null,
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if (! $this->created )
        {
            $this->created = Carbon::now();
        }
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'id'       => is_string ($this->id) ? $this->id : $this->id?->toString(),
            'parent'   => $this->parent?->toString(),
            'index'    => $this->index,
            'authors'  => $this->authors,
            'version'  => $this->version,
            'created'  => $this->created?->format('c'),
        ], $this->meta?->all() ?? []);
    }
}