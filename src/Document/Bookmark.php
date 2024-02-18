<?php 

namespace ScreenJSON\Document;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    BookmarkInterface,
    ContentInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Cop;

class Bookmark implements BookmarkInterface, JsonSerializable 
{
    protected Cop $cop;

    public function __construct (
        protected ?ContentInterface $title = null,
        protected ?ContentInterface $description = null,
        protected ?string $type = null,
        protected ?int $scene = null,
        protected ?int $element = null,
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        $this->cop = new Cop;

        if ($description)
        {
            $this->description ($description);
        }

        if ($element)
        {
            $this->element ($element);
        }

        if ($scene)
        {
            $this->scene ($scene);
        }

        if ($title)
        {
            $this->title ($title);
        }

        if ($type)
        {
            $this->type ($type);
        }

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function description (?ContentInterface $desc = null) : self | ContentInterface
    {
        if ( $desc )
        {
            $this->cop->check ('Bookmark description', $desc, ['blank']);

            $this->description = $desc;

            return $this;
        }

        return $this->description;
    }

    public function element (?int $el = null) : self | int
    {
        if ( $el )
        {
            $this->cop->check ('Bookmark element index', $el, ['above_zero']);

            $this->element = $el;

            return $this;
        }

        return $this->element;
    }

    public function jsonSerialize() : array
    {
        return [
            'id'          => is_string ($this->id) ? $this->id : $this->id?->toString(),
            'parent'      => $this->parent?->toString(),
            'scene'       => $this->scene,
            'type'        => $this->type,
            'element'     => $this->element,
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }

    public function scene (?int $scene = null) : self | int
    {
        if ( $scene )
        {
            $this->cop->check ('Bookmark scene index', $scene, ['above_zero']);

            $this->scene = $scene;

            return $this;
        }

        return $this->scene;
    }

    public function title (?ContentInterface $title = null) : self | ContentInterface
    {
        if ( $title )
        {
            $this->cop->check ('Bookmark title', $title, ['blank']);

            $this->title = $title;

            return $this;
        }

        return $this->title;
    }

    public function type (?string $type = null) : self | int
    {
        if ( $type )
        {
            $this->cop->check ('Bookmark element type', $type, ['blank', 'alpha_dash', 'in'], ["action", "character", "dialogue", "general", "parenthetical", "shot", "transition"]);

            $this->type = trim ($type);

            return $this;
        }

        return $this->type;
    }
}