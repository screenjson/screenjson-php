<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Meta implements MetaInterface, JsonSerializable
{
    protected Cop $cop;
    protected array $map = [];

    public function __construct (
        ?array $data = null
    ) {
        $this->cop = new Cop;

        if ($data)
        {
            $this->add ($data);
        }
    }

    public function add (array $data) : self 
    {
        if ($data)
        {
            $this->cop->check ('Meta data keys', $data, ['key_slugs']);

            $this->map = array_merge ($this->map, $data);
        }

        return $this;
    }

    public function all () : array 
    {
        return ['meta' => $this->map];
    }

    public function jsonSerialize() : array
    {
        return $this->map;
    }
}