<?php 

namespace ScreenJSON\Document\Scene\Elements;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Document\Scene\Element;

use ScreenJSON\Interfaces\{
    ContentInterface,
    ElementInterface
};

use \JsonSerializable;

use ScreenJSON\Enums;

class Shot extends Element implements ElementInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
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
        return $this->__build ([
            "type"      => Enums\Element::SHOT,
        ]);
    }
}