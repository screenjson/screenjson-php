<?php 

namespace ScreenJSON\Interfaces;

interface DecrypterInterface 
{
    public function decrypt (): string;

    public function load (string $json_file) : self;

    public function save (string $save_path, string $password) : self;
}