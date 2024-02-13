<?php 

namespace ScreenJSON\Interfaces;

interface CoverInterface 
{
    protected TitleInterface $title;

    protected array $authors = [];

    protected array $derivations = [];

    protected ContentInterface $additional;

    protected MetaInterface $meta;
}