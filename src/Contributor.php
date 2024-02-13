<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ContributorInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Contributor implements ContributorInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}