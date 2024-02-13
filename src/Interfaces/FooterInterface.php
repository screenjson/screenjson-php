<?php 

namespace ScreenJSON\Interfaces;

interface FooterInterface 
{
    protected bool $cover;

    protected bool $display;

    protected int $start;

    protected array $omit = [];

    protected ContentInterface $content;

    protected MetaInterface $meta;
}