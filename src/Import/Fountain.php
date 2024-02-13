<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\FountainInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

class Fountain implements FountainInterface, ImportInterface, ParserInterface
{
    public function __construct (protected string $file_path)
    {

    }
}