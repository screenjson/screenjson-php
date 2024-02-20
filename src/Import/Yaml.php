<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\YamlInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

use ScreenJSON\Exceptions\InvalidFileFormatException;

class Yaml extends Importer implements YamlInterface, ImportInterface, ParserInterface
{
    public function __construct (
        protected string $file_path,
        protected string $ext = 'yml'
    ) {
        $this->file_path = $file_path;
        
        $this->validate ();
        $this->parse ();
    }

    public function parse () : self
    {

        return $this;
    }

    public function validate () : self 
    {
        if ( pathinfo ($this->file_path, PATHINFO_EXTENSION) != $this->ext )
        {
            throw new InvalidFileFormatException ("File extension must be ".$this->ext);
        }

        if (! file_exists ($this->file_path) )
        {
            throw new InvalidFileFormatException ("File does not exist.");
        }

        if (! is_readable ($this->file_path) )
        {
            throw new InvalidFileFormatException ("File is not readable.");
        }

        if (! filesize ($this->file_path) )
        {
            throw new InvalidFileFormatException ("File size is 0 bytes.");
        }

        if (! mime_content_type ($this->file_path) != 'text/yaml' )
        {
            throw new InvalidFileFormatException ("File does not have the correct mime type.");
        }

        if (! \yaml_parse_file ($this->file_path) )
        {
            throw new InvalidFileFormatException ("Could not load Yaml structure.");
        }

        return $this;
    }
}