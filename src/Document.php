<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\DocumentInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Document implements DocumentInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}