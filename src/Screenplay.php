<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\DocumentInterface;
use ScreenJSON\Interfaces\CoverInterface;
use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\FooterInterface;
use ScreenJSON\Interfaces\HeaderInterface;
use ScreenJSON\Interfaces\LicenseInterface;
use ScreenJSON\Interfaces\SceneInterface;
use ScreenJSON\Interfaces\ScreenplayInterface;
use ScreenJSON\Interfaces\TitleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Interfaces\ExportInterface;
use ScreenJSON\Interfaces\ImportInterface;

class Screenplay extends Common implements ScreenplayInterface, JsonSerializable
{
    public function __construct (
        protected ?TitleInterface $title = null,
        protected array $config = [],
        protected ?LicenseInterface $license = null,
        protected array $authors = [],
        protected ?UuidInterface $id = null,
        protected ?DocumentInterface $document = null,
        protected ?ExportInterface $exporter = null,
        protected ?ImportInterface $importer = null,
        protected string $guid = 'rfc4122',
        protected string $lang = 'en',
        protected string $locale = 'en_US',
        protected string $charset = 'utf8',
        protected string $dir = 'ltr',
        protected array $colors = [],
        protected array $derivations = [],
        protected array $registrations = [],
        protected array $revisions = [],
        protected array $taggable = [],
    ) {
        if (! $id )
        {
            $this->id = Uuid::uuid4();
        }

        if ( count ($config) )
        {
            foreach (['id', 'guid', 'lang', 'locale', 'charset', 'dir'] AS $assignable)
            {
                if ( isset ($config[$assignable]) )
                {
                    $this->{$assignable} = $config[$assignable];
                }
            }
        }

        $this->document = new Document;
    }

    private function defaults () : self 
    {
        if (! $this->title )
        {
            $this->title = new Document\Title ("Untitled Screenplay");
        }

        return $this;
    }

    public function cover (?CoverInterface $cover = null) : self | CoverInterface
    {
        if ( $cover )
        {
            $this->document->cover ($cover);

            return $this;
        }

        return $this->document?->cover;
    }

    public function footer (?FooterInterface $footer = null) : self | FooterInterface
    {
        if ( $footer )
        {
            $this->document->footer ($footer);

            return $this;
        }

        return $this->document?->footer;
    }

    public function header (?HeaderInterface $header = null) : self | HeaderInterface
    {
        if ( $header )
        {
            $this->document->header ($header);

            return $this;
        }

        return $this->document?->header;
    }

    public function jsonSerialize() : array
    {
        $this->defaults();

        return [
            'id'                => $this->id?->toString(),
            'guid'              => $this->guid,
            'title'             => $this->title,
            'lang'              => $this->lang,
            'locale'            => $this->locale,
            'charset'           => $this->charset,
            'dir'               => $this->dir,
            'authors'           => $this->authors,
            'colors'            => $this->colors,
            'contributors'      => $this->contributors,
            'derivations'       => $this->derivations,
            'document'          => $this->document,
            'encryption'        => $this->encryption,
            'license'           => $this->license,
            'registrations'     => $this->registrations,
            'revisions'         => $this->revisions,
            'taggable'          => $this->taggable,
        ];
    }

    public function scene (?SceneInterface $scene = null) : self | SceneInterface
    {
        if ( $scene )
        {
            $this->document->scenes ($scene);

            return $this;
        }

        return end ($this->document->scenes());
    }
}