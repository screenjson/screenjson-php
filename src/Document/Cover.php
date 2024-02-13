<?php 

namespace ScreenJSON\Document;

use ScreenJSON\Interfaces\CoverInterface;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\TitleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Cover implements CoverInterface, JsonSerializable
{
    public function __construct (
        protected ?TitleInterface $title,
        protected ?ContentInterface $additional = null,
        protected array $derivations = [],
        protected array $authors = [],
        protected ?MetaInterface $meta = null
    ) {}
    
    public function jsonSerialize() : array
    {
        return [
            'title'       => $this->title,
            'authors'     => $this->authors,
            'derivations' => $this->derivations,
            'additional'  => $this->additional,
            'meta'        => $this->meta,
        ];
    }
}