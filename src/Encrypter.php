<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncrypterInterface;

class Encrypter implements EncrypterInterface 
{
    public function __construct (protected string $file_path)
    {

    }

    public function save (protected string $file_path, string $password)
    {

    }
}