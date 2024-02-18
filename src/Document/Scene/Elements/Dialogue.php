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

class Dialogue extends Element implements ElementInterface, JsonSerializable
{
    public function __construct (
        protected ?string $origin = null,
        protected bool $dual = false,
        protected ?ContentInterface $content = null,
        protected ?array $config = [],
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        $this->cop = new Cop;

        if ($this->origin)
        {
            $this->origin ($origin);
        }

        if ($this->dual)
        {
            $this->dual ($dual);
        }

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if ( $config && is_array ($config) && count ($config) )
        {
            $this->__apply_config_map ($config);
        }
    }

    public function dual (?bool $value = null) : self | bool 
    {
        if ($value)
        {
            $this->cop->check ("Dual", $value, ['bool_type', 'bool_val']);

            $this->dual = $value;

            return $this;
        }

        return $this->dual;
    }

    public function jsonSerialize() : array
    {
        return $this->__build ([
            "type"      => Enums\Element::DIALOGUE,
            'origin'    => $this->origin,
            'dual'      => $this->dual,
        ]);
    }

    public function origin (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ("Origin", $value, ['blank', 'alpha_dash', 'in'], ["V.O", "O.S", "O.C", "FILTER"]);

            $this->origin = trim (mb_strtoupper($value));

            return $this;
        }

        return $this->origin;
    }
}