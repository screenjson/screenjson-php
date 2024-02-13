<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\StyleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Style implements StyleInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}