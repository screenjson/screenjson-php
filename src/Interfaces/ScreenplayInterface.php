<?php 

namespace ScreenJSON\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface ScreenplayInterface 
{
    protected ExportInterface $exporter;

    protected ImportInterface $importer;
    
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
}