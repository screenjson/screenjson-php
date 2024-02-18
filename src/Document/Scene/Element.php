<?php 

namespace ScreenJSON\Document\Scene;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\ContentInterface;

use ScreenJSON\Cop;
use ScreenJSON\Surface;
use ScreenJSON\Enums;

abstract class Element extends Surface
{
    protected Cop $cop;

    protected ?ContentInterface $content = null;
    protected ?array $config = [];
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
    protected ?array $authors = null;
    protected ?string $id = null;
    protected ?string $parent = null;

    public function __apply_config_map (array $config) : self 
    {
        $this->cop = new Cop;

        foreach ($config AS $key => $val)
        {
            switch ($key)
            {
                case 'access':
                case 'styles':
                    $this->cop->check ($key, $val, ['array_slugs']);
                break;

                case 'authors':
                    $this->cop->check ($key, $val, ['array_uuids']);
                break;

                case 'charset':
                case 'css':
                case 'dir':
                case 'dom':
                    $this->cop->check ($key, $val, ['blank', 'alpha_dash']);
                break;

                case 'fov':
                    $this->cop->check ($key, $val, ['above_zero', 'degrees']);
                break;

                case 'interactivity':
                case 'omitted':
                case 'locked':
                    $this->cop->check ($key, $val, ['bool_type', 'bool_val']);
                break;

                case 'lang':
                    $this->cop->check ($key, mb_strtoupper($val), ['blank', 'alpha_dash', 'lang']);
                break;

                case 'perspective':
                    $this->cop->check ($key, $val, ['blank', 'alpha_dash', 'in'], ['2D', '3D']);
                break;
            }

            $this->{$key} = $val;
        }

        return $this;
    }

    public function __build (array $params = []) : array 
    {
        $data = array_merge ([
            'id'        => is_string ($this->id) ? $this->id : $this->id?->toString(),
            'parent'    => $this->parent,
        ], 
        $params,
        [
            "authors"       => $this->authors,
            "annotations"   => $this->annotations,
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
            if ( is_null ($v) )
            {
                unset ($data[$k]);
            }

            if ( is_array ($v) && count ($v) == 0 )
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
            $this->cop->check ('CSS', $value, ['alpha_dash']);

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
            $this->cop->check ('Interactivity', $value, ['bool_type', 'bool_val']);

            $this->interactivity = $value;

            return $this;
        }

        return $this->interactivity;
    }

    public function locked (?bool $value = null) : bool | self 
    {
        if ( $value )
        {
            $this->cop->check ('Locked', $value, ['bool_type', 'bool_val']);

            $this->locked = $value;

            return $this;
        }

        return $this->locked;
    }

    public function omitted (?bool $value = null) : bool | self 
    {
        if ( $value )
        {
            $this->cop->check ('Omitted', $value, ['bool_type', 'bool_val']);

            $this->omitted = $value;

            return $this;
        }

        return $this->omitted;
    }

    public function perspective (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->cop->check ('Perspective', $value, ['alpha_dash', 'in'], ['2D', '3D']);

            $this->perspective = $value;

            return $this;
        }

        return $this->perspective;
    }

    public function styles (?array $value = null) : array | self 
    {
        if ( $value )
        {
            $this->cop->check ('Styles', $value, ['array_slugs']);

            $this->styles = array_merge ($this->styles, $value);

            return $this;
        }

        return $this->styles;
    }
}