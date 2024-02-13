<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\ContributorInterface;
use ScreenJSON\Interfaces\MetaInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Contributor implements ContributorInterface, JsonSerializable
{
    public function __construct (
        protected ?string $given = null,
        protected ?string $family = null,
        protected ?UuidInterface $id = null,
        protected array $roles = [],
        protected ?MetaInterface $meta = null,
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function jsonSerialize() : array
    {
        return [
            'id'        => $this->id?->toString(),
            'given'     => mb_convert_case ($this->given, MB_CASE_TITLE, "UTF-8"),
            'family'    => mb_convert_case ($this->family, MB_CASE_TITLE, "UTF-8"),
            'roles'     => $this->roles,
            'meta'      => $this->meta,
        ];
    }
}