<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\SceneInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Scene implements SceneInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}