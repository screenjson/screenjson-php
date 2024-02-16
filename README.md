# ScreenJSON PHP SDK

This package provides an SDK library for implementing ScreenJSON into a PHP application. It is a *work in progress* and should only be considered an implementation refernece.

## Installation

Require the package:

    composer require screenjson/screenjson-php

## Usage

### Validate a ScreenJSON document

```php
require_once ('vendor/autoload.php');

$validator = new ScreenJSON\Validator ('myfile.json');

if ( $validator->fails() )
{
    print $validator->errors();
}
```

### Encrypt/decrypt a ScreenJSON document

```php
require_once ('vendor/autoload.php');

$encrypted = new ScreenJSON\Encrypter ('myfile.json')
    ->save('encrypted.json', 'mypassword');

$decrypted = new ScreenJSON\Decrypter ('encrypted.txt', 'mypassword');
```

### Import

#### Import a PDF document

```php
require_once ('vendor/autoload.php');

$screenplay = new ScreenJSON\Import\PDF ('myfile.pdf');
```

#### Import a Final Draft Pro document

```php
require_once ('vendor/autoload.php');

$screenplay = new ScreenJSON\Import\FinalDraft ('myfile.fdx');
```

#### Import a FadeIn Pro document

```php
require_once ('vendor/autoload.php');

$screenplay = new ScreenJSON\Import\FadeIn ('myfile.fadein');
```

#### Import a Fountain (Markdown) document

```php
require_once ('vendor/autoload.php');

$screenplay = new ScreenJSON\Import\Fountain ('myfile.fountain');
```

#### Import a Celtx document

```php
require_once ('vendor/autoload.php');

$screenplay = new ScreenJSON\Import\Celtx ('myfile.celtx');
```

### Export

#### Export a ScreenJSON file

```php
require_once ('vendor/autoload.php');

$screenplay->save ('myfile.json');
```

#### Export a YAML file

```php
require_once ('vendor/autoload.php');

$screenplay->save ('myfile.yaml');
```

#### Export a PDF document

```php
require_once ('vendor/autoload.php');

$screenplay->save (new ScreenJSON\Export\PDF, 'myfile.pdf');
```

#### Export a Final Draft Pro document

```php
require_once ('vendor/autoload.php');

$screenplay->save (new ScreenJSON\Export\FinalDraft, 'myfile.fdx');
```

#### Export a FadeIn Pro document

```php
require_once ('vendor/autoload.php');

$screenplay->save (new ScreenJSON\Export\FadeIn, 'myfile.fadein');
```

#### Export a Fountain (Markdown) document

```php
require_once ('vendor/autoload.php');

$screenplay->save (new ScreenJSON\Export\Fountain, 'myfile.fountain');
```

#### Export a Celtx document

```php
require_once ('vendor/autoload.php');

$screenplay->save (new ScreenJSON\Export\Celtx, 'myfile.celtx');
```

### Build a screenplay from scratch

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Screenplay;
use ScreenJSON\Content;
use ScreenJSON\Document\Title;
use ScreenJSON\Document\Cover;
use ScreenJSON\Document\Header;
use ScreenJSON\Document\Footer;
use ScreenJSON\Document\Scene;

use ScreenJSON\Document\Scene\Heading;
use ScreenJSON\Document\Scene\Elements\Action;
use ScreenJSON\Document\Scene\Elements\Character;
use ScreenJSON\Document\Scene\Elements\Dialogue;
use ScreenJSON\Document\Scene\Elements\General;
use ScreenJSON\Document\Scene\Elements\Parenthetical;
use ScreenJSON\Document\Scene\Elements\Shot;
use ScreenJSON\Document\Scene\Elements\Transition;

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay->cover (
        $cover = new Cover (new Title ('My New Story'))
    )
    ->header ($header = (new Header)->content('Here is some header content'))
    ->footer ($footer = (new Footer)->content('Here is some footer content'));

# Content can be a string, string + lang, an array of language-keyed strings, or a Content object (same args)
# Specify it in the constructor, or use the content() helper if you need to build the object manually
$screenplay->scene (
    $scene = (new Scene ($heading = new Heading (new Content ('INT'), new Content ("DON CORLEONE'S HOME OFFICE"), new Content ('DAY'), 1, 8)))
        ->element ((new Action)->content ("Corleone stands, turning his back toward Bonasera."))
        ->element ((new Character)->content ('DON CORLEONE'))
        ->element ((new Parenthetical)->content ('turning, looking around'))
        ->element ((new Dialogue ('O.C', false))->content ([
            'en' => "You come to me on the day of my daughter's wedding, and ask me to do a murder.",
            'fr' => "Tu es venu me voir le jour du mariage de ma fille, et tu m'as demandé de commettre un meurtre.",
        ]))
        ->element ((new Transition)->content ('SLOW PAN TO:'))
        ->element ((new Action)->content ("Bonasera kisses his hand.", 'en'))
        ->element ((new Shot)->content ("CLOSE UP ON HAND"))
        ->element ((new General)->content ("The End"))
)

echo json_encode ($screenplay, JSON_PRETTY_PRINT);
```

### Laravel Integration

*This is very much a work in progress, with placeholder functionality. Do not use in production.*

This SDK library is automatically discoverable by Laravel and injects different root-level objects into the framework's service container as implementation *contracts*.

Example methods:

```php
app (ScreenplayEncryptionContract::class)->load ('myfile.json')->save ('encrypted.json', 'mypassword');
app (ScreenplayDecryptionContract::class)->load ('encrypted.json')->save ('clear.json', 'mypassword');
app (ScreenplayValidationContract::class)->examine ('myfile.json');
```

```php
$screenplay = app (Screenplay::class);
```

To publish the config:

```bash
php artisan vendor:publish --tag=screenjson
```

    
## Structure

* **/docs** is used to store documentation generated by default;
* **/src** is where your codes will live in, each class will need to reside in its own file inside this folder;
* **/tests** each class that you write in src folder needs to be tested before it was even "included" into somewhere else. So basically we have tests classes there to test other classes;
* **/views** is used to store presentation files and assets for providing UI;
* **.gitignore** there are certain files that we don't want to publish in Git, so we just add them to this fle for them to "get ignored by git";
* **CHANGELOG.md** to keep track of package updates;
* **LICENSE** terms of how much freedom other programmers is allowed to use this library;
* **README.md** it is a mini documentation of the library, this is usually the "home page" of your repo if you published it on GitHub and Packagist;
* **composer.json** is where the information about your library is stored, like package name, author and dependencies;
* **phpunit.xml** It is a configuration file of PHPUnit, so that tests classes will be able to test the classes you've written;
* **.travis.yml** basic configuration for Travis CI with configured test coverage reporting for code climate.

Please refer to original [article](http://www.darwinbiler.com/creating-composer-package-library/) for more information.

Useful Tools
============

Running Tests:
--------

    php vendor/bin/phpunit
 
 or 
 
    composer test
    composer pest

Code Sniffer Tool:
------------------

    php vendor/bin/phpcs --standard=PSR12 src/
 
 or
 
    composer psr12-check

Code Auto-fixer:
----------------

    php vendor/bin/phpcbf --standard=PSR12 src/ 
    
 or
 
    composer psr12-autofix
 
 
Building Docs:
--------

    php vendor/bin/phpdoc -d "src" -t "docs"
 
 or 
 
    composer docs

Changelog
=========

To keep track, please refer to [CHANGELOG.md](https://github.com/alexc-hollywood/screenjson-php/blob/master/CHANGELOG.md).

License
=======

Please refer to [LICENSE](https://github.com/alexc-hollywood/screenjson-php/blob/master/LICENSE).
