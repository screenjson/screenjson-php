<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\AnnotationInterface;
use ScreenJSON\Interfaces\ContentInterface;
use ScreenJSON\Interfaces\ContributorInterface;
use ScreenJSON\Interfaces\EncryptionInterface;
use ScreenJSON\Interfaces\MetaInterface;
use ScreenJSON\Interfaces\RevisionInterface;

use ScreenJSON\Enums;

abstract class Common 
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

    public function content (?mixed $value = null, ?string $lang = null) : string | self 
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
                $this->content = $lang ? new Content ([$lang => $value]) 
                    : new Content ([Enums\Language::ENGLISH => $value]);
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

    public function meta (?MetaInterface $meta = null) : MetaInterface | self | null
    {
        if ( $meta )
        {
            $this->meta = $meta;

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