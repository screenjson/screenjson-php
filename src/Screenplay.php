<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use ScreenJSON\Interfaces\DocumentInterface;
use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\LicenseInterface;
use ScreenJSON\Interfaces\ScreenplayInterface;
use ScreenJSON\Interfaces\TitleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

use ScreenJSON\Interfaces\ExportInterface;
use ScreenJSON\Interfaces\ImportInterface;

class Screenplay implements ScreenplayInterface, JsonSerializable
{
    public function __construct (
        protected ?TitleInterface $title = null,
        protected array $config = [],
        protected ?LicenseInterface $license = null,
        protected ?EncryptionInterface $encryption = null,
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
        protected array $contributors = [],
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
    }

    private function __defaults () : self 
    {
        if (! $this->title )
        {
            $this->title = new Document\Title;
        }

        return $this;
    }

    public function jsonSerialize() : array
    {
        $this->__defaults();

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
}