<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    AnnotationInterface,
    ContentInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

class Annotation extends Surface implements AnnotationInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?ContentInterface $content = null,
        protected ?string $contributor,
        protected ?string $color,
        protected ?Carbon $created,
        protected array $highlight = [],
        protected ?string $id = null,
        protected ?string $parent = null,
    ) {
        $this->cop = new Cop;

        if ($color)
        {
            $this->color ($color);
        }

        if ($created)
        {
            $this->created ($created);
        }
        else 
        {
            $this->created = Carbon::now();
        }

        if ($highlight)
        {
            $this->highlight ($highlight[0] ?? null, $highlight[1] ?? null);
        }
        
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function color (?string $value) : self | string
    {
        if ($value)
        {
            $this->cop->check ('Annotation color', $value, ['blank', 'alpha_dash']);

            $this->color = $value;

            return $this;
        }

        return $this->color;
    }

    public function created (?Carbon $value = null) : self | Carbon 
    {
        if ($value)
        {
            $this->created = $value;

            return $this;
        }

        return $this->created;
    }

    public function highlight (?int $start = null, ?int $end = null) : self | array
    {
        $this->highlight = [];

        if ($start)
        {
            $this->cop->check ('Annotation highlight start', $start, ['above_zero']);

            $this->highlight[] = $start;

            if ($end)
            {
                $this->cop->check ('Annotation highlight end', $end, ['above_zero']);

                $this->highlight[] = $end;
            }

            return $this;
        }

        return $this->highlight;
    }

    public function jsonSerialize() : array
    {
        return array_merge([
            'id'            => is_string ($this->id) ? $this->id : $this->id?->toString(),
            'parent'        => $this->parent?->toString(),
            'highlight'     => $this->highlight,
            'contributor'   => $this->contributor,
            'content'       => $this->content,
            'color'         => $this->color,
            'created'       => $this->created?->format ('c'),
        ], $this->meta?->all() ?? []);
    }
}