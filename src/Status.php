<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\StatusInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Status implements StatusInterface, JsonSerializable
{
    public function __construct (
        protected string $color = 'white',
        protected int $round = 0,
        protected ?Carbon $updated = null,
        protected ?MetaInterface $meta = null,
    ) {
        if (! $updated )
        {
            $this->updated = Carbon::now();
        }
    }

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