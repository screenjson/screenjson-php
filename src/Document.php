<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\{
    BookmarkInterface,
    CoverInterface,
    DocumentInterface,
    HeaderInterface,
    FooterInterface,
    SceneInterface,
    StatusInterface,
    StyleInterface
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
    }

    public function bookmarks (?BookmarkInterface $bookmark = null) : self | array 
    {
        if ($bookmark)
        {
            $this->bookmarks[] = $bookmark;

            return $this;
        }

        return $this->bookmarks;
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

    public function status (?StatusInterface $status = null) : self | StatusInterface 
    {
        if ($status)
        {
            $this->status = $status;

            return $this;
        }

        return $this->status;
    }

    public function styles (?StyleInterface $style = null) : self | array 
    {
        if ($style)
        {
            $this->styles[] = $style;

            return $this;
        }

        return $this->styles;
    }
}