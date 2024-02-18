<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    ContentInterface,
    DerivationInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

class Derivation extends Surface implements DerivationInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?string $type = null,
        protected ?ContentInterface $title = null,
        protected ?string $id = null,
    ) {
        $this->cop = new Cop;

        if ($type)
        {
            $this->type ($type);
        }

        if ($title)
        {
            $this->title ($title);
        }

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'id'    => is_string ($this->id) ? $this->id : $this->id?->toString(),
            'type'  => $this->type,
            'title' => $this->title,
        ], $this->meta?->all() ?? []);
    }

    public function title (?ContentInterface $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Derivation title', $value, ['blank']);

            $this->title = trim ($value);

            return $this;
        }

        return $this->title;
    }

    public function type (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Derivation type', $value, ['blank', 'alpha_dash']);

            $this->type = trim ($value);

            return $this;
        }

        return $this->type;
    }
}