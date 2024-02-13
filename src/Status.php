<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\StatusInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Status implements StatusInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}