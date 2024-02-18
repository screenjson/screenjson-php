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
    protected Cop $cop;

    public function __construct (
        protected ?string $version = null,
        protected ?int $index = null,
        protected array $authors = [],
        protected ?Carbon $created = null,
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        $this->cop = new Cop;

        if ($this->version)
        {
            $this->version ($version);
        }

        if ($this->index)
        {
            $this->index ($index);
        }

        if ($this->authors)
        {
            $this->authors ($authors);
        }

        if ( $this->created )
        {
            $this->created ($created);
        }

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if (! $this->created )
        {
            $this->created (new Carbon);
        }
    }

    public function authors (?array $value = null) : self | array 
    {
        if ($value)
        {
            $this->authors = $value;

            return $this;
        }

        return $this->authors;
    }

    public function created (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->cop->check ('Revision creation', $value, ['future']);

            $this->created = $value;

            return $this;
        }

        return $this->created;
    }

    public function index (?int $value = null) : self | int 
    {
        if ($value)
        {
            $this->cop->check ("Revision index", $value, ['above_zero']);
            
            $this->index = $value;

            return $this;
        }

        return $this->index;
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

    public function version (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->version = trim ($value);

            return $this;
        }

        return $this->version;
    }
}