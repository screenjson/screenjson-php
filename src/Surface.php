<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\{
    AnnotationInterface,
    ContentInterface,
    ContributorInterface,
    EncryptionInterface,
    MetaInterface,
    RevisionInterface,
    TitleInterface
};

use ScreenJSON\Enums;

abstract class Surface 
{
    protected Cop $cop;

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

    public function charset (?string $c = null) : self | string 
    {
        if ($c)
        {
            if (! $this->cop ) { $this->cop = new Cop; }

            $this->cop->check ('Charset', $c, ['blank', 'alpha_dash']);

            $this->charset = $c;

            return $this;
        }

        return $this->charset;
    }

    public function content (mixed $value = null, ?string $lang = null) : string | ContentInterface | self 
    {
        $this->cop = new Cop;

        if ($lang)
        {
            $this->cop->check ('Lang', $lang, ['blank', 'alpha_dash', 'lang']);
        }

        if ( $value )
        {
            if ( is_object ($value) && $value instanceof ContentInterface )
            {
                $this->content = $value;
            }

            if ( is_array ($value) && count ($value) ) 
            {
                $this->content = new Content ($value);

                foreach ($value AS $k => $v)
                {
                    $this->cop->check ('Lang', $k, ['blank', 'alpha_dash', 'lang']);
                }
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

    public function dir (?string $d = null) : self | string 
    {
        if ($d)
        {
            if (! $this->cop ) { $this->cop = new Cop; }

            $this->cop->check ('Direction', $d, ['blank', 'alpha_dash']);

            $this->dir = $d;

            return $this;
        }

        return $this->dir;
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
        return is_string ($this->id) ? $this->id : $this->id?->toString();
    }

    public function lang (?string $lang = null) : self | string 
    {
        if ($lang)
        {
            if (! $this->cop ) { $this->cop = new Cop; }

            $this->cop->check ('Lang', $lang, ['blank', 'alpha_dash', 'lang']);

            $this->lang = $lang;

            return $this;
        }

        return $this->lang;
    }

    public function locale (?string $locale = null) : self | string 
    {
        if ($locale)
        {
            $this->cop->check ('Locale', $lang, ['blank', 'alpha_dash']);

            $this->locale = $locale;

            return $this;
        }

        return $this->locale;
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