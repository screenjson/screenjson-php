<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\YamlInterface;
use ScreenJSON\Interfaces\ExportInterface;

class Yaml extends Exporter implements YamlInterface, ExportInterface
{
    public function convert () : self 
    {
        
        return $this;
    }
}