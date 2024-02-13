<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\StyleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Style implements StyleInterface, JsonSerializable
{
    public function __construct (
        protected ?string $id = null,
        protected ?string $content = null,
        protected ?bool $default = null,
        protected ?MetaInterface $meta = null,
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'id'      => $this->id,
            'default' => $this->default,
            'content' => $this->content,
            'meta'    => $this->meta,
        ];
    }
}