<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ColorInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Color extends Surface implements ColorInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?string $title = null,
        protected array $rgb = [],
        protected ?string $hex = null,
    ) {
        $this->cop = new Cop;

        if ($title)
        {
            $this->title ($title);
        }

        if ($rgb)
        {
            $this->rgb ($rgb);
        }

        if ($hex) 
        {
            $this->hex ($hex);
        }
    }    

    public function hex (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Color hex', $value, ['blank', 'hex']);

            $this->hex = trim ($value);

            return $this;
        }

        return $this->hex;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'title' => $this->title,
            'rgb'   => $this->rgb,
            'hex'   => '#' . $this->hex
        ], $this->meta?->all() ?? []);
    }

    public function rgb (?array $value = null) : self | array 
    {
        if ($value)
        {
            $this->cop->check ('Color RGB', $value, ['array_rgb']);

            $this->rgb = $value;

            return $this;
        }

        return $this->rgb;
    }

    public function title (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Color title', $value, ['blank', 'alpha_dash']);

            $this->title = trim ($value);

            return $this;
        }

        return $this->title;
    }
}