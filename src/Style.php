<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\StyleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Style implements StyleInterface, JsonSerializable
{
    public function __construct (
        protected ?string $id = null,
        protected ?string $content = null,
        protected ?bool $default = null,
    ) {}

    public function jsonSerialize() : array
    {
        return array_merge ([
            'id'      => $this->id,
            'default' => $this->default,
            'content' => $this->content,
        ], []);
    }
}