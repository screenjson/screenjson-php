<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\LicenseInterface;

use \JsonSerializable;
use \Carbon\Carbon;

class License extends Surface implements LicenseInterface, JsonSerializable
{
    public function __construct (
        protected string $identifier = 'CC-BY-NC-ND-4.0',
        protected string $ref = 'https://spdx.org/licenses/'
    ) {}

    public function jsonSerialize() : array
    {
        return array_merge ([
            'identifier' => $this->identifier,
            'ref'        => $this->ref,
        ], $this->meta?->all() ?? []);
    }
}