<?php 

namespace ScreenJSON\Document\Scene;

use ScreenJSON\Interfaces\HeadingInterface;
use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Meta;

use ScreenJSON\Enums\Context;
use ScreenJSON\Enums\Sequence;

class Heading implements HeadingInterface, JsonSerializable
{
    protected int $numbering;

    protected string $context;

    protected string $setting;

    protected string $sequence;

    protected string $description;

    protected ?Meta $meta;

    public function jsonSerialize() : array
    {
        return [
            "numbering"     => $this->numbering,
            "context"       => $this->context,
            "setting"       => $this->setting,
            "sequence"      => $this->sequence,
            "description"   => $this->description,
            "meta"          => $this->meta,
        ];
    }

    public function description (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->description = $value;

            return $this;
        }

        return $this->description;
    }

    public function setting (?string $value = null) : string | self 
    {
        if ( $value )
        {
            $this->setting = $value;

            return $this;
        }

        return $this->setting;
    }
}