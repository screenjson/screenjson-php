<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncryptionInterface;

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Encryption implements EncryptionInterface, JsonSerializable
{
    public function __construct (
        protected string $cipher = Enums\Cipher::AES_256,
        protected string $hash = Enums\Hash::SHA256,
        protected string $encoding = Enums\Encoding::HEX
    ) {}

    public function ciphers () : array 
    {
        return openssl_get_cipher_methods();
    }

    public function encodings() : array 
    {
        return [
            'hex', 'base32', 'base64', 'ascii85'
        ];
    }

    public function hashes () : array 
    {
        return hash_hmac_algos();
    }

    public function jsonSerialize() : array
    {
        return [
            'cipher'    => $this->cipher,
            'hash'      => $this->hash,
            'encoding'  => $this->encoding,
        ];
    }
}