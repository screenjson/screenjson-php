<?php 

namespace ScreenJSON\Interfaces;

interface ColorInterface 
{
    protected string $title;

    protected array $rgb = [];

    protected string $hex;

    protected MetaInterface $meta;
}