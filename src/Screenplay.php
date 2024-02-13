<?php 

namespace ScreenJSON;

use Ramsey\Uuid\UuidInterface;
use ScreenJSON\Interfaces\DocumentInterface;
use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\LicenseInterface;
use ScreenJSON\Interfaces\ScreenplayInterface;
use ScreenJSON\Interfaces\TitleInterface;
use \JsonSerializable;
use \Carbon\Carbon;

class Screenplay implements ScreenplayInterface, JsonSerializable
{
    protected UuidInterface $id;

    protected string $guid;

    protected TitleInterface $title;

    protected string $lang;

    protected string $locale;

    protected string $charset;

    protected string $dir;

    protected array $authors = [];
    
    protected array $colors = [];

    protected array $contributors = [];

    protected array $derivations = [];
    
    protected DocumentInterface $document;

    protected EncryptionInterface $encryption;

    protected LicenseInterface $license;

    protected array $registrations = [];

    protected array $revisions = [];

    protected array $taggable = [];

    public function jsonSerialize() : array
    {
        return [
            'id'                => $this->id->toString(),
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