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
    protected array $bookmarks = [];

    protected CoverInterface $cover;

    protected FooterInterface $footer;

    protected HeaderInterface $header;

    protected MetaInterface $meta;

    protected array $scenes = [];

    protected StatusInterface $status;

    protected array $styles = [];

    protected array $templates = [];

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