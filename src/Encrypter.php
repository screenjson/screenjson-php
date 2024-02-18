<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncrypterInterface;


class Encrypter implements EncrypterInterface 
{
    protected Cop $cop;

    public function __construct (
        protected ?string $file_path = null,
        protected ?string $password = null,
    )
    {
        $this->cop = new Cop;
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