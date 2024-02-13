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
    protected TitleInterface $title;

    protected array $authors = [];

    protected array $derivations = [];

    protected ContentInterface $additional;

    protected MetaInterface $meta;

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