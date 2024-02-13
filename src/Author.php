<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\AuthorInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Author implements AuthorInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}