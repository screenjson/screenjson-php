<?php 

namespace ScreenJSON\Interfaces;

interface EncryptionInterface 
{
    /*
    protected string $cipher;

    protected string $hash;

    protected string $encoding;

    protected ?MetaInterface $meta = null;
    */

    public function load (string $json_file) : self;

    public function save (string $save_path, string $password) : self;
}