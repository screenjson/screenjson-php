<?php 

namespace ScreenJSON\Interfaces;

interface StyleInterface 
{
    protected string $id;
    
    protected bool $default;

    protected string $content;

    protected MetaInterface $meta;
}