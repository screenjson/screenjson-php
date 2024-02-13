<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\BookmarkInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Bookmark implements BookmarkInterface, JsonSerializable 
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}