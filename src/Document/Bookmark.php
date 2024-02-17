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

class Bookmark implements BookmarkInterface, JsonSerializable 
{
    public function __construct (
        protected ?ContentInterface $title = null,
        protected ?ContentInterface $description = null,
        protected ?string $type = null,
        protected ?int $scene = null,
        protected ?int $element = null,
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
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
}