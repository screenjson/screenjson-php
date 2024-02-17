<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\{
    Encryptable,
    TitleInterface,
    Translatable
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Title implements TitleInterface, Translatable, Encryptable, JsonSerializable
{    
    public function __construct (
        protected string|array $translations,
        protected ?string $lang = Enums\Language::ENGLISH,
    ) {
        if ( is_string ($translations) )
        {
            $str = $translations;
            $this->translations = [];
            $this->translations[$lang] = $str;
        }

        if ( is_array ($translations) )
        {
            $this->translations = $translations;
        }
    }

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