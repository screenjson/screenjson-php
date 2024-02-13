<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\DecrypterInterface;

class Decrypter implements DecrypterInterface 
{
    public function __construct (protected string $file_path, string $password)
    {

    }
}