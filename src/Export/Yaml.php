<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\{
    ExportInterface,
    ScreenplayInterface,
    YamlInterface
};

use \Exception;

class Yaml extends Exporter implements YamlInterface, ExportInterface
{
    public function convert () : self 
    {
        if (! function_exists ('yaml_emit') )
        {
            throw new Exception ("YAML extension is required. Function yaml_emit doesn't exist.");
        }

        $this->output = yaml_emit ($this->screenplay);

        return $this;
    }
}