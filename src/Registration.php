<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\RegistrationInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Registration extends Surface implements RegistrationInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?string $authority = null,
        protected ?string $identifier = null,
        protected ?Carbon $created = null,
        protected ?Carbon $modified = null,
    ) {
        $this->cop = new Cop;

        if ( $authority )
        {
            $this->authority ($authority);
        }

        if ( $identifier )
        {
            $this->identifier ($identifier);
        }

        if ( $created )
        {
            $this->created ($created);
        }

        if ( $modified )
        {
            $this->modified ($modified);
        }
    }

    public function authority (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Registration authority', $value, ['blank']);

            $this->authority = trim ($value);

            return $this;
        }

        return $this->authority;
    }

    public function created (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->cop->check ('Registration creation', $value, ['future']);

            $this->created = $value;

            return $this;
        }

        return $this->created;
    }

    public function identifier (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Registration authority', $value, ['blank', 'alpha_dash']);

            $this->identifier = trim ($value);

            return $this;
        }

        return $this->identifier;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'authority'   => $this->authority,
            'identifier'  => $this->identifier,
            'created'     => $this->created,
            'modified'    => $this->modified,
        ], $this->meta?->all() ?? []);
    }

    public function modified (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->cop->check ('Registration modified', $value, ['future']);

            $this->modified = $value;

            return $this;
        }

        return $this->modified;
    }
}