<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\AnnotationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Annotation implements AnnotationInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}