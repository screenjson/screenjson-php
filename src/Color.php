<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ColorInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Color implements ColorInterface, JsonSerializable
{
    public function __construct (
        protected ?string $title = null,
        protected array $rgb = [],
        protected ?string $hex = null,
        protected ?MetaInterface $meta = null,
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'title' => $this->title,
            'rgb'   => $this->rgb,
            'hex'   => '#' . $this->hex,
            'meta'  => $this->meta,
        ];
    }
}