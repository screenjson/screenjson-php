<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\RevisionInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Revision implements RevisionInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}