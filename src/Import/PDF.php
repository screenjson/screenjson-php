<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\PDFInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

class PDF implements PDFInterface, ImportInterface, ParserInterface
{
    public function __construct (protected string $file_path)
    {

    }
}