<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\{
    CoverInterface,
    ContentInterface,
    TitleInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Cop;
use ScreenJSON\Surface;

class Cover extends Surface implements CoverInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?TitleInterface $title,
        protected ?ContentInterface $additional = null,
        protected array $derivations = [],
        protected array $authors = [],
    ) {
        $this->cop = new Cop;

        if ($title)
        {
            $this->title ($title);
        }

        if ($additional)
        {
            $this->additional ($additional);
        }
    }
    
    public function additional (?ContentInterface $text = null) : self | ContentInterface
    {
        if ( $text )
        {
            $this->cop->check ('Additional cover text', $text, ['blank']);

            $this->additional = $text;

            return $this;
        }

        return $this->additional;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'title'       => $this->title,
            'authors'     => $this->authors,
            'derivations' => $this->derivations,
            'additional'  => $this->additional,
        ], $this->meta?->all() ?? []);
    }

    public function title (?TitleInterface $title = null) : self | TitleInterface
    {
        if ( $title )
        {
            $this->cop->check ('Cover title', $title, ['blank']);

            $this->title = $title;

            return $this;
        }

        return $this->title;
    }
}