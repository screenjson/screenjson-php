<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ColorInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Color extends Surface implements ColorInterface, JsonSerializable
{
    public function __construct (
        protected ?string $title = null,
        protected array $rgb = [],
        protected ?string $hex = null,
    ) {}

    public function jsonSerialize() : array
    {
        return array_merge ([
            'title' => $this->title,
            'rgb'   => $this->rgb,
            'hex'   => '#' . $this->hex
        ], $this->meta?->all() ?? []);
    }
}