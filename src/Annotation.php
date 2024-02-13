<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\AnnotationInterface;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\MetaInterface;

use \JsonSerializable;
use \Carbon\Carbon;

class Annotation implements AnnotationInterface, JsonSerializable
{
    protected UuidInterface $id;

    protected ?UuidInterface $parent;

    protected array $highlight = [];

    protected ?UuidInterface $contributor;

    protected ?Carbon $created;

    protected ContentInterface $content;

    protected ?string $color;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'id'            => $this->id->toString(),
            'parent'        => $this->parent?->toString(),
            'highlight'     => $this->highlight,
            'contributor'   => $this->contributor?->toString(),
            'content'       => $this->content,
            'color'         => $this->color,
            'created'       => $this->created?->format ('c'),
            'meta'          => $this->meta,
        ];
    }
}