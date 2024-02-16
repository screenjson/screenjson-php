<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\ValidatorInterface;

use ScreenJSON\Exceptions\InvalidFileFormatException;
use ScreenJSON\Exceptions\NonExistentInputFileException;
use ScreenJSON\Exceptions\UnreadableInputFileException;
use ScreenJSON\Exceptions\ZeroSizeInputFileException;

use Opis\JsonSchema\{
    Validator AS Engine,
    Errors\ErrorFormatter,
    Errors\ValidationError,
};

use \Exception;

class Validator implements ValidatorInterface 
{
    public function __construct(
        protected string $json_file,
        protected string $schema = '/../resources/data/schema.json',
        protected array $errors = [],
    ) {
        if (! file_exists (dirname(__FILE__) . $this->schema) || !is_readable (dirname(__FILE__) . $this->schema) || !filesize (dirname(__FILE__) . $this->schema) )
        {
            throw new Exception ("Can't find internal JSON Schema reference file. Aborting.");
        }

        if (! file_exists ($json_file) )
        {
            throw new NonExistentInputFileException ("File does not exist on disk (" . $json_file . ")");
        }

        if (! is_readable ($json_file) )
        {
            throw new UnreadableInputFileException ("Can't read file (".$json_file.")");
        }

        if (! filesize ($json_file) )
        {
            throw new ZeroSizeInputFileException ("File exists but is empty and zero bytes.");
        }

        if ( mime_content_type ($json_file) != 'application/json' )
        {
            throw new InvalidFileFormatException ("Mime type of the file is not application/json.");
        }

        if (! json_decode ( file_get_contents ($json_file) ) )
        {
            throw new InvalidFileFormatException ("File is not valid JSON.");
        }

        $this->json_file = $json_file;

        $this->validate ();
    }

    public function errors () : array
    {
        return $this->errors;
    }

    public function fails () : bool
    {
        return count ($this->errors) > 0;
    }

    public function passes () : bool
    {
        return count ($this->errors) == 0;
    }

    private function validate () : self
    {
        $validator = new Engine();

        $validator->resolver()->registerRaw (
            file_get_contents (dirname(__FILE__) . $this->schema)
        );

        $result = $validator->setMaxErrors(10)->validate (
            json_decode ( file_get_contents ($this->json_file) ),
            'https://screenjson.com/schema.json'
        );

        if (! $result->isValid() ) 
        {
            $formatter = new ErrorFormatter();

            $builder = function (ValidationError $error, ?array $subErrors = null) use ($formatter) 
            {
                $ret = [
                    'keyword' => $error->keyword(),
                    'message' => $formatter->formatErrorMessage($error),
                ];
            
                if ($subErrors) {
                    $ret['subErrors'] = $subErrors;
                }
            
                return $ret;
            };

            $this->errors = $formatter->formatNested ($result->error(), $builder);
        } 

        return $this;
    }
}