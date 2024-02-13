<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\FinalDraftInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

class FinalDraft implements FinalDraftInterface, ImportInterface, ParserInterface
{
    public function __construct (protected string $file_path)
    {

    }
}