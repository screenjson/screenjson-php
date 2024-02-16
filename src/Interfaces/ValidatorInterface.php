<?php 

namespace ScreenJSON\Interfaces;

interface ValidatorInterface 
{
    /*
    protected string $schema;
    
    protected array $errors = [];
    */

    public function examine (string $json_file) : self;
    
    public function errors () : array;

    public function fails () : bool;

    public function passes () : bool;
}