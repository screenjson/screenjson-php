<?php 

namespace ScreenJSON\Import;

use ScreenJSON\Interfaces\FadeInInterface;
use ScreenJSON\Interfaces\ImportInterface;
use ScreenJSON\Interfaces\ParserInterface;

use ScreenJSON\Exceptions\InvalidFileFormatException;

use ScreenJSON\Cop;
use ScreenJSON\Screenplay;
use ScreenJSON\Content;
use ScreenJSON\Document;
use ScreenJSON\Document\Scene\Elements;

use \SimpleXMLElement;

class FadeIn extends Importer implements FadeInInterface, ImportInterface, ParserInterface
{
    protected Cop $cop;

    public function __construct (
        protected string $file_path,
        protected string $ext = 'fadein',
        protected ?SimpleXMLElement $original = null
    ) {
        $this->cop = new Cop;

        $this->file_path = $file_path;

        $this->validate ();
        $this->parse ();
    }

    private function __context (SimpleXMLElement $xml) : string 
    {
        if ( str_starts_with ($xml->text->__toString(), "I/"))
        {
            return 'I/E';
        }

        if ( str_starts_with ($xml->text->__toString(), "INT/"))
        {
            return 'INT/EXT';
        }

        if ( str_starts_with ($xml->text->__toString(), "INT"))
        {
            return 'INT';
        }

        if ( str_starts_with ($xml->text->__toString(), "EXT/"))
        {
            return 'EXT/INT';
        }

        if ( str_starts_with ($xml->text->__toString(), "EXT"))
        {
            return 'EXT';
        }

        if ( str_starts_with ($xml->text->__toString(), "POV"))
        {
            return 'INT';
        }

        return 'INT';
    }

    private function __element (SimpleXMLElement $xml) : string 
    {
        if ( stristr ($xml->style->attributes()['basestyle']->__toString (), 'Scene'))
        {
            return 'scene';
        }

        return mb_strtolower ($xml->style->attributes()['basestyle']->__toString ());
    }

    private function __origin (SimpleXMLElement $xml) : string | null
    {
        if (! str_contains ($xml->text->__toString(), '('))
        {
            return null;
        }

        return explode(')', (explode('(', $xml->text->__toString())[1]))[0];
    }

    private function __setting (SimpleXMLElement $xml) : string
    {
        $before_last_dash = trim (substr ($xml->text->__toString(), 0, strrpos ($xml->text->__toString(), '-')));

        $after_first_space = trim (substr ($before_last_dash, strpos ($before_last_dash, " ") + 1));

        return $after_first_space;
    }

    private function __sequence (SimpleXMLElement $xml) : string
    {
        return substr ($xml->text->__toString(), strrpos($xml->text->__toString(), '-') + 1);
    }

    public function parse () : self
    {
        $zip = new \ZipArchive();

        if ( $zip->open ($this->file_path) === TRUE )
        {
            $doc_xml = $zip->getFromName('document.xml');
            $this->original = simplexml_load_string ($doc_xml);
            $zip->close();
        }

        $title = null;

        foreach ($this->original->children()->titlepage->children() AS $para)
        {
            if ( isset ($para->attributes()['bookmark']) ) 
            {
                $title = $para->text->__toString();
            }
        }

        $this->screenplay = new Screenplay (new Document\Title ($title), [
            'guid'    => 'rfc4122',
            'lang'    => 'en',
            'locale'  => 'en_GB',
            'charset' => 'utf8',
            'dir'     => 'ltr'
        ]);

        foreach ($this->original->children()->settings AS $setting)
        {
            $this->screenplay
                ->header (
                    new Document\Header (
                        new Content($setting->attributes()['page_header']), 1, boolval ($setting->attributes()['header_first_page']), 1
                    )
                )
                ->footer (
                    new Document\Footer (
                        new Content ($setting->attributes()['page_footer']), 1, boolval ($setting->attributes()['footer_first_page']), 1
                    )
                );
        }

        $counter = 1;
        $scene = null;
        $origin = false;

        foreach ($this->original->children()->paragraphs->children() AS $para)
        {
            $type = $this->__element ($para);

            if ( $type == 'scene' )
            {             
                $this->screenplay->scene (
                    $scene = (
                        new Document\Scene (
                            $heading = new Document\Scene\Heading (
                                new Content ($this->__context ($para) ?? 'UNK'), 
                                new Content ($this->__setting ($para) ?? 'UNKNOWN'), 
                                new Content ($this->__sequence ($para) ?? 'UNK'), $counter, $counter
                            )
                        )
                    )
                );


                $counter++;
            } 
            else 
            {
                $element_classes = [
                    'action'        => Elements\Action::class,
                    'character'     => Elements\Character::class,
                    'dialogue'      => Elements\Dialogue::class,
                    'general'       => Elements\General::class,
                    'parenthetical' => Elements\Parenthetical::class,
                    'shot'          => Elements\Shot::class,
                    'transition'    => Elements\Transition::class,
                ];

                if ($type == 'character')
                {
                    if (str_contains ($para->text->__toString(), '('))
                    {
                        $origin = $this->__origin ($para);
                    }
                }

                if ($type == 'dialogue')
                {
                    $scene->element (new Elements\Dialogue ($origin, false, new Content ($para->text->__toString())));
                    $origin = null;
                }
                else 
                {
                    $scene->element (new $element_classes[$type] (new Content ($para->text->__toString())));
                }
                
            }


        }

        return $this;
    }

    public function validate () : self 
    {
        libxml_use_internal_errors (true);

        if ( pathinfo ($this->file_path, PATHINFO_EXTENSION) != $this->ext )
        {
            throw new InvalidFileFormatException ("File extension must be ".$this->ext);
        }

        $this->cop->check ('File', $this->file_path, ['file', 'exists', 'readable', 'mime_zip']);

        if (! is_resource ($zip = zip_open ($this->file_path)) )
        {
            throw new InvalidFileFormatException ("Could not open file as a system resource.");
        }

        // Check the zip has the right XML file
        $zip = new \ZipArchive();

        if ( $zip->open ($this->file_path) === TRUE )
        {
            if (!$doc_xml = $zip->getFromName('document.xml') )
            {
                throw new InvalidFileFormatException ("Unable to find document.xml in file.");
            }

            if (! simplexml_load_string ($doc_xml) )
            {
                throw new InvalidFileFormatException ("Unable to read document.xml XML structure.");

                $zip->close();
            }
        }

        return $this;
    }
}