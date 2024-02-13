<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\StyleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Style implements StyleInterface, JsonSerializable
{
    protected string $id;
    
    protected bool $default;

    protected string $content;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'id'      => $this->id,
            'default' => $this->default,
            'content' => $this->content,
            'meta'    => $this->meta,
        ];
    }
}