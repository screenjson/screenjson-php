<?php 

namespace ScreenJSON\Document\Scene\Elements;

use ScreenJSON\Document\Scene\Element;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\ElementInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RevisionInterface;

use ScreenJSON\Meta;
use ScreenJSON\Revision;
use ScreenJSON\Document\Scene\Content;

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Transition extends Element implements ElementInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
        protected string $perspective = Enums\Perspective::TWO_D,
        protected bool $interactivity = false,
        protected string $lang = Enums\Language::ENGLISH,
        protected string $charset = Enums\Charset::UTF8,
        protected string $dir = Enums\Direction::LTR,
        protected bool $omitted = false,
        protected bool $locked = false,
        protected bool $encrypted = false,
        protected string $html = "p",
        protected string $css = "col-md-12",
        protected array $access = [],
        protected ?RevisionInterface $revision = null,
        protected array $styles = [],
        protected ?MetaInterface $meta = null,
    ) {}       

    public function jsonSerialize() : array
    {
        return [
            "type"          => Enums\Element::TRANSITION,
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