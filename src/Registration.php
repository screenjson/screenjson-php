<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RegistrationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Registration implements RegistrationInterface, JsonSerializable
{
    public function __construct (
        protected ?string $authority = null,
        protected ?string $identifier = null,
        protected ?Carbon $created = null,
        protected ?Carbon $modified = null,
        protected ?MetaInterface $meta = null,
    ) {}


    public function jsonSerialize() : array
    {
        return [
            'authority'   => $this->authority,
            'identifier'  => $this->identifier,
            'created'     => $this->created,
            'modified'    => $this->modified,
            'meta'        => $this->meta,
        ];
    }
}