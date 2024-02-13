<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\PDFInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

use Symfony\Component\Process\Process;

use ScreenJSON\Exceptions\InvalidFileFormatException;
use Ottosmops\Pdftotext\Exceptions\BinaryNotFound;

class PDF extends Importer implements PDFInterface, ImportInterface, ParserInterface
{
    public function __construct (
        protected string $file_path,
        protected string $ext = 'pdf'
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
        if (! pathinfo ($this->file_path, PATHINFO_EXTENSION) != $this->ext )
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

        if (! mime_content_type ($this->file_path) != 'application/pdf' )
        {
            throw new InvalidFileFormatException ("File does not have the correct mime type.");
        }

        // Check it's not password protected

        if ( stristr (file_get_contents($this->file_path), "/Encrypt") ) 
        {
            throw new InvalidFileFormatException ("File appears to be password-protected (encrypted).");
        }

        // Check we have pdftotext

        $process = Process::fromShellCommandline('which pdftotext');

        if (! $process->run()->isSuccessful() ) 
        {
            throw new BinaryNotFound ("Poppler Utils pdftotext binary is not installed or not available.");
        }

        return $this;
    }
}