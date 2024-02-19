<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Exceptions\InvalidSaveLocationException;

abstract class Exporter
{
    private string $output;

    private object $screenplay;

    public function load (object $screenplay) : self 
    {
        $this->screenplay = $screenplay;

        return $this;
    }

    public function output () : string
    {
        return $this->output;
    }

    public function saveable () : self
    {
        if (! is_writable ($this->file_path) )
        {
            throw new InvalidSaveLocationException;
        }

        return $this;
    }
}