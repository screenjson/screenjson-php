<?php 

namespace ScreenJSON\Document\Scene;

use ScreenJSON\Interfaces\{
    ContentInterface,
    HeadingInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Meta;

use ScreenJSON\Enums\Context;
use ScreenJSON\Enums\Sequence;

class Heading implements HeadingInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $context = null,
        protected ?ContentInterface $setting = null,
        protected ?ContentInterface $sequence = null,
        protected ?int $numbering = null,
        protected ?int $page = null,
        protected ?ContentInterface $description = null
    ) {}

    public function jsonSerialize() : array
    {
        return array_merge ([
            "numbering"     => $this->numbering,
            "context"       => $this->context,
            "setting"       => $this->setting,
            "sequence"      => $this->sequence,
            "description"   => $this->description,
        ], $this->meta?->all() ?? []);
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