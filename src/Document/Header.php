<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\HeaderInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Header implements HeaderInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
        protected int $start = 1,
        protected bool $cover = false,
        protected bool $display = true,
        protected array $omit = [],
        protected ?MetaInterface $meta = null,
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'cover'     => $this->cover,
            'display'   => $this->display,
            'start'     => $this->start,
            'omit'      => $this->omit,
            'content'   => $this->content,
            'meta'      => $this->meta,
        ];
    }
}