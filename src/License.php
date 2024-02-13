<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\LicenseInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class License implements LicenseInterface, JsonSerializable
{
    public function jsonSerialize() : array
    {
        return [

        ];
    }
}