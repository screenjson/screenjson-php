<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\CoverInterface;
use ScreenJSON\Interfaces\DocumentInterface;
use ScreenJSON\Interfaces\HeaderInterface;
use ScreenJSON\Interfaces\FooterInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\StatusInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Document implements DocumentInterface, JsonSerializable
{
    public function __construct (
        protected ?CoverInterface $cover = null,
        protected ?FooterInterface $footer = null,
        protected ?HeaderInterface $header = null,
        protected ?StatusInterface $status = null,
        protected ?MetaInterface $meta = null,
        protected array $scenes = [],
        protected array $bookmarks = [],
        protected array $styles = [],
        protected array $templates = [],
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'bookmarks' => $this->bookmarks,
            'cover'     => $this->cover,
            'header'    => $this->header,
            'footer'    => $this->footer,
            'status'    => $this->status,
            'styles'    => $this->styles,
            'templates' => $this->templates,
            'scenes'    => $this->scenes,
            'meta'      => $this->meta,
        ];
    }
}