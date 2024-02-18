<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\StatusInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Status extends Surface implements StatusInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected string $color = 'white',
        protected int $round = 0,
        protected ?Carbon $updated = null,
    ) {
        $this->cop = new Cop;

        if ($color)
        {
            $this->color ($color);
        }

        if ($round)
        {
            $this->round ($round);
        }

        if ($updated)
        {
            $this->updated ($updated);
        }

        if (! $updated )
        {
            $this->updated = Carbon::now();
        }
    }

    public function color (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Status color', $value, ['blank', 'alpha_dash', 'in', ["white", "blue", "pink", "yellow", "green", "goldenrod", "buff", "salmon", "cherry"]]);

            $this->color = trim ($value);

            return $this;
        }

        return $this->color;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'color'   => $this->color,
            'round'   => $this->round,
            'updated' => $this->updated?->format('c'),
        ], $this->meta?->all() ?? []);
    }

    public function round (?int $value = null) : self | int 
    {
        if ($value)
        {
            $this->cop->check ('Status round', $value, ['above_zero']);

            $this->round = $value;

            return $this;
        }

        return $this->round;
    }

    public function updated (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->cop->check ('Status updated', $value, ['future']);

            $this->updated = $value;

            return $this;
        }

        return $this->updated;
    }
}