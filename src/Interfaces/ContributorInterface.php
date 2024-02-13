<?php 

namespace ScreenJSON\Interfaces;

use Ramsey\Uuid\UuidInterface;
use Carbon\Carbon;

interface ContributorInterface 
{
    protected UuidInterface $id;

    protected string $given;

    protected string $family;

    protected array $roles;

    protected MetaInterface $meta;
}