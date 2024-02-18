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

use ScreenJSON\Cop;
use ScreenJSON\Enums;

class Action extends Element implements ElementInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?ContentInterface $content = null,
        protected ?array $config = [],
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        $this->cop = new Cop;

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if ( $config && is_array ($config) && count ($config) )
        {
            $this->__apply_config_map ($config);
        }
    }    

    public function jsonSerialize() : array
    {
        return $this->__build ([
            "type"      => Enums\Element::ACTION,
        ]);
    }
}