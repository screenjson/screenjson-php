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
use \Exception;

class Scene extends Surface implements SceneInterface, JsonSerializable
{
    public function __construct (
        protected ?HeadingInterface $heading = null,
        protected ?array $authors = null,
        protected ?Carbon $created = null,
        protected ?Carbon $modified = null,
        protected ?array $animals = null,
        protected ?array $cast = null,
        protected ?array $extra = null,
        protected ?array $locations = null,
        protected ?array $moods = null,
        protected ?array $props = null,
        protected ?array $sfx = null,
        protected ?array $sounds = null,
        protected ?array $tags = null,
        protected ?array $vfx = null,
        protected ?array $wardrobe = null,
        protected ?string $id = null,
        protected ?string $parent = null,
        protected ?array $body = [],
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if (! $created )
        {
            $this->created = Carbon::now();
        }

        if (! $modified )
        {
            $this->modified = Carbon::now();
        }
    }

    public function animals (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->animals[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->animals[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->animals;
    }

    public function cast (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->cast[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->cast[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->cast;
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

    public function extra (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->extra[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->extra[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->extra;
    }

    public function jsonSerialize() : array
    {
        $data = array_merge ([
            'id'            => is_string ($this->id) ? $this->id : $this->id?->toString(),
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

    public function locations (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->locations[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->locations[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->locations;
    }

    public function moods (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->moods[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->moods[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->moods;
    }

    public function props (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->props[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->props[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->props;
    }

    public function sfx (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->sfx[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->sfx[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->sfx;
    }

    public function sounds (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->sounds[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->sounds[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->sounds;
    }

    public function tags (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->tags[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->tags[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->tags;
    }

    public function vfx (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->vfx[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->vfx[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->vfx;
    }

    public function wardrobe (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->wardrobe[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                foreach ($value AS $tag)
                {
                    $this->wardrobe[] = trim ($tag);
                }

                return $this;
            }
            
            throw new Exception ("Tagged item must be a string or an array.");
        }

        return $this->wardrobe;
    }
}