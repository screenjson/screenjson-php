<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Meta implements MetaInterface, JsonSerializable
{
    public function __construct (
        protected array $map = []
    ) {}

    public function jsonSerialize() : array
    {
        return $this->map;
    }
}