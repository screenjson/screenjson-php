<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\{
    ContentInterface,
    HeaderInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Cop;
use ScreenJSON\Surface;

class Header extends Surface implements HeaderInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?ContentInterface $content = null,
        protected int $start = 1,
        protected bool $cover = false,
        protected bool $display = true,
        protected array $omit = [],
    ) {
        $this->cop = new Cop;

        if ( $content )
        {
            $this->content ($content);
        }

        if ( $cover )
        {
            $this->cover ($cover);
        }

        if ( $display )
        {
            $this->display ($display);
        }

        if ( $omit )
        {
            $this->omit ($omit);
        }

        if ( $start )
        {
            $this->start ($start);
        }
    }

    public function cover (?bool $value = null) : self | bool 
    {
        if ($value)
        {
            $this->cop->check ('Header cover', $value, ['bool_type', 'bool_val']);

            $this->cover = $value;

            return $this;
        }

        return $this->cover;
    }

    public function display (?bool $value = null) : self | bool 
    {
        if ($value)
        {
            $this->cop->check ('Header display', $value, ['bool_type', 'bool_val']);

            $this->display = $value;

            return $this;
        }

        return $this->display;
    }

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

    public function omit (?array $pages = null) : self | array 
    {
        if ($pages)
        {
            $this->omit = $pages;

            return $this;
        }

        return $this->omit;
    }

    public function start (?int $value = null) : self | int 
    {
        if ($value)
        {
            $this->cop->check ('Header start', $value, ['above_zero']);

            $this->start = $value;

            return $this;
        }

        return $this->start;
    }
}