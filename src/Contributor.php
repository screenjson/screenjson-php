<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\ContributorInterface;

use \JsonSerializable;
use \Carbon\Carbon;

class Contributor extends Surface implements ContributorInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?string $given = null,
        protected ?string $family = null,
        protected array $roles = [],
        protected ?string $id = null,
    ) {
        $this->cop = new Cop;

        if ( $given )
        {
            $this->given ($given);
        }

        if ( $family )
        {
            $this->family ($family);
        }

        if ( $roles && count ($roles) > 0 )
        {
            $this->roles ($roles);
        }

        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }

    public function family (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Contributor family name', $value, ['blank', 'alpha_dash']);

            $this->family = mb_convert_case (trim ($value), MB_CASE_TITLE, "UTF-8");

            return $this;
        }

        return $this->family;
    }

    public function given (?string $value = null) : self | string 
    {
        if ($value)
        {
            $this->cop->check ('Contributor given name', $value, ['blank', 'alpha_dash']);

            $this->given = mb_convert_case (trim ($value), MB_CASE_TITLE, "UTF-8");

            return $this;
        }

        return $this->given;
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

    public function roles (?array $value = null) : self | array 
    {
        if ($value)
        {
            $this->cop->check ('Contributor roles', $value, ['array_slugs']);
            
            $this->roles = $value;

            return $this;
        }

        return $this->roles;
    }
}