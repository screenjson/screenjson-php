<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncryptionInterface;

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Encryption implements EncryptionInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected string $cipher = Enums\Cipher::AES_256,
        protected string $hash = Enums\Hash::SHA256,
        protected string $encoding = Enums\Encoding::HEX
    ) {
        $this->cop = new Cop;

        if ($cipher)
        {
            $this->cipher ($cipher);
        }

        if ($encoding)
        {
            $this->encoding ($encoding);
        }

        if ($hash)
        {
            $this->hash ($hash);
        }
    }

    public function cipher (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Derivation type', $value, ['blank', 'alpha_dash', 'in'], openssl_get_cipher_methods());

            $this->cipher = trim ($value);

            return $this;
        }

        return $this->cipher;
    }

    public function ciphers () : array 
    {
        return openssl_get_cipher_methods();
    }

    public function encoding (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Derivation type', $value, ['blank', 'alpha_dash', 'in'], ['hex', 'base32', 'base64', 'ascii85']);

            $this->encoding = trim ($value);

            return $this;
        }

        return $this->encoding;
    }

    public function encodings() : array 
    {
        return [
            'hex', 'base32', 'base64', 'ascii85'
        ];
    }

    public function hash (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Derivation type', $value, ['blank', 'alpha_dash', 'in'], hash_hmac_algos());

            $this->hash = trim ($value);

            return $this;
        }

        return $this->hash;
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