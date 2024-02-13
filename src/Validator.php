<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ValidatorInterface;

class Validator implements ValidatorInterface 
{
    protected string $schema;
    
    protected array $errors = [];

    public function errors () : array
    {
        return $this->errors;
    }

    public function fails () : bool
    {
        return count ($this->errors) > 0;
    }

    public function passes () : bool
    {
        return count ($this->errors) == 0;
    }

    public function validate () : self
    {

        return $this;
    }
}