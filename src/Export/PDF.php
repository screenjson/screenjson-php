<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\PDFInterface;
use ScreenJSON\Interfaces\ExportInterface;

class PDF extends Exporter implements PDFInterface, ExportInterface
{
    public function convert () : self 
    {
        
        return $this;
    }
}