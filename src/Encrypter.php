<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\EncrypterInterface;

use \Exception;

use ScreenJSON\Enums;

class Encrypter implements EncrypterInterface 
{
    protected Cop $cop;

    public function __construct (
        protected ?string $file_path = null,
        protected ?string $password = null,
        protected ?string $key = null,
        protected ?string $iv = null,
        private ?object $representation = null,
        private ?object $encrypted = null
    )
    {
        $this->cop = new Cop;

        if ($this->file_path)
        {
            $this->load ($file_path);
        }
    }

    private function __setup () : void
    {
        if (! $this->key )
        {
            $this->key = hash (Enums\Hash::SHA256, $this->password);
        }

        if (! $this->iv )
        {
            $this->iv = openssl_random_pseudo_bytes (openssl_cipher_iv_length (Enums\Cipher::AES_256), false);
        }
    }

    private function encrypt (string $plain) : string 
    {
        return base64_encode (
            openssl_encrypt ($plain, Enums\Cipher::AES_256, $this->key, 0, $this->iv)
        );
    }

    public function load (string $file_path) : self
    {
        $this->cop->check ('JSON file', $file_path, ['file', 'exists', 'readable', 'mime_json']);

        $validator = (new Validator)->examine ($file_path);

        if ($validator->fails())
        {
            throw new Exception ("File is not formatted as ScreenJSON.");
        }

        $this->representation = json_decode (file_get_contents ($file_path), JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR);

        return $this;
    }

    private function process () : self
    {
        if (! $this->representation || !is_object ($this->representation) )
        {
            throw new Exception ("Unable to read internal onject representation of JSON structure.");
        }

        $this->__setup ();

        $this->encrypted = clone $this->representation;

        $this->encrypted->title = $this->translations ($this->representation->title);
        $this->encrypted->cover->title = $this->translations ($this->representation->cover->title);
        $this->encrypted->cover->additional = $this->translations ($this->representation->cover->additional);
        $this->encrypted->header->content = $this->translations ($this->representation->header->content);
        $this->encrypted->footer->content = $this->translations ($this->representation->footer->content);

        foreach ($this->representation->document->scenes AS $index => $scene)
        {
            $this->encrypted->scenes[$index]->description->content = $this->translations ($scene->description->content);
            $this->encrypted->scenes[$index]->heading->setting->content = $this->translations ($scene->heading->setting->content);

            foreach ($scene->body AS $el_index => $element)
            {
                $this->encrypted->scenes[$index]->body[$el_index]->content = $this->translations ($element->content);
            }
        }

        return $this;
    }

    public function save (string $save_path, string $password) : int
    {
        $this->password = $password;

        $this->cop->check ('Save location', basename ($save_path), ['writable', 'exists']);
        $this->cop->check ("Password", $password, ['blank']);

        $this->process ();

        $this->encrypted->encryption = new Encryption (Enums\Cipher::AES_256, Enums\Hash::SHA256, Enums\Encoding::BASE64);

        file_put_contents (
            $save_path,
            json_encode (
                $this->encrypted, 
                JSON_PRETTY_PRINT | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
            )
        );
        
        return filesize ($save_path);
    }

    private function translations (object $translatable) : object 
    {
        foreach ($translatable AS $lang => $chars)
        {
            $translatable->{$lang} = $this->encrypt ($chars);
        }

        return $translatable;
    }
}