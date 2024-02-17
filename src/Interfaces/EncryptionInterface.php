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

    public function ciphers () : array;

    public function encodings() : array;

    public function hashes () : array;
}