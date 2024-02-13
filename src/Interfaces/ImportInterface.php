<?php 

namespace ScreenJSON\Interfaces;

interface ImportInterface 
{
    protected string $ext;

    public function parse () : self

    public function validate () : self 
}