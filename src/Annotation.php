<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    AnnotationInterface,
    ContentInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

class Annotation extends Surface implements AnnotationInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
        protected ?string $contributor,
        protected ?string $color,
        protected ?Carbon $created,
        protected array $highlight = [],
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
        return array_merge([
            'id'            => $this->id?->toString(),
            'parent'        => $this->parent?->toString(),
            'highlight'     => $this->highlight,
            'contributor'   => $this->contributor,
            'content'       => $this->content,
            'color'         => $this->color,
            'created'       => $this->created?->format ('c'),
        ], $this->meta?->all() ?? []);
    }
}