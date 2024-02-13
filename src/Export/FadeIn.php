<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\ExportInterface;
use ScreenJSON\Interfaces\FadeInInterface;

class FadeIn extends Exporter implements FadeInInterface, ExportInterface
{
    public function convert () : self 
    {
        
        return $this;
    }
}