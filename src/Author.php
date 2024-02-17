<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\AuthorInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Author extends Surface implements AuthorInterface, JsonSerializable
{
    public function __construct (
        protected ?string $given = null,
        protected ?string $family = null,
        protected array $roles = [],
        protected ?string $id = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            'id'        => is_string ($this->id) ? $this->id : $this->id?->toString(),
            'given'     => mb_convert_case ($this->given, MB_CASE_TITLE, "UTF-8"),
            'family'    => mb_convert_case ($this->family, MB_CASE_TITLE, "UTF-8"),
            'roles'     => $this->roles,
        ], $this->meta?->all() ?? []);
    }
}