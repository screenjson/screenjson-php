<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\RegistrationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Registration extends Surface implements RegistrationInterface, JsonSerializable
{
    public function __construct (
        protected ?string $authority = null,
        protected ?string $identifier = null,
        protected ?Carbon $created = null,
        protected ?Carbon $modified = null,
    ) {}


    public function jsonSerialize() : array
    {
        return array_merge ([
            'authority'   => $this->authority,
            'identifier'  => $this->identifier,
            'created'     => $this->created,
            'modified'    => $this->modified,
        ], $this->meta?->all() ?? []);
    }
}