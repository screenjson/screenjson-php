<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\{
    AnnotationInterface,
    ContentInterface,
    ContributorInterface,
    EncryptionInterface,
    MetaInterface,
    RevisionInterface
};

use ScreenJSON\Enums;

abstract class Surface 
{
    protected array $annotations = [];
    protected array $contributors = [];
    protected ?ContentInterface $content = null;
    protected ?EncryptionInterface $encryption = null;
    protected ?MetaInterface $meta = null;
    protected array $revisions = [];

    public function annotations () : array 
    {
        return $this->annotations;
    }

    public function annotation (?AnnotationInterface $annotation = null) : AnnotationInterface | self 
    {
        if ( $annotation )
        {
            $this->annotations[] = $annotation;

            return $this;
        }

        return end ($this->annotations);
    }

    public function content (mixed $value = null, ?string $lang = null) : string | self 
    {
        if ( $value )
        {
            if ( is_object ($value) && $value instanceof ContentInterface )
            {
                $this->content = $value;
            }

            if ( is_array ($value) && count ($value) ) 
            {
                $this->content = new Content ($value);
            }

            if ( is_string ($value) )
            {
                $this->content = $lang ? new Content ([$lang => trim ($value)]) 
                    : new Content ([Enums\Language::ENGLISH => trim ($value)]);
            }

            return $this;
        }

        return $this->content;
    }

    public function contributors () : array 
    {
        return $this->contributors;
    }

    public function contributor (?ContributorInterface $contributor = null) : ContributorInterface | self 
    {
        if ( $contributor )
        {
            $this->contributors[] = $contributor;

            return $this;
        }

        return end ($this->contributors);
    }

    public function encryption (?EncryptionInterface $encryption = null) : EncryptionInterface | self | null
    {
        if ( $encryption )
        {
            $this->encryption = $encryption;

            return $this;
        }

        return $this->encryption;
    }

    public function id () : string 
    {
        return $this->id?->toString();
    }

    public function meta (?array $data = null) : MetaInterface | self | null
    {
        if ( $data )
        {
            if ( $this->meta instanceof MetaInterface )
            {
                $this->meta->add ($data);

                return $this;
            }

            $this->meta = new Meta ($data);

            return $this;
        }

        return $this->meta;
    }

    public function revision (?RevisionInterface $revision = null) : RevisionInterface | self 
    {
        if ( $revision )
        {
            $this->revisions[] = $revision;

            return $this;
        }

        return end ($this->revisions);
    }

    public function revisions () : array 
    {
        return $this->revisions;
    }
}