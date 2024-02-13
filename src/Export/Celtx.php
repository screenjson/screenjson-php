<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\ExportInterface;
use ScreenJSON\Interfaces\CeltxInterface;

class Celtx extends Exporter implements CeltxInterface, ExportInterface
{
    public function convert () : self 
    {
        
        return $this;
    }
}