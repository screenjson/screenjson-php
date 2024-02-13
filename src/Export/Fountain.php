<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\FountainInterface;
use ScreenJSON\Interfaces\ExportInterface;

class Fountain extends Exporter implements FountainInterface, ExportInterface
{
    public function convert () : self 
    {
        
        return $this;
    }
}