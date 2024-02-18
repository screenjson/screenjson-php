<?php 

namespace ScreenJSON;

use Ramsey\Uuid\{
    UuidInterface,
    Uuid
};

use ScreenJSON\Interfaces\{
    AuthorInterface,
    BookmarkInterface,
    ColorInterface,
    DerivationInterface,
    DocumentInterface,
    ExportInterface,
    CoverInterface,
    FooterInterface,
    HeaderInterface,
    ImportInterface,
    LicenseInterface,
    RegistrationInterface,
    SceneInterface,
    ScreenplayInterface,
    StatusInterface,
    StyleInterface,
    TitleInterface
};

use \JsonSerializable;
use \Carbon\Carbon;


class Screenplay extends Surface implements ScreenplayInterface, JsonSerializable
{
    protected Cop $cop;

    public function __construct (
        protected ?TitleInterface $title = null,
        protected array $config = [],
        protected ?LicenseInterface $license = null,
        protected array $authors = [],
        protected ?string $id = null,
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
        $this->cop = new Cop;

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

        if ( $guid )
        {
            $this->guid ($guid);
        }

        if ( $taggable )
        {
            $this->taggable ($taggable);
        }

        $this->document = new Document;
    }

    public function author (AuthorInterface $author) : self 
    {
        $this->authors[] = $author;

        return $this;
    }

    public function authors () : array 
    {
        return $this->authors;
    }

    public function bookmark (BookmarkInterface $bookmark) : self 
    {
        $this->document?->bookmarks ($bookmark);

        return $this;
    }

    public function bookmarks () : array 
    {
        return $this->document?->bookmarks ();
    }

    public function color (ColorInterface $color) : self 
    {
        $this->colors[] = $color;

        return $this;
    }

    public function colors () : array 
    {
        return $this->colors;
    }

    public function cover (?CoverInterface $cover = null) : self | CoverInterface
    {
        if ( $cover )
        {
            $this->document->cover ($cover);

            return $this;
        }

        return $this->document?->cover();
    }

    public function decrypt (string $password) 
    {
        $decrypter = new Decrypter;
    }

    private function defaults () : self 
    {
        if (! $this->title )
        {
            $this->title = new Document\Title ("Untitled Screenplay");
        }

        return $this;
    }

    public function derivations () : array
    {
        return $this->derivations;
    }

    public function derivation (DerivationInterface $derivation) : self
    {
        $this->derivations[] = $derivation;

        return $this;
    }

    public function encrypt (string $password) 
    {
        $encrypter = new Encrypter;
    }

    public function footer (?FooterInterface $footer = null) : self | FooterInterface
    {
        if ( $footer )
        {
            $this->document->footer ($footer);

            return $this;
        }

        return $this->document?->footer();
    }

    public function guid (?string $d = null) : self | string 
    {
        if ($d)
        {
            $this->cop->check ('GUID format', $d, ['blank', 'alpha_dash']);

            $this->guid = $d;

            return $this;
        }

        return $this->guid;
    }

    public function header (?HeaderInterface $header = null) : self | HeaderInterface
    {
        if ( $header )
        {
            $this->document->header ($header);

            return $this;
        }

        return $this->document?->header();
    }

    public function license (?LicenseInterface $license = null) : LicenseInterface | self
    {
        if ( $license )
        {
            $this->license = $license;

            return $this;
        }

        return $this->license;
    }

    public function jsonSerialize() : array
    {
        $this->defaults();

        return array_merge ([
            'id'                => is_string ($this->id) ? $this->id : $this->id?->toString(),
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
        ], $this->meta?->all() ?? []);
    }

    public function registration (RegistrationInterface $registration) : self 
    {
        $this->registrations[] = $registration;

        return $this;
    }

    public function registrations () : array 
    {
        return $this->registrations;
    }

    public function save (ExportInterface $exporter, string $save_path) : int
    {

        return filesize ($save_path);
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

    public function scenes () : array 
    {
        return $this?->document->scenes();
    }

    public function status (?StatusInterface $status = null) : self | StatusInterface
    {
        if ( $status )
        {
            $this->document?->status ($status);

            return $this;
        }

        return $this->document?->status ();
    }

    public function style (StyleInterface $style) : self 
    {
        $this->document?->styles ($style);

        return $this;
    }

    public function styles () : array 
    {
        return $this->document?->styles ();
    }

    public function taggable (?array $data = null) : array | self
    {
        if ( $data && is_array ($data) && count ($data) > 1 )
        {
            $this->cop->check ('Taggable items', $data, ['array_slugs']);

            foreach ($data AS $tag) // we can't use array_merge here because the var is protected
            {
                if (! in_array ($tag, $this->taggable) )
                {
                    $this->taggable[] = $tag;
                }
            }

            return $this;
        }

        return $this->taggable;
    }

    public function validate () : array
    {
        return (new Validator)->raw ($this)->errors();
    }
}