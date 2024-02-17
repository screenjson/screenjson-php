<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\DecrypterInterface;

class Decrypter implements DecrypterInterface 
{
    public function __construct (
        protected ?string $file_path = null,
        protected ?string $password = null,
    )
    {

    }

    public function load (string $json_file) : self
    {

        return $this;
    }

    public function save (string $save_path, string $password) : self
    {
        
        return $this;
    }
}