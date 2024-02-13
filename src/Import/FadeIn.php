<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\FadeInInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

class FadeIn implements FadeInInterface, ImportInterface, ParserInterface
{
    public function __construct (protected string $file_path)
    {

    }
}