<?php 

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
use ScreenJSON\Document\Scene\Elements\Dialogue;

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay
    ->cover (new Cover (new Title (
        'My New Story'
    )))
    ->header (new Header (new Content (
        'Here is some header content'
    )))
    ->footer (new Footer (new Content (
        'Here is some footer content'
    )));

$screenplay->scene (
    (new Scene (new Heading (new Content ('INT'), new Content ('SOME LOCATION'), new Content ('DAY'))))
        ->element (new Action (new Content (
            'Something happens here'
        )))
        ->element (new Dialogue (new Content (
            'Hey, give me back my gun!'
        ), 'O.C', false))
);

echo json_encode ($screenplay, JSON_PRETTY_PRINT);