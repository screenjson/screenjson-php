<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\LicenseInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class License implements LicenseInterface, JsonSerializable
{
    protected string $identifier;

    protected string $ref;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'identifier' => $this->identifier,
            'ref'        => $this->ref,
            'meta'       => $this->meta,
        ];
    }
}