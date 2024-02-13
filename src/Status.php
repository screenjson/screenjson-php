<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\StatusInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Status implements StatusInterface, JsonSerializable
{
    protected string $color;

    protected int $round;

    protected Carbon $updated;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'color'   => $this->color,
            'round'   => $this->round,
            'updated' => $this->updated?->format('c'),
            'meta'    => $this->meta,
        ];
    }
}