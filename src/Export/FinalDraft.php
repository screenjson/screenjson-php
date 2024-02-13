<?php 

namespace ScreenJSON\Export;

use ScreenJSON\Interfaces\ExportInterface;
use ScreenJSON\Interfaces\FinalDraftInterface;

class FinalDraft extends Exporter implements FinalDraftInterface, ExportInterface
{
    public function convert () : self 
    {
        
        return $this;
    }
}