<?php 

namespace ScreenJSON\Document;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\BookmarkInterface;
use ScreenJSON\Interfaces\ContentInterface;
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
        protected ?UuidInterface $id = null,
        protected ?UuidInterface $parent = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function jsonSerialize() : array
    {
        return [
            'id'          => $this->id?->toString(),
            'parent'      => $this->parent?->toString(),
            'scene'       => $this->scene,
            'type'        => $this->type,
            'element'     => $this->element,
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }
}