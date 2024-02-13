<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\FooterInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Footer implements FooterInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}