<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Meta implements MetaInterface, JsonSerializable
{
    protected array $map = [];

    public function __construct (
        ?array $data = null
    ) {
        if ($data)
        {
            $this->map = array_merge ($this->map, $data);
        }
    }

    public function add (array $data) : self 
    {
        if ($data)
        {
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