<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ScreenplayInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Screenplay implements ScreenplayInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}