<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Exceptions\InvalidSaveLocationException;

abstract class Exporter
{
    public function saveable () : self
    {
        if (! is_writable ($this->file_path) )
        {
            throw new InvalidSaveLocationException;
        }

        return $this;
    }
}