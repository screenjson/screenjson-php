<?php 

namespace ScreenJSON\Document\Scene;

use ScreenJSON\Meta;
use ScreenJSON\Revision;
use ScreenJSON\Document\Scene\Content;

use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RevisionInterface;

use \Carbon\Carbon;

use ScreenJSON\Enums;

abstract class Element 
{
    public function __construct (
        protected string $type =  Enums\Element::ACTION,
        protected ?ContentInterface $content = null,
        protected string $perspective = Enums\Perspective::TWO_D,
        protected bool $interactivity = false,
        protected string $lang = Enums\Language::ENGLISH,
        protected string $charset = Enums\Charset::UTF8,
        protected string $dir = Enums\Direction::LTR,
        protected bool $omitted = false,
        protected bool $locked = false,
        protected bool $encrypted = false,
        protected string $html = "p",
        protected string $css = "col-md-12",
        protected array $access = [],
        protected ?RevisionInterface $revision = null,
        protected array $styles = [],
        protected ?MetaInterface $meta = null,
    ) {}

    public function charset (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->charset = $value;

            return $this;
        }

        return $this->charset;
    }

    public function css (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->css = $value;

            return $this;
        }

        return $this->css;
    }

    public function content (?Content $value = null) : string | self 
    {
        if ( $value )
        {
            $this->content = $value;

            return $this;
        }

        return $this->content;
    }

    public function dir (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->dir = $value;

            return $this;
        }

        return $this->dir;
    }

    public function encrypted (?bool $value = null) : bool | self 
    {
        if ( $value )
        {
            $this->encrypted = $value;

            return $this;
        }

        return $this->encrypted;
    }

    public function html (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->html = $value;

            return $this;
        }

        return $this->html;
    }

    public function interactivity (?bool $value = null) : bool | self 
    {
        if ( $value )
        {
            $this->interactivity = $value;

            return $this;
        }

        return $this->interactivity;
    }

    public function lang (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->lang = $value;

            return $this;
        }

        return $this->lang;
    }

    public function locked (?bool $value = null) : bool | self 
    {
        if ( $value )
        {
            $this->locked = $value;

            return $this;
        }

        return $this->locked;
    }

    public function omitted (?bool $value = null) : bool | self 
    {
        if ( $value )
        {
            $this->omitted = $value;

            return $this;
        }

        return $this->omitted;
    }

    public function perspective (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->perspective = $value;

            return $this;
        }

        return $this->perspective;
    }

    public function styles (?array $value = null) : array | self 
    {
        if ( $value )
        {
            $this->styles = array_merge ($this->styles, $value);

            return $this;
        }

        return $this->styles;
    }
}