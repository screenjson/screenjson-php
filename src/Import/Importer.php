<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Screenplay;

abstract class Importer
{
    protected Screenplay $screenplay;

    public function save (string $file_path) : int 
    {
        file_put_contents ($file_path, $this->screenplay->output());

        return filesize ($file_path);
    }
}