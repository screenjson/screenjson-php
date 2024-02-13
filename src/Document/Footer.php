<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\FooterInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Footer implements FooterInterface, JsonSerializable
{
    protected bool $cover;

    protected bool $display;

    protected int $start;

    protected array $omit = [];

    protected ContentInterface $content;

    protected MetaInterface $meta;

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