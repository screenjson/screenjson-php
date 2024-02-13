<?php 

namespace ScreenJSON\Document;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\BookmarkInterface;
use ScreenJSON\Interfaces\ContentInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Bookmark implements BookmarkInterface, JsonSerializable 
{
    protected UuidInterface $id;

    protected ?UuidInterface $parent;

    protected int $scene;

    protected string $type;

    protected int $element;

    protected ContentInterface $title;

    protected ContentInterface $description;

    public function jsonSerialize() : array
    {
        return [
            'id'          => $this->id->toString(),
            'parent'      => $this->parent?->toString(),
            'scene'       => $this->scene,
            'type'        => $this->type,
            'element'     => $this->element,
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }
}