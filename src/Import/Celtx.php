<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\CeltxInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

class Celtx implements CeltxInterface, ImportInterface, ParserInterface
{
    public function __construct (protected string $file_path)
    {

    }
}