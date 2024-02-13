<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\Translatable;
use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Content implements ContentInterface, Translatable, JsonSerializable
{    
    protected array $translations = [
        Enums\Language::ENGLISH => ""
    ];

    public function add (string $lang, string $value) : string | self 
    {
        if ( $value )
        {
            $this->translations[$lang] = $value;

            return $this;
        }

        return $this->{$lang};
    }

    public function jsonSerialize() : array
    {
        return $this->translations;
    }
}