<?php 

namespace ScreenJSON\Document\Scene;

use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\HeadingInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\Encryptable;
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
        protected ?ContentInterface $description = null,
        protected ?MetaInterface $meta = null,
    ) {}

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