<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncryptionInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Encryption implements EncryptionInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}