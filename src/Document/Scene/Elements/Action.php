<?php 

namespace ScreenJSON\Document\Scene\Elements;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Document\Scene\Element;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\ElementInterface;
use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\MetaInterface;

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Enums;

class Action extends Element implements ElementInterface, JsonSerializable
{
    public function __construct (
        protected ?ContentInterface $content = null,
        protected ?UuidInterface $id = null,
        protected ?UuidInterface $parent = null,
        protected ?EncryptionInterface $encryption = null,
        protected array $contributors = [],
        protected array $revisions = [],
        protected ?MetaInterface $meta = null,
        protected string $lang = Enums\Language::ENGLISH,
        protected string $charset = Enums\Charset::UTF8,
        protected string $dir = Enums\Direction::LTR,
        protected string $perspective = Enums\Perspective::TWO_D,
        protected bool $interactivity = false,
        protected int $fov = 40,
        protected bool $omitted = false,
        protected bool $locked = false,
        protected string $dom = "p",
        protected string $css = "col-md-12",
        protected array $access = ['author', 'contributor', 'editor'],
        protected array $styles = [],
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }
    }    

    public function jsonSerialize() : array
    {
        return [
            'id'            => $this->id?->toString(),
            'parent'        => $this->parent?->toString(),
            "type"          => Enums\Element::ACTION,
            "perspective"   => $this->perspective,
            "interactivity" => $this->interactivity,
            'fov'           => $this->fov,
            "lang"          => $this->lang,
            "charset"       => $this->charset,
            "dir"           => $this->dir,
            "omitted"       => $this->omitted,
            "locked"        => $this->locked,
            "encryption"    => $this->encryption,
            "dom"           => $this->dom,
            "css"           => $this->css,
            'contributors'  => $this->contributors,
            "access"        => $this->access,
            "revisions"     => $this->revisions,
            "styles"        => $this->styles,
            "content"       => $this->content,
        ];
    }
}