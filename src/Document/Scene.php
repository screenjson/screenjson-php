<?php 

namespace ScreenJSON\Document;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\ElementInterface;
use ScreenJSON\Interfaces\HeadingInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\SceneInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Scene implements SceneInterface, JsonSerializable
{
    public function __construct (
        protected ?HeadingInterface $heading = null,
        protected ?UuidInterface $id = null,
        protected ?Carbon $created = null,
        protected ?Carbon $modified = null,
        protected ?MetaInterface $meta = null,
        protected array $body = [],
        protected array $animals = [],
        protected array $authors = [],
        protected array $cast = [],
        protected array $contributors = [],
        protected array $extra = [],
        protected array $locations = [],
        protected array $moods = [],
        protected array $props = [],
        protected array $sfx = [],
        protected array $sounds = [],
        protected array $tags = [],
        protected array $vfx = [],
        protected array $wardrobe = [],
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if (! $this->created )
        {
            $this->created = Carbon::now();
        }

        if (! $this->modified )
        {
            $this->modified = Carbon::now();
        }
    }

    public function element (?ElementInterface $element = null) : self | ElementInterface
    {
        if ( $element )
        {
            $this->body[] = $element;

            return $this;
        }

        return end ($this->body);
    }

    public function jsonSerialize() : array
    {
        return [
            'id'            => $this->id?->toString(),
            'heading'       => $this->heading,
            'body'          => $this->body,
            'animals'       => $this->animals,
            'authors'       => $this->authors,
            'cast'          => $this->cast,
            'contributors'  => $this->contributors,
            'extra'         => $this->extra,
            'locations'     => $this->locations,
            'moods'         => $this->moods,
            'props'         => $this->props,
            'sfx'           => $this->sfx,
            'sounds'        => $this->sounds,
            'tags'          => $this->tags,
            'vfx'           => $this->vfx,
            'wardrobe'      => $this->wardrobe,
            'created'       => $this->created?->format('c'),
            'modified'      => $this->modified?->format('c'),
            'meta'          => $this->meta,
        ];
    }
}