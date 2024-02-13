<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ColorInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Color implements ColorInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}