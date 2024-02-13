<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\DerivationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Derivation implements DerivationInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}