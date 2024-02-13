<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\HeaderInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Header implements HeaderInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}