<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\CeltxInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

use ScreenJSON\Exceptions\InvalidFileFormatException;

class Celtx extends Importer implements CeltxInterface, ImportInterface, ParserInterface
{
    

    public function __construct (
        protected string $file_path,
        protected string $ext = 'celtx'
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

        if (! mime_content_type ($this->file_path) != 'application/zip' )
        {
            throw new InvalidFileFormatException ("File does not have the correct mime type.");
        }

        if (! is_resource ($zip = zip_open ($this->file_path)) )
        {
            throw new InvalidFileFormatException ("Could not open file as a system resource.");
        }

        // Check the zip has the correct RDF files
        $zip = new \ZipArchive();

        if( $zip->open ($this->file_path) === TRUE )
        {
            if (!$local_rdf =  $zip->getFromName('local.rdf') )
            {
                throw new InvalidFileFormatException ("Unable to find local.rdf in file.");
            }

            if (! simplexml_load_string ($local_rdf) )
            {
                throw new InvalidFileFormatException ("Unable to read local.rdf XML structure.");
            }

            if (! $project_rdf = $zip->getFromName('project.rdf') )
            {
                throw new InvalidFileFormatException ("Unable to find project.rdf in file.");
            }

            if (! simplexml_load_string ($project_rdf) )
            {
                throw new InvalidFileFormatException ("Unable to read project.rdf XML structure.");
            }

            $zip->close();
        }

        return $this;
    }
}