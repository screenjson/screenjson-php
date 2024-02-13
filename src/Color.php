<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ColorInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Color implements ColorInterface, JsonSerializable
{
    protected string $title;

    protected array $rgb = [];

    protected string $hex;

    protected MetaInterface $meta;

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