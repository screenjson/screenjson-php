<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\{
    ContentInterface,
    Encryptable,
    Translatable
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Content implements ContentInterface, Translatable, Encryptable, JsonSerializable
{   
    protected Cop $cop;

    public function __construct (
        protected string|array $translations,
        protected ?string $lang = Enums\Language::ENGLISH,
        protected ?string $casing = null
    ) {
        $this->cop = new Cop;

        if ( $casing )
        {
            $this->cop->check ('Casing', $casing, ['blank', 'alpha_dash', 'in'], ['lower', 'upper', 'title']);
        }

        if ( is_string ($translations) )
        {
            $this->cop->check ('Content', $translations, ['blank']);

            $str = $translations;
            $this->translations = [];

            if ($casing)
            {
                switch ($casing)
                {
                    case 'lower':
                        $this->translations[$lang] = mb_convert_case (trim ($str), MB_CASE_LOWER, "UTF-8");
                    break;
                    
                    case 'upper':
                        $this->translations[$lang] = mb_convert_case (trim ($str), MB_CASE_UPPER, "UTF-8");
                    break;

                    case 'title':
                        $this->translations[$lang] = mb_convert_case (trim ($str), MB_CASE_TITLE, "UTF-8");
                    break;
                }
            }
            else 
            {
                $this->translations[$this->lang ?? Enums\Language::ENGLISH] = trim ($str);
            }
        }

        if ( is_array ($translations) )
        {
            $this->translations = $translations;
        }
    }

    public function first (?string $lang = null) : string | array 
    {
        if ($lang)
        {
            if ( isset ($translations[$lang] ))
            {
                return $translations[$lang];
            }
        }

        return $this->translations[0] ?? '';
    }

    public function jsonSerialize() : array
    {
        return $this->translations;
    }

    public function titling () : self 
    {
        foreach ($this->translations AS $lang => $text)
        {
            $this->translations[$lang] = mb_convert_case (trim ($text), MB_CASE_TITLE, "UTF-8");
        }

        return $this;
    }

    public function translations (?string $lang = null) : string | array 
    {
        if ($lang)
        {
            if ( isset ($translations[$lang] ))
            {
                return $translations[$lang];
            }
        }

        return $this->translations;
    }

    public function upper () : self 
    {
        foreach ($this->translations AS $lang => $text)
        {
            $this->translations[$lang] = mb_convert_case (trim ($text), MB_CASE_UPPER, "UTF-8");
        }

        return $this;
    }
}