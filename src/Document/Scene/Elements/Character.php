<?php 

namespace ScreenJSON\Document\Scene\Elements;

use ScreenJSON\Document\Scene\Element;
use ScreenJSON\Interfaces\ElementInterface;

use ScreenJSON\Meta;
use ScreenJSON\Revision;
use ScreenJSON\Document\Scene\Content;

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Character extends Element implements ElementInterface, JsonSerializable
{
    public function __construct (
        protected string $type = Enums\Element::CHARACTER
    ) {
        parent::__construct ($type);
    }    

    public function jsonSerialize() : array
    {
        return [
            "type"          => $this->type,
            "perspective"   => $this->perspective,
            "interactivity" => $this->interactivity,
            "lang"          => $this->lang,
            "charset"       => $this->charset,
            "dir"           => $this->dir,
            "omitted"       => $this->omitted,
            "locked"        => $this->locked,
            "encrypted"     => $this->encrypted,
            "html"          => $this->html,
            "css"           => $this->css,

            "access"        => $this->access,
            "revision"      => $this->revision,
            "styles"        => $this->styles,
            "content"       => $this->content,
            "meta"          => $this->meta,
        ];
    }
}