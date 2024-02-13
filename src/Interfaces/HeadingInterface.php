<?php 

namespace ScreenJSON\Interfaces;

interface HeadingInterface 
{
    protected int $numbering;

    protected int $page;

    protected ContentInterface $context;

    protected ContentInterface $setting;

    protected ContentInterface $sequence;

    protected ContentInterface $description;

    protected MetaInterface $meta;
}