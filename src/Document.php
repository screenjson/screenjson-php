<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\{
    CoverInterface,
    DocumentInterface,
    HeaderInterface,
    FooterInterface,
    SceneInterface,
    StatusInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

class Document extends Surface implements DocumentInterface, JsonSerializable
{
    public function __construct (
        protected ?CoverInterface $cover = null,
        protected ?FooterInterface $footer = null,
        protected ?HeaderInterface $header = null,
        protected ?StatusInterface $status = null,
        protected array $scenes = [],
        protected array $bookmarks = [],
        protected array $styles = [],
        protected array $templates = [],
    ) {
        if (! $status )
        {
            $this->status = new Status ('white', 0);
        }

        $this->styles[] = new Style ('courier-12', 'font-family: courier; font-size: 12px;', 1);
    }

    public function cover (?CoverInterface $cover = null) : self | CoverInterface
    {
        if ( $cover )
        {
            $this->cover = $cover;

            return $this;
        }

        return $this->cover;
    }

    public function footer (?FooterInterface $footer = null) : self | FooterInterface
    {
        if ( $footer )
        {
            $this->footer = $footer;

            return $this;
        }

        return $this->footer;
    }

    public function header (?HeaderInterface $header = null) : self | HeaderInterface
    {
        if ( $header )
        {
            $this->header = $header;

            return $this;
        }

        return $this?->header;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'bookmarks' => $this->bookmarks,
            'cover'     => $this->cover,
            'header'    => $this->header,
            'footer'    => $this->footer,
            'status'    => $this->status,
            'styles'    => $this->styles,
            'templates' => $this->templates,
            'scenes'    => $this->scenes,
        ], $this->meta?->all() ?? []);
    }

    public function scenes (?SceneInterface $scene = null) : self | SceneInterface
    {
        if ( $scene )
        {
            $this->scenes[] = $scene;

            return $this;
        }

        return $this->scenes;
    }
}