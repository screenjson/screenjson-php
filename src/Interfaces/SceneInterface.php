<?php 

namespace ScreenJSON\Interfaces;

use Ramsey\Uuid\UuidInterface;
use Carbon\Carbon;

interface SceneInterface 
{
    protected UuidInterface $id;

    protected HeadingInterface $heading;

    protected array $body = [];

    protected array $animals = [];

    protected array $authors = [];

    protected array $cast = [];

    protected array $contributors = [];

    protected array $extra = [];

    protected array $locations = [];

    protected array $moods = [];

    protected array $props = [];

    protected array $sfx = [];

    protected array $sounds = [];

    protected array $tags = [];

    protected array $vfx = [];

    protected array $wardrobe = [];

    protected Carbon $created;

    protected Carbon $modified;

    protected MetaInterface $meta;
}