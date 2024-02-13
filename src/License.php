<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\LicenseInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class License implements LicenseInterface, JsonSerializable
{
    public function __construct (
        protected string $identifier = 'CC-BY-NC-ND-4.0',
        protected string $ref = 'https://spdx.org/licenses/',
        protected ?MetaInterface $meta = null
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'identifier' => $this->identifier,
            'ref'        => $this->ref,
            'meta'       => $this->meta,
        ];
    }
}