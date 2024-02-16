<?php 

namespace ScreenJSON\Document\Scene\Elements;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Document\Scene\Element;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\ElementInterface;

use \JsonSerializable;

use ScreenJSON\Enums;

class General extends Element implements ElementInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
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
        return $this->__build ([
            "type"      => Enums\Element::GENERAL,
        ]);
    }
}