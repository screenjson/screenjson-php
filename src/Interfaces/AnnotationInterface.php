<?php 

namespace ScreenJSON\Interfaces;

use Ramsey\Uuid\UuidInterface;
use Carbon\Carbon;

interface AnnotationInterface 
{
    protected UuidInterface $id;

    protected ?string $parent;

    protected array $highlight = [];

    protected ?string $contributor;

    protected ?Carbon $created;

    protected ContentInterface $content;

    protected ?string $color;

    protected MetaInterface $meta;
}