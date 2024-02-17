<?php 

require_once ('vendor/autoload.php');

use ScreenJSON\{
    Annotation,
    Author,
    Color,
    Content,
    Contributor,
    Derivation,
    Encryption,
    License,
    Meta,
    Registration,
    Revision,
    Screenplay,
    Style
};

use ScreenJSON\Document\{
    Cover, 
    Header,
    Footer,
    Title
};

use Carbon\Carbon;

$author_a = (new Author ('Joe', 'Bloggs', ['writer']))->meta (['imdb' => 'wga123098']);
$author_b = (new Author ('Jane', 'Smith', ['reviser']))->meta (['imdb' => 'wga8765']);

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay
->author ($author_a)
->author ($author_b)
->contributor (new Contributor ('Fred', 'Thompson', ['editor']))
->contributor (new Contributor ('Alice', 'Bobster', ['doctor']))
->registration (new Registration ('WGA', 'ABC123DEF789', Carbon::parse ('2004-02-12T15:19:21+00:00'), Carbon::parse ('2004-02-12T15:19:21+00:00')))
->derivation ((new Derivation ('movie', new Content ('The Godfather')))->meta (['imdb' => 'tl64765h']))
->license (new License ('CC-BY-NC-ND-4.0', 'https://spdx.org/licenses'))
->encryption (new Encryption ('aes-256-ctr', 'sha256', 'base64'))
->revision (new Revision ('14', 3, [$author_a->id()]))
->taggable (['foo', 'bar', 'abc', 'def', 'something'])
->color (new Color ('black', [0, 0, 0], '#000'))
->color (new Color ('white', [255, 255, 255], '#FFF'))
->color (new Color ('pink', [255, 192, 203], '#FFC0CB'))
->annotation (new Annotation (new Content ('Not for external use.'), $author_a->id(), 'pink', Carbon::now(), [10, 18]))
->meta (['foo' => 'bar', 'lorem' => 'epsom'])
->cover (new Cover (new Title ('My New Story')))
->header (new Header (new Content('Here is some header content.')))
->footer (new Footer (new Content ('Here is some footer content')));

echo json_encode ($screenplay, JSON_PRETTY_PRINT);