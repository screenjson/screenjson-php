<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\FooterInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Footer implements FooterInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
        protected int $start = 1,
        protected bool $cover = false,
        protected bool $display = true,
        protected array $omit = [],
    ) {}

    public function jsonSerialize() : array
    {
        return array_merge ([
            'cover'     => $this->cover,
            'display'   => $this->display,
            'start'     => $this->start,
            'omit'      => $this->omit,
            'content'   => $this->content,
        ], $this->meta?->all() ?? []);
    }
}