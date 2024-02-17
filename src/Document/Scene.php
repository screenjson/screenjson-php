<?php 

namespace ScreenJSON\Document;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    ElementInterface,
    HeadingInterface,
    SceneInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Surface;

class Scene extends Surface implements SceneInterface, JsonSerializable
{
    public function __construct (
        protected ?HeadingInterface $heading = null,
        protected ?string $id = null,
        protected ?string $parent = null,
        protected array $authors = null,
        protected ?Carbon $created = null,
        protected ?Carbon $modified = null,
        protected array $body = null,
        protected array $animals = null,
        protected array $cast = null,
        protected array $extra = null,
        protected array $locations = null,
        protected array $moods = null,
        protected array $props = null,
        protected array $sfx = null,
        protected array $sounds = null,
        protected array $tags = null,
        protected array $vfx = null,
        protected array $wardrobe = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function defaults () : self 
    {
        $this->animals      = [];
        $this->authors      = [];
        $this->cast         = [];
        $this->contributors = [];
        $this->extra        = [];
        $this->locations    = [];
        $this->moods        = [];
        $this->props        = [];
        $this->sfx          = [];
        $this->sounds       = [];
        $this->tags         = [];
        $this->vfx          = [];
        $this->wardrobe     = [];

        $this->created = Carbon::now();
        $this->modified = Carbon::now();

        return $this;
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
        $data = array_merge ([
            'id'            => $this->id?->toString(),
            'heading'       => $this->heading,
            'authors'       => $this->authors,
            'contributors'  => $this->contributors,
            'body'          => $this->body,
            'animals'       => $this->animals,
            'cast'          => $this->cast,
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
        ], $this->meta?->all() ?? []);

        foreach ($data AS $k => $v)
        {
            if (! $v || is_null ($v) )
            {
                unset ($data[$k]);
            }
        }

        return $data;
    }
}