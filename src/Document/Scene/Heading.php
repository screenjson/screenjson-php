<?php 

namespace ScreenJSON\Document\Scene;

use ScreenJSON\Interfaces\{
    ContentInterface,
    HeadingInterface
};

use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Cop;
use ScreenJSON\Meta;
use ScreenJSON\Surface;

use ScreenJSON\Enums\Context;
use ScreenJSON\Enums\Sequence;

class Heading extends Surface implements HeadingInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?ContentInterface $context = null,
        protected ?ContentInterface $setting = null,
        protected ?ContentInterface $sequence = null,
        protected ?int $numbering = null,
        protected ?int $page = null,
        protected ?ContentInterface $description = null
    ) {
        $this->cop = new Cop;

        if ($context)
        {
            $this->context ($context);
        }

        if ($setting)
        {
            $this->setting ($setting);
        }

        if ($sequence)
        {
            $this->sequence ($sequence);
        }

        if ($numbering)
        {
            $this->numbering ($numbering);
        }

        if ($page)
        {
            $this->page ($page);
        }

        if ($description)
        {
            $this->description ($description);
        }
    }

    public function context (?ContentInterface $content = null) : self | ContentInterface
    {
        if ($content)
        {
            //$this->cop->check ('Heading context', $content->first(), ['blank', 'alpha_dash', 'in'], ["I/E", "INT/EXT", "EXT/INT", "INT", "EXT", "POV"]);

            $this->context = $content;
            $this->context->upper();

            return $this;
        }

        return $this->context;
    }

    public function jsonSerialize() : array
    {
        return array_merge ([
            "numbering"     => $this->numbering,
            "context"       => $this->context,
            "setting"       => $this->setting,
            "sequence"      => $this->sequence,
            "description"   => $this->description,
        ], $this->meta?->all() ?? []);
    }

    public function description (?ContentInterface $value = null) : self | ContentInterface
    {
        if ( $value )
        {
            $this->description = $value;

            return $this;
        }

        return $this->description;
    }

    public function numbering (?int $value = null) : self | int
    {
        if ( $value )
        {
            $this->cop->check ('Heading numbering', $value, ['above_zero']);

            $this->numbering = $value;

            return $this;
        }

        return $this->numbering;
    }

    public function page (?int $value = null) : self | int
    {
        if ( $value )
        {
            $this->cop->check ('Heading page', $value, ['above_zero']);

            $this->page = $value;

            return $this;
        }

        return $this->page;
    }

    public function setting (?ContentInterface $content = null) : self | ContentInterface
    {
        if ( $content )
        {
            $this->setting = $content;
            $this->setting->upper();

            return $this;
        }

        return $this->setting;
    }

    public function sequence (?ContentInterface $content = null) : self | ContentInterface
    {
        if ($content)
        {
            //$this->cop->check ('Heading sequence', $content->first(), ['blank', 'alpha_dash', 'in'], ["DAY", "NIGHT", "DAWN", "DUSK", "LATER", "MOMENTS LATER", "CONTINUOUS", "MORNING", "AFTERNOON", "EVENING", "THE NEXT DAY"]);

            $this->sequence = $content;
            $this->sequence->upper();

            return $this;
        }

        return $this->sequence;
    }
}