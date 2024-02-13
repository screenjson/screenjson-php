<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\RegistrationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Registration implements RegistrationInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}