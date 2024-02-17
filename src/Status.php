<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\StatusInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Status extends Surface implements StatusInterface, JsonSerializable
{
    public function __construct (
        protected string $color = 'white',
        protected int $round = 0,
        protected ?Carbon $updated = null,
    ) {
        if (! $updated )
        {
            $this->updated = Carbon::now();
        }
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'color'   => $this->color,
            'round'   => $this->round,
            'updated' => $this->updated?->format('c'),
        ], $this->meta?->all() ?? []);
    }
}