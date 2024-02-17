<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\{
    ContentInterface,
    FooterInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Surface;

class Footer extends Surface implements FooterInterface, JsonSerializable
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