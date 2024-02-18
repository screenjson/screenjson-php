<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\LicenseInterface;

use \JsonSerializable;
use \Carbon\Carbon;

use \UnexpectedValueException;
use Respect\Validation\Validator as v;

class License extends Surface implements LicenseInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected string $identifier = 'CC-BY-NC-ND-4.0',
        protected string $ref = 'https://spdx.org/licenses/'
    ) {
        $this->cop = new Cop;

        if ($identifier)
        {
            $this->identifier ($identifier);
        }

        if ($ref)
        {
            $this->ref ($ref);
        }
    }

    public function identifier (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('License identifier', $value, ['blank', 'alpha_dash']);

            $this->identifier = trim ($value);

            return $this;
        }

        return $this->identifier;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'identifier' => $this->identifier,
            'ref'        => $this->ref,
        ], $this->meta?->all() ?? []);
    }

    public function ref (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('License identifier', $value, ['blank', 'url']);

            $this->ref = trim ($value);

            return $this;
        }

        return $this->ref;
    }
}