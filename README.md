# ScreenJSON PHP SDK

This package provides an SDK library for implementing ScreenJSON into a PHP application. It is a *work in progress* and should only be considered an implementation reference.

## Installation

Require the package:

    composer require screenjson/screenjson-php

## Console application

```bash
php screenjson decrypt ./encrypted.json ./output-clear.json mypassword
php screenjson encrypt ./screenplay.json ./encrypted.json mypassword
php screenjson export ./myfile.json ./myfile.fdx
php screenjson import ./myfile.fadein ./myfile.json
php screenjson validate ./myfile.json
```

## Docker application

Build the container:

```bash
docker build -f docker/Dockerfile -t screenjson-php:latest .
docker push screenjson/screenjson-php:latest
```

Commands:

```bash
docker run screenjson/screenjson-php decrypt ./encrypted.json ./output-clear.json mypassword
docker run screenjson/screenjson-php encrypt ./screenplay.json ./encrypted.json mypassword
docker run screenjson/screenjson-php export ./myfile.json ./myfile.fdx
docker run screenjson/screenjson-php import ./myfile.fadein ./myfile.json
docker run screenjson/screenjson-php validate ./myfile.json
```

## Usage

### Open a ScreenJSON document + misc functions
```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');

$screenplay->output (); // Get a JSON string
```

### Get a raw JSON string rendering
```php
echo $screenplay->output ();
```

### Validate a ScreenJSON document

```php
$validator = new ScreenJSON\Validator ('myfile.json');

if ( $validator->fails() )
{
    print_r ($validator->errors());
}
```

```php
$validator = (new ScreenJSON\Validator)
    ->examine ('myfile.json');

if ( $validator->fails() )
{
    print_r ($validator->errors());
}
```

### Encrypt a ScreenJSON document

```php
$encrypted = new ScreenJSON\Encrypter ('myfile.json')
    ->save ('encrypted.json', 'mypassword');
```

```php
$encrypted = (new ScreenJSON\Encrypter)
    ->load ('myfile.json')
    ->save ('encrypted.json', 'mypassword');
```

### Decrypt a ScreenJSON document

```php
$decrypted = new ScreenJSON\Decrypter ('encrypted.txt')
    ->save ('decrypted.json', 'mypassword');
```

```php
$decrypted = (new ScreenJSON\Decrypter)
    ->load ('encrypted.txt')
    ->save ('decrypted.json', 'mypassword');
```

### Import

#### Import a PDF document

```php
$screenplay = new ScreenJSON\Import\PDF ('myfile.pdf');
```

#### Import a Final Draft Pro document

```php
$screenplay = new ScreenJSON\Import\FinalDraft ('myfile.fdx');
```

#### Import a FadeIn Pro document

```php
$screenplay = new ScreenJSON\Import\FadeIn ('myfile.fadein');
```

#### Import a Fountain (Markdown) document

```php
$screenplay = new ScreenJSON\Import\Fountain ('myfile.fountain');
```

#### Import a Celtx document

```php
$screenplay = new ScreenJSON\Import\Celtx ('myfile.celtx');
```

### Export

#### Export a ScreenJSON file

```php
$screenplay->save (null, 'myfile.json'); // Do not specify an exporter
```

#### Export a YAML file

```php
$screenplay->save (new ScreenJSON\Export\Yaml, 'myfile.yaml');
```

```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');
$screenplay->save (new ScreenJSON\Export\Yaml, 'myfile.yaml');
```

#### Export a PDF document

```php
$screenplay->save (new ScreenJSON\Export\PDF, 'myfile.pdf');
```

```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');
$screenplay->save (new ScreenJSON\Export\PDF, 'myfile.pdf');
```

#### Export a Final Draft Pro document

```php
$screenplay->save (new ScreenJSON\Export\FinalDraft, 'myfile.fdx');
```

```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');
$screenplay->save (new ScreenJSON\Export\FinalDraft, 'myfile.fdx');
```

#### Export a FadeIn Pro document

```php
$screenplay->save (new ScreenJSON\Export\FadeIn, 'myfile.fadein');
```

```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');
$screenplay->save (new ScreenJSON\Export\FadeIn, 'myfile.fadein');
```

#### Export a Fountain (Markdown) document

```php
$screenplay->save (new ScreenJSON\Export\Fountain, 'myfile.fountain');
```

```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');
$screenplay->save (new ScreenJSON\Export\Fountain, 'myfile.fountain');
```

#### Export a Celtx document

```php
// Build your own, then...
$screenplay->save (new ScreenJSON\Export\Celtx, 'myfile.celtx');
```

```php
$screenplay = (new ScreenJSON\Screenplay)->open ('myfile.json');
$screenplay->save (new ScreenJSON\Export\Celtx, 'myfile.celtx');
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
