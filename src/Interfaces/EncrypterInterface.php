<?php 

namespace ScreenJSON\Interfaces;

interface EncrypterInterface 
{
    public function load (string $json_file) : self;
    
    public function save (string $save_path, string $password) : int;
}