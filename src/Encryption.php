<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Encryption implements EncryptionInterface, JsonSerializable
{
    protected string $cipher;

    protected string $hash;

    protected string $encoding;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'cipher'    => $this->cipher,
            'hash'      => $this->hash,
            'encoding'  => $this->encoding,
        ];
    }
}