<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\AuthorInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Author implements AuthorInterface, JsonSerializable
{
    protected UuidInterface $id;

    protected string $given;

    protected string $family;

    protected array $roles;

    protected MetaInterface $meta;

    public function jsonSerialize() : array
    {
        return [
            'id'        => $this->id->toString(),
            'given'     => mb_convert_case ($this->given, MB_CASE_TITLE, "UTF-8"),
            'family'    => mb_convert_case ($this->family, MB_CASE_TITLE, "UTF-8"),
            'roles'     => $this->roles,
            'meta'      => $this->meta,
        ];
    }
}