<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\StyleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Style implements StyleInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?string $id = null,
        protected ?string $content = null,
        protected ?bool $default = null,
    ) {
        $this->cop = new Cop;

        if ($id)
        {
            $this->id ($id);
        }

        if ($content)
        {
            $this->content ($content);
        }

        if ($default)
        {
            $this->default ($default);
        }
    }

    public function content (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Style content', $value, ['blank']);

            $this->content = trim ($value);

            return $this;
        }

        return $this->content;
    }

    public function default (?bool $value = null) : self | bool 
    {
        if ($value)
        {
            $this->cop->check ('Style default', $value, ['bool_type', 'bool_val']);

            $this->default = $value;

            return $this;
        }

        return $this->default;
    }

    public function id (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Style content', $value, ['blank', 'alpha_dash']);

            $this->id = trim ($value);

            return $this;
        }

        return $this->id;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'id'      => $this->id,
            'default' => $this->default,
            'content' => $this->content,
        ], []);
    }
}