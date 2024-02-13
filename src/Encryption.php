<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Encryption implements EncryptionInterface, JsonSerializable
{
    public function __construct (
        protected string $cipher = Enums\Cipher::AES_256,
        protected string $hash = Enums\Hash::SHA256,
        protected string $encoding = Enums\Encoding::HEX,
        protected ?MetaInterface $meta = null,
    ) {}

    public function jsonSerialize() : array
    {
        return [
            'cipher'    => $this->cipher,
            'hash'      => $this->hash,
            'encoding'  => $this->encoding,
        ];
    }
}