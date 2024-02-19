<?php 

namespace ScreenJSON\Interfaces;

interface ExportInterface 
{
    public function convert () : self;

    public function load (ScreenplayInterface $screenplay) : self;

    public function output () : string;
}