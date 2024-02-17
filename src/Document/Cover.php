<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\{
    CoverInterface,
    ContentInterface,
    TitleInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Surface;

class Cover extends Surface implements CoverInterface, JsonSerializable
{
    public function __construct (
        protected ?TitleInterface $title,
        protected ?ContentInterface $additional = null,
        protected array $derivations = [],
        protected array $authors = [],
    ) {}
    
    public function jsonSerialize() : array
    {
        return array_merge ([
            'title'       => $this->title,
            'authors'     => $this->authors,
            'derivations' => $this->derivations,
            'additional'  => $this->additional,
        ], $this->meta?->all() ?? []);
    }
}