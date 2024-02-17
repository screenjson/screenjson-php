<?php 

namespace ScreenJSON\Document\Scene;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\ContentInterface;

use ScreenJSON\Surface;
use ScreenJSON\Enums;

abstract class Element extends Surface
{
    protected ?ContentInterface $content = null;
    protected ?UuidInterface $id = null;
    protected ?UuidInterface $parent = null;
    protected ?string $lang = null;
    protected ?string $charset = null;
    protected ?string $dir = null;
    protected ?string $perspective = null;
    protected ?bool $interactivity = null;
    protected ?int $fov = null;
    protected ?bool $omitted = null;
    protected ?bool $locked = null;
    protected ?string $dom = null;
    protected ?string $css = null;
    protected ?array $access = null;
    protected ?array $styles = null;

    public function __build (array $params = []) : array 
    {
        $data = array_merge ([
            'id'        => $this->id?->toString(),
            'parent'    => $this->parent?->toString(),
        ], 
        $params,
        [
            "content"       => $this->content,
            "contributors"  => $this->contributors,
            "encryption"    => $this->encryption,
            "perspective"   => $this->perspective,
            "interactivity" => $this->interactivity,
            'fov'           => $this->fov,
            "lang"          => $this->lang,
            "charset"       => $this->charset,
            "dir"           => $this->dir,
            "omitted"       => $this->omitted,
            "locked"        => $this->locked,
            "dom"           => $this->dom,
            "css"           => $this->css,
            "access"        => $this->access,
            "revisions"     => $this->revisions,
            "styles"        => $this->styles,
            "meta"          => $this->meta,
        ]);

        foreach ($data AS $k => $v)
        {
            if (! $v || is_null ($v) )
            {
                unset ($data[$k]);
            }
        }

        return $data;
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

    public function defaults () : self 
    {
        $this->contributors ?: [];
        $this->revisions ?: [];
        $this->lang = Enums\Language::ENGLISH;
        $this->charset = Enums\Charset::UTF8;
        $this->dir = Enums\Direction::LTR;
        $this->perspective = Enums\Perspective::TWO_D;
        $this->interactivity = false;
        $this->fov = 40;
        $this->omitted = false;
        $this->locked = false;
        $this->dom = "p";
        $this->css = "col-md-12";
        $this->access = ['author', 'contributor', 'editor'];
        $this->styles = [];

        return $this;
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