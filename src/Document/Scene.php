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

use ScreenJSON\Cop;
use ScreenJSON\Surface;
use ScreenJSON\Document\Scene\Elements;
use \Exception;

class Scene extends Surface implements SceneInterface, JsonSerializable
{
    protected Cop $cop;

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
        $this->cop = new Cop;

        if ($animals)
        {
            $this->animals ($animals);
        }

        if ($authors)
        {
            //$this->authors ($authors);
        }

        if ($cast)
        {
            $this->cast ($cast);
        }

        if ( $created )
        {
            $this->created ($created);
        } 
        else 
        {
            $this->created (Carbon::now());
        }

        if ($extra)
        {
            $this->extra ($extra);
        }

        if ($heading)
        {
            $this->heading ($heading);
        }

        if ($locations)
        {
            $this->locations ($locations);
        }

        if ( $modified )
        {
            $this->modified ($modified);
        }
        else 
        {
            $this->modified (Carbon::now());
        }

        if ($moods)
        {
            $this->moods ($moods);
        }

        if ($props)
        {
            $this->props ($props);
        }

        if ($sounds)
        {
            $this->sounds ($sounds);
        }

        if ($sfx)
        {
            $this->sfx ($sfx);
        }

        if ($wardrobe)
        {
            $this->wardrobe ($wardrobe);
        }

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function animals (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->cop->check ('Animal', $value, ['blank']);

                $this->animals[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Animals', $value, ['array_slugs']);

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
                $this->cop->check ('Cast', $value, ['blank']);

                $this->cast[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Cast', $value, ['array_slugs']);

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

    public function created (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->cop->check ('Scene creation', $value, ['future']);

            $this->created = $value;

            return $this;
        }

        return $this->created;
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
            if (in_array ($element::class, [Elements\Character::class, Elements\Shot::class, Elements\Transition::class]))
            {
                $element?->content()?->upper();
            }

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
                $this->cop->check ('Extra', $value, ['blank']);

                $this->extra[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Extra', $value, ['array_slugs']);

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

    public function heading (?HeadingInterface $heading = null) : self | HeadingInterface
    {
        if ( $heading )
        {
            $this->heading = $heading;

            return $this;
        }

        return $this?->heading;
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
                $this->cop->check ('Location', $value, ['blank']);

                $this->locations[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Locations', $value, ['array_slugs']);

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

    public function modified (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->cop->check ('Scene modified', $value, ['future']);

            $this->modified = $value;

            return $this;
        }

        return $this->modified;
    }

    public function moods (mixed $value = null) : self | array 
    {
        if ( $value )
        {
            if ( is_string ($value) )
            {
                $this->cop->check ('Mood', $value, ['blank']);

                $this->moods[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Moods', $value, ['array_slugs']);

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
                $this->cop->check ('Prop', $value, ['blank']);

                $this->props[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Props', $value, ['array_slugs']);

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
                $this->cop->check ('SFX', $value, ['blank']);

                $this->sfx[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('SFX', $value, ['array_slugs']);

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
                $this->cop->check ('Sound', $value, ['blank']);

                $this->sounds[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Sounds', $value, ['array_slugs']);

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
                $this->cop->check ('Tag', $value, ['blank']);

                $this->tags[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Tags', $value, ['array_slugs']);

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
                $this->cop->check ('VFX', $value, ['blank']);

                $this->vfx[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('VFX', $value, ['array_slugs']);

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
                $this->cop->check ('Wardrobe', $value, ['blank']);

                $this->wardrobe[] = trim ($value);

                return $this;
            }

            if ( is_array ($value) )
            {
                $this->cop->check ('Wardrobe', $value, ['array_slugs']);

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