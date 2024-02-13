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
    public function __construct (
        protected ?ContentInterface $content = null,
        protected ?UuidInterface $contributor,
        protected ?string $color,
        protected array $highlight = [],
        protected ?UuidInterface $id = null,
        protected ?UuidInterface $parent = null,
        protected ?Carbon $created,
        protected ?MetaInterface $meta = null,
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'id'            => $this->id?->toString(),
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