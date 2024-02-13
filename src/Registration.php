<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RegistrationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Registration implements RegistrationInterface, JsonSerializable
{
    protected string $authority;

    protected string $identifier;

    protected Carbon $created;

    protected Carbon $modified;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [

        ];
    }
}