<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\CoverInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Cover implements CoverInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}