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
    protected int $numbering;

    protected int $page;

    protected ContentInterface $context;

    protected ContentInterface $setting;

    protected ContentInterface $sequence;

    protected ContentInterface $description;

    protected MetaInterface $meta;

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