<?php 

require_once ('vendor/autoload.php');

use ScreenJSON\Screenplay;
use ScreenJSON\Document\Title;

$screenplay = new Screenplay (
    new Title , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

echo json_encode ($screenplay, JSON_PRETTY_PRINT);